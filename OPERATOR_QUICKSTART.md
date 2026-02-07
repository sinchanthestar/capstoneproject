# 🚀 Operator Module - Quick Start Guide

## What Was Built

A complete **Operator Role** feature set in your Laravel attendance system with 7 main components:

1. **Dashboard** - Overview with analytics and statistics
2. **Attendance Management** - Full CRUD operations for daily attendance
3. **Permission Approval** - Approve/reject employee leave requests
4. **Real-time Monitoring** - Live check-in status display
5. **Reports** - Daily, weekly, and monthly attendance summaries
6. **Schedule Views** - View employee schedules (read-only)
7. **Data Input** - Limited data entry for attendance and notes

---

## 🎯 How Operators Access Features

### Via Sidebar Menu
When logged in as an Operator, sidebar shows:
```
📊 Dashboard (Operator Panel)
├── 🛡️ Dashboard
├── 📋 Kelola Absensi
├── 📥 Verifikasi Izin
├── 📡 Monitoring Real-time
└── 📅 Laporan
    ├── Harian
    ├── Mingguan
    └── Bulanan
```

### Direct URLs
- Dashboard: `/operator/dashboard`
- Attendance: `/operator/attendance`
- Permissions: `/operator/permissions`
- Monitoring: `/operator/monitoring`
- Reports: `/operator/reports/{daily|weekly|monthly}`

---

## 📊 Features in Detail

### 1️⃣ Dashboard (`/operator/dashboard`)
**What you see:**
- Quick access buttons to all features
- Today's summary: Hadir, Telat, Izin, Belum Check-in
- Monthly chart showing attendance trends
- Top 5 absent employees
- Top 5 late employees
- Shift distribution (Pagi/Siang/Malam)
- Recent check-ins from last 7 days

**Filter options:** Month, Year dropdown selectors

---

### 2️⃣ Kelola Absensi (`/operator/attendance`)
**View:**
- Two-column layout:
  - Left: Sudah Check-in (✓ green)
  - Right: Belum Check-in (✗ red)

**Actions available:**
- ✏️ Edit existing record
- 🗑️ Delete record
- ✓ Hadir (quick action)
- ⏱️ Izin (quick action)
- ✗ Alpha (quick action)

**Forms:**
- Create new: Date, Schedule, Status, Check-in/out time, Notes
- Edit: Update existing record

**Filters:** Date, Shift, Employee name search

---

### 3️⃣ Verifikasi Izin/Sakit (`/operator/permissions`)
**View:**
- Tab-based filtering:
  - Pending (⏳ yellow)
  - Approved (✓ green)
  - Rejected (✗ red)
  - All

**Actions:**
- 👁️ Review (see details)
- ✅ Approve (with optional notes)
- ❌ Reject (with required reason)

**Information shown:**
- Employee name
- Leave type (Izin/Cuti)
- Date and shift
- Reason
- Approval status

---

### 4️⃣ Monitoring Real-time (`/operator/monitoring`)
**Display:**
- Statistics cards:
  - Total scheduled
  - Checked in (count)
  - Not checked in (count)
  - Percentage checked in

**Two-column layout:**
- Sudah Check-in (list with names/times)
- Belum Check-in (list with names)

**Filters:** Date, Shift, plus Search button

---

### 5️⃣ Laporan/Reports (`/operator/reports/`)

#### Daily (`/operator/reports/daily`)
- Single date selector
- Summary cards: Total, Hadir, Telat, Absen
- Table: Employee, Shift, Check-in, Status

#### Weekly (`/operator/reports/weekly`)
- Date range selector (start - end)
- Table: Employee, Total Jadwal, Hadir, Absen, Percentage
- Progress bars for visual

#### Monthly (`/operator/reports/monthly`)
- Month & Year dropdowns
- Table: Employee, Total Jadwal, Hadir, Telat, Absen, Percentage
- All with badges and progress indicators

---

## 🔒 Security Features

### Who can access?
✅ Users with `role = 'Operator'`
❌ Regular users ('User' role) - NO ACCESS
❌ Admins must be assigned Operator role separately

### How are routes protected?
All operator routes use middleware:
```php
Route::middleware(['auth', 'web', 'CheckRole:Operator'])->group(...)
```

### What can operators NOT do?
❌ Create user accounts
❌ Change user roles
❌ Modify system settings
❌ Access admin panel
❌ Delete employees

---

## 💾 Database Requirements

Ensure these columns exist:

**users table:**
- `role` varchar(50) - Must be exactly 'Operator'

**attendances table:**
- `check_in_time` timestamp nullable
- `check_out_time` timestamp nullable
- `is_late` boolean default false
- `status` varchar (hadir/telat/izin/alpha)
- `schedule_id` foreign key
- `user_id` foreign key

