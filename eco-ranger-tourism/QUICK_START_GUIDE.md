# Eco Rankings - Quick Start Guide

## 🚀 Instalasi & Setup

### 1. **Run Migration** (shared_contents table)
```bash
php artisan migrate --force
```

### 2. **Clear All Caches**
```bash
php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan config:clear
```

### 3. **[Opsional] Seed Demo Data**
```bash
php artisan db:seed --class=EcoRankingSeeder
```

---

## 🌐 Akses Halaman

**URL**: `http://localhost:8000/eco-rankings`

**Route Name**: `eco.rankings`

---

## 📊 Sistem Poin Explained

### Real-Time Calculation
Poin dihitung OTOMATIS dari database setiap kali halaman di-refresh:

```php
// Di app/Models/User.php
public function getTotalPointsAttribute()
{
    $points = 0;
    $points += $this->reports()->count() * 10;           // 10 poin/report
    $points += $this->eventParticipations()->count() * 50; // 50 poin/event
    $points += $this->reports()->where('status', 'diverifikasi')->count() * 5; // 5 poin/verifikasi
    $points += $this->forumPosts()->count() * 15;        // 15 poin/forum post
    $points += $this->sharedContents()->count() * 20;    // 20 poin/content
    return $points;
}
```

### Point Sources
| Activity | Points | Table | Count Method |
|----------|--------|-------|--------------|
| Report Issue | +10 | `reports` | COUNT all |
| Join Event | +50 | `participant_events` | COUNT all |
| Verify Report | +5 | `reports` | WHERE status='diverifikasi' |
| Forum Post | +15 | `forum_diskusis` | COUNT all |
| Share Content | +20 | `shared_contents` | COUNT all |

---

## 🎯 Manual Testing (Without Seeder)

### Add Data Using Tinker:

```bash
php artisan tinker
```

```php
// Get a user
$user = App\Models\User::find(1);

// Add reports (10 points per report)
App\Models\Report::create([
    'user_id' => $user->id,
    'title' => 'Test Report',
    'description' => 'Test description',
    'latitude' => -6.1234,
    'longitude' => 106.5678,
    'status' => 'diverifikasi',
    'report_date' => now(),
]);

// Add forum posts (15 points per post)
App\Models\ForumDiskusi::create([
    'user_id' => $user->id,
    'topic' => 'Test Topic',
    'message' => 'Test message',
]);

// Add shared content (20 points per content)
App\Models\SharedContent::create([
    'user_id' => $user->id,
    'title' => 'Test Content',
    'description' => 'Test description',
    'type' => 'image',
]);

// Join event (50 points per event)
$user->eventParticipations()->attach(1); // Attach to event ID 1

// Check total points
dd($user->total_points); // Will show calculated points

// Exit tinker
exit;
```

---

## 🏆 Levels & Tiers

### Level based on Total Points:
- **0 - 999**: ECO-MEMBER
- **1000 - 1999**: ECO-LEADER
- **2000 - 2399**: ECO-WARRIOR
- **≥ 2400**: ECO-RANGER

---

## 🎁 Badges & Achievements

5 Badges with DYNAMIC progress from database:

1. **🪣 Plastic Hunter** → Report 10 garbage piles
2. **🌳 Tree Hugger** → Join 5 tree planting events
3. **🐢 Turtle Saver** → Verify/report 3 items
4. **💬 Eco-Speaker** → Post 20 forum messages
5. **📸 Green Influencer** → Share 10 contents

Progress auto-updates based on real database counts.

---

## 💰 Rewards

**Top 3 Eco-Warriors get vouchers:**
- 🥇 **Rank #1** → Rp 500.000 Voucher (Hotel & Event)
- 🥈 **Rank #2** → Rp 250.000 Voucher (Tour Package)
- 🥉 **Rank #3** → Rp 100.000 Voucher (Culinary & Merch)

---

## 📱 Features

### **Page Sections:**
1. ✅ Top 3 Podium - Gold/Silver/Bronze styling
2. ✅ Reward Vouchers - Top 3 hadiah
3. ✅ Full Leaderboard - Tabel dengan paginasi (10/halaman)
4. ✅ Point Rules - 5 sumber poin
5. ✅ Badges & Achievements - Progress tracking (logged-in users)
6. ✅ Current Rank - User's position (logged-in users)

