<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // ═══════════════════════════════════════════
    // INDEX — Tampilkan daftar semua event
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $user  = auth()->user();
        $month = $request->query('month'); // filter bulan (untuk regular user)

        $query = Event::withCount('participants')
                       ->with('participants');

        // Filter bulan hanya untuk regular user
        if ($month && (!$user || $user->role !== 'admin')) {
            $query->whereMonth('event_date', $month);
        }

        $events = $query->orderByDesc('event_date')->get();

        // Tandai apakah user sudah join tiap event
        if ($user) {
            $joinedIds = $user->participatedEvents()->pluck('events.id')->toArray();
            $events->each(function ($event) use ($joinedIds) {
                $event->is_joined = in_array($event->id, $joinedIds);
            });
        }

        return view('aksi.index', compact('events', 'month'));
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

        return redirect()->route('aksi.index')->with('success', 'Anggota berhasil dihapus dari event!');
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

        $messages = $event->messages()->with('user')->orderBy('created_at')->get();

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

        $request->validate(['message' => 'required|string|max:1000']);

        EventMessage::create([
            'event_id' => $event->id,
            'user_id'  => $user->id,
            'message'  => $request->message,
        ]);

        return redirect()->route('aksi.chat', $event)->with('success', 'Pesan terkirim!');
    }

    // ═══════════════════════════════════════════
    // DELETE MESSAGE — Admin: Hapus pesan di chat
    // ═══════════════════════════════════════════
    public function deleteMessage(Event $event, EventMessage $message)
    {
        // Pastikan pesan yang akan dihapus memang milik event ini
        if ($message->event_id === $event->id) {
            $message->delete();
        }

        return redirect()->route('aksi.chat', $event)->with('success', 'Pesan berhasil dihapus!');
    }
}
