<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\RankingService;

class EventController extends Controller
{
    // ═══════════════════════════════════════════
    // INDEX — Tampilkan daftar semua event
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $user   = auth()->user();
        $month  = $request->query('month'); // filter bulan (untuk regular user)
        $search = $request->query('search'); // fitur search

        $query = Event::withCount('participants')
                       ->with('participants');

        // Filter bulan hanya untuk regular user
        if ($month && (!$user || $user->role !== 'admin')) {
            $query->whereMonth('event_date', $month);
        }

        // Fitur pencarian berdasarkan nama atau lokasi event
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->orderByDesc('event_date')->get();

        // Tandai apakah user sudah join tiap event
        if ($user) {
            $joinedIds = $user->participatedEvents()->pluck('events.id')->toArray();
            $events->each(function ($event) use ($joinedIds) {
                $event->is_joined = in_array($event->id, $joinedIds);
            });
        }

        return view('aksi.index', compact('events', 'month', 'search'));
    }

    // ═══════════════════════════════════════════
    // STORE — Admin: Tambah event baru
    // ═══════════════════════════════════════════
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'required|date',
            'location'    => 'nullable|string|max:255',
            'organizer'   => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'event_date'  => $validated['event_date'],
            'location'    => $validated['location']   ?? null,
            'organizer'   => $validated['organizer']  ?? null,
            'image_path'  => $imagePath,
        ]);

        return redirect()->route('aksi.index')->with('success', 'Event berhasil ditambahkan!');
    }

    // ═══════════════════════════════════════════
    // UPDATE — Admin: Edit event
    // ═══════════════════════════════════════════
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'required|date',
            'location'    => 'nullable|string|max:255',
            'organizer'   => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($event->image_path && Storage::disk('public')->exists($event->image_path)) {
                Storage::disk('public')->delete($event->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'event_date'  => $validated['event_date'],
            'location'    => $validated['location']   ?? null,
            'organizer'   => $validated['organizer']  ?? null,
            'image_path'  => $validated['image_path'] ?? $event->image_path,
        ]);

        return redirect()->route('aksi.index')->with('success', 'Event berhasil diperbarui!');
    }

    // ═══════════════════════════════════════════
    // DESTROY — Admin: Hapus event
    // ═══════════════════════════════════════════
    public function destroy(Event $event)
    {
        if ($event->image_path && Storage::disk('public')->exists($event->image_path)) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('aksi.index')->with('success', 'Event berhasil dihapus!');
    }

    // ═══════════════════════════════════════════
    // REMOVE MEMBER — Admin: Hapus anggota event
    // ═══════════════════════════════════════════
    public function removeMember(Request $request, Event $event, int $user_id)
    {
        $event->participants()->detach($user_id);

        // Hapus poin yang didapatkan dari event ini jika ada
        $user = \App\Models\User::find($user_id);
        if ($user) {
            $ledger = \App\Models\PointLedger::where('user_id', $user->id)
                ->where('description', 'like', '%(ID: ' . $event->id . ')%')
                ->first();

            if ($ledger) {
                $user->eco_points = max(0, ($user->eco_points ?? 0) - $ledger->points);
                $user->eco_level = $user->calculateEcoLevel();
                $user->save();
                
                $ledger->delete();
            }
        }

        return redirect()->route('aksi.index')->with('success', 'Anggota berhasil dihapus dari event beserta poinnya!');
    }

    // ═══════════════════════════════════════════
    // JOIN — Regular User: Bergabung ke event
    // ═══════════════════════════════════════════
    public function join(Event $event)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('aksi.index')->with('error', 'Admin tidak perlu bergabung ke event.');
        }

        if (!$event->isJoinedBy($user->id)) {
            $event->participants()->attach($user->id);

            // Cek apakah user sudah pernah dapat poin dari event ini sebelumnya
            $hasEarned = \App\Models\PointLedger::where('user_id', $user->id)
                ->where('description', 'like', '%(ID: ' . $event->id . ')%')
                ->exists();

            if (!$hasEarned) {
                RankingService::addPoints($user, 'community_action', null, 'Bergabung ke event: ' . $event->name . ' (ID: ' . $event->id . ')');
            }
        }

        return redirect()->route('aksi.index')->with('success', 'Berhasil bergabung ke event "' . $event->name . '"!');
    }

    // ═══════════════════════════════════════════
    // LEAVE — Regular User: Batal ikut event
    // ═══════════════════════════════════════════
    public function leave(Event $event)
    {
        $user = auth()->user();

        $event->participants()->detach($user->id);

        // Hapus poin yang didapatkan dari event ini jika ada
        $ledger = \App\Models\PointLedger::where('user_id', $user->id)
            ->where('description', 'like', '%(ID: ' . $event->id . ')%')
            ->first();

        if ($ledger) {
            $user->eco_points = max(0, ($user->eco_points ?? 0) - $ledger->points);
            $user->eco_level = $user->calculateEcoLevel();
            $user->save();
            
            $ledger->delete();
        }

        return redirect()->route('aksi.index')->with('success', 'Berhasil membatalkan keikutsertaan dari event "' . $event->name . '"!');
    }

    // ═══════════════════════════════════════════
    // CHAT — Tampilkan dan kirim pesan grup event
    // ═══════════════════════════════════════════
    public function chat(Event $event)
    {
        $user = auth()->user();

        // Pastikan user sudah join event untuk mengakses chat
        if (!$event->isJoinedBy($user->id) && $user->role !== 'admin') {
            return redirect()->route('aksi.index')->with('error', 'Anda harus bergabung ke event terlebih dahulu untuk mengakses chat!');
        }

        $messages = $event->messages()->with(['user', 'reactions.user'])->orderBy('created_at')->get();

        return view('aksi.chat', compact('event', 'messages'));
    }

    // ═══════════════════════════════════════════
    // SEND MESSAGE — Kirim pesan ke chat event
    // ═══════════════════════════════════════════
    public function sendMessage(Request $request, Event $event)
    {
        $user = auth()->user();

        if (!$event->isJoinedBy($user->id) && $user->role !== 'admin') {
            return redirect()->route('aksi.index')->with('error', 'Anda harus bergabung ke event untuk mengirim pesan!');
        }

        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048'
        ]);

        if (empty($request->message) && !$request->hasFile('image')) {
            return back()->with('error', 'Pesan atau foto harus diisi!');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        EventMessage::create([
            'event_id'   => $event->id,
            'user_id'    => $user->id,
            'message'    => $request->message ?? '',
            'image_path' => $imagePath,
        ]);

        return redirect()->route('aksi.chat', $event)->with('success', 'Pesan terkirim!');
    }

    // ═══════════════════════════════════════════
    // REACT — Tambah/Hapus Reaksi
    // ═══════════════════════════════════════════
    public function react(Request $request, Event $event, EventMessage $message)
    {
        $user = auth()->user();

        if (!$event->isJoinedBy($user->id) && $user->role !== 'admin') {
            return back()->with('error', 'Anda harus bergabung ke event untuk memberikan reaksi!');
        }

        $request->validate(['reaction' => 'required|string|max:10']);

        // Check if user already reacted
        $existingReaction = \App\Models\EventMessageReaction::where('event_message_id', $message->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction === $request->reaction) {
                // If same reaction, remove it (toggle)
                $existingReaction->delete();
            } else {
                // Change reaction type
                $existingReaction->update(['reaction' => $request->reaction]);
            }
        } else {
            \App\Models\EventMessageReaction::create([
                'event_message_id' => $message->id,
                'user_id'          => $user->id,
                'reaction'         => $request->reaction,
            ]);
        }

        return back();
    }

    // ═══════════════════════════════════════════
    // DELETE MESSAGE — Admin: Hapus pesan di chat
    // ═══════════════════════════════════════════
    public function deleteMessage(Event $event, EventMessage $message)
    {
        // Pastikan pesan yang akan dihapus memang milik event ini
        if ($message->event_id === $event->id) {
            if ($message->image_path && Storage::disk('public')->exists($message->image_path)) {
                Storage::disk('public')->delete($message->image_path);
            }
            $message->delete();
        }

        return redirect()->route('aksi.chat', $event)->with('success', 'Pesan berhasil dihapus!');
    }
}