### **Responsive:**
- ✅ Desktop: Full layout
- ✅ Tablet: Adjusted grid
- ✅ Mobile: Single column

---

## 🔧 Customization

### Change Points Value
Edit `app/Http/Controllers/EcoRankingController.php`:

```php
// In getTotalPointsAttribute():
$points += $this->reports()->count() * 10;  // Change 10 to X

// Or in method calculateBadgeProgress():
'target' => 10,  // Change badge targets
```

### Change Badge Icons/Names
Edit `app/Http/Controllers/EcoRankingController.php` → `getBadges()`:

```php
[
    'name' => 'Plastic Hunter',  // Change name
    'icon' => '🪣',             // Change emoji
    'target' => 10,             // Change target
]
```

### Change Reward Amounts
Edit `getRewards()` method:

```php
'amount' => 'Rp 500.000',  // Change voucher amount
```

### Change Colors/Styling
Edit `resources/views/eco-rankings.blade.php`:

```blade
<!-- Change Tailwind classes for colors -->
<div class="bg-emerald-600">...</div>  <!-- Change to other colors -->
```

---

## ⚡ Performance Tips

### For Large Number of Users:
If > 1000 users and performance is slow:

```php
// Option 1: Add caching in controller
$allUsers = Cache::remember('eco_rankings_users', 300, function() {
    return User::with(['reports', 'forumPosts', 'sharedContents', 'eventParticipations'])
        ->get();
});

// Option 2: Create cached 'total_points' column
// Migration: ALTER TABLE users ADD COLUMN total_points_cached INT DEFAULT 0;
// Then update via batch job/queue
```

---

## 🐛 Troubleshooting

### Issue: Page not loading / 404 error
**Solution:**
```bash
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

### Issue: Points not updating
**Solution:**
1. Check data exists: `php artisan tinker` → `dd(App\Models\User::find(1)->reports()->count());`
2. Verify relationships in User model
3. Hard refresh browser (Ctrl+Shift+R)

### Issue: Badge progress incorrect
**Solution:**
1. Check database has correct event/content names
2. Verify `calculateBadgeProgress()` logic in controller
3. Test with: `php artisan tinker` → `dd($user->total_points);`

### Issue: Pagination not showing
**Solution:**
- Pagination shows only if > 10 rankings (more than top 3)
- Need at least 13 users or seed enough test data

---

## 📚 File Structure

```
Created/Modified Files:
├── database/migrations/2026_04_15_000001_create_shared_contents_table.php (NEW)
├── database/seeders/EcoRankingSeeder.php (NEW)
├── app/Models/
│   ├── User.php (MODIFIED)
│   ├── SharedContent.php (NEW)
│   ├── ForumDiskusi.php (NEW)
│   └── Event.php (NEW)
├── app/Http/Controllers/EcoRankingController.php (NEW)
├── resources/views/eco-rankings.blade.php (NEW)
├── routes/web.php (MODIFIED - Added /eco-rankings route)
├── ECO_RANKINGS_DOCUMENTATION.md (NEW)
└── QUICK_START_GUIDE.md (THIS FILE)
```

---

## ✅ What You Get

✅ Real-time ranking system (updated instantly from database)
✅ 5 dynamic badges with progress tracking
✅ Gorgeous UI with Tailwind CSS
✅ Responsive design (mobile-friendly)
✅ Pagination support
✅ Role-aware content (login required for badges)
✅ Top 3 podium with rewards
✅ Easy to customize

---

## 🎯 Next Steps

### Recommended:
1. ✅ Run migration
2. ✅ Seed demo data or add manual data
3. ✅ Visit `/eco-rankings` and test
4. ✅ Customize colors/points/badges as needed
5. ✅ Integrate into your navigation menu

### Optional:
- Add weekly/monthly rankings
- Email notifications for rank changes
- Admin dashboard for points management
- Voucher redemption system
- Leaderboard API endpoints

---

## 📖 Need Help?

1. **Data issues**: Use `php artisan tinker` to inspect database
2. **Display issues**: Check browser console (F12) for errors
3. **Performance**: Review controller query optimization
4. **Customization**: Edit controller logic or blade styling

---

**Version**: 1.0
**Status**: ✅ Ready to Use
**Last Updated**: 2026-04-15

Happy Eco Ranking! 🌿♻️🌍