**schedules table:**
- `schedule_date` date
- `user_id` foreign key
- `shift_id` foreign key

**shifts table:**
- `shift_name` varchar
- `start_time` time
- `end_time` time
- `category` varchar (Pagi/Siang/Malam)

**permissions table:**
- `user_id` foreign key
- `schedule_id` foreign key
- `status` varchar (pending/approved/rejected)
- `type` varchar (izin/cuti/sakit)
- `reason` text
- `approved_by` bigint unsigned nullable
- `rejection_reason` text nullable

---

## 🧪 Quick Test

### Create Test Operator
```bash
php artisan tinker

User::create([
    'name' => 'Op Test',
    'email' => 'op@test.com',
    'password' => bcrypt('test123'),
    'role' => 'Operator'
]);
```

### Verify Routes
```bash
php artisan route:list | Select-String "operator"
```

### Test in Browser
1. Login: `op@test.com` / `test123`
2. Visit: `http://localhost:8000/operator/dashboard`
3. Check sidebar shows operator menu
4. Click each menu item to verify links work

---

## 📁 File Locations

### Controllers
- `app/Http/Controllers/Operator/DashboardController.php`
- `app/Http/Controllers/Operator/AttendanceController.php`
- `app/Http/Controllers/Operator/PermissionApprovalController.php`
- `app/Http/Controllers/Operator/MonitoringController.php`
- `app/Http/Controllers/Operator/ReportingController.php`

### Views
- `resources/views/operator/dashboard.blade.php`
- `resources/views/operator/attendance/{index,create,edit}.blade.php`
- `resources/views/operator/permissions/{index,show}.blade.php`
- `resources/views/operator/monitoring/index.blade.php`
- `resources/views/operator/reports/{daily,weekly,monthly}.blade.php`

### Routes
- `routes/web.php` (Operator route group at end of file)

### Layout
- `resources/views/layouts/user.blade.php` (Updated with operator menu)

---

## 🎨 Design System

**Colors Used:**
- Sky/Blue (#0ea5e9) - Primary actions
- Green (#10b981) - Success/Present
- Orange (#f59e0b) - Warning/Late
- Yellow (#eab308) - Info/Permitted
- Red (#ef4444) - Danger/Absent

**Icons:** Lucide icons (from CDN)
**Styling:** Tailwind CSS (responsive)
**Charts:** Chart.js for monthly trends
**Interactivity:** Alpine.js for menu toggles

---

## 🔧 Common Tasks

### Add new operator
```bash
User::create([
    'name' => 'Name',
    'email' => 'email@domain.com',
    'password' => bcrypt('password'),
    'role' => 'Operator'  # ⚠️ Exact case!
]);
```

### Approve permission programmatically
```bash
$permission = Permission::find($id);
$permission->update([
    'status' => 'approved',
    'approved_by' => auth()->id(),
    'approval_notes' => 'Approved by system'
]);
```

### Mark attendance
```bash
Attendance::create([
    'user_id' => $userId,
    'schedule_id' => $scheduleId,
    'check_in_time' => now(),
    'is_late' => false,
    'status' => 'hadir'
]);
```

### Run reports for specific date
```bash
# In ReportingController
$date = '2024-01-30';
$schedules = Schedules::whereDate('schedule_date', $date)->get();
```

---

## ⚠️ Important Notes

1. **Role is case-sensitive**: Use `'Operator'` not `'operator'`
2. **Timezone**: Ensure Laravel timezone matches your server
3. **Shift categories**: Use exactly: Pagi, Siang, Malam (Indonesian)
4. **Status values**: hadir, telat, izin, alpha
5. **Chart**: Requires Chart.js library (auto-loaded via CDN)

---

## 📞 Support

### If operators can't see the menu
- Check `role = 'Operator'` (capital O)
- Check middleware in routes/web.php
- Clear route cache: `php artisan route:cache --clear`

### If pages show 403 error
- User is not Operator role
- Middleware not configured
- Check CheckRole middleware file exists

### If data doesn't show
- Check database has correct columns
- Verify relationships in models
- Check dates are in correct format

---

## 🚀 Next Steps

1. ✅ Create test operator account
2. ✅ Verify all routes work
3. ✅ Test each feature with real data
4. ✅ Train operators on system
5. ⏳ Optional: Add PDF export for reports
6. ⏳ Optional: Add email notifications

---

**Ready to use!** 🎉

For detailed implementation info, see: `OPERATOR_MODULE_README.md`  
For verification checklist, see: `OPERATOR_VERIFICATION.md`
