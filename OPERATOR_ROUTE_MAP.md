# 🗺️ Operator Module - Complete Route Map

## All Operator Routes

### Dashboard Routes
```
GET  /operator/dashboard
     Name: operator.dashboard
     Controller: Operator\DashboardController@index
     Purpose: Main operator dashboard with analytics
     Parameters: ?selected_month=1&selected_year=2024
```

---

## Attendance Management Routes

### View Attendance List
```
GET  /operator/attendance
     Name: operator.attendance.index
     Controller: Operator\AttendanceController@index
     Purpose: View daily attendance records
     Parameters: 
       ?date=2024-01-30
       &shift_id=1
       &search=John Doe
```

### Manual Input Form
```
GET  /operator/attendance/create
     Name: operator.attendance.create
     Controller: Operator\AttendanceController@create
     Purpose: Show form to input manual attendance
     Fields Needed: schedule_id, status, check_in_time, check_out_time, notes
```

### Save New Attendance
```
POST /operator/attendance
     Name: operator.attendance.store
     Controller: Operator\AttendanceController@store
     Purpose: Save manual attendance record
     Redirect: /operator/attendance
```

### Edit Attendance Form
```
GET  /operator/attendance/{attendance}/edit
     Name: operator.attendance.edit
     Controller: Operator\AttendanceController@edit
     Purpose: Show edit form for existing attendance
     URL Example: /operator/attendance/5/edit
```

### Update Attendance
```
PUT  /operator/attendance/{attendance}
     Name: operator.attendance.update
     Controller: Operator\AttendanceController@update
     Purpose: Save attendance changes
     URL Example: /operator/attendance/5
     Method Spoofing: @method('PUT') in form
```

### Delete Attendance
```
DELETE /operator/attendance/{attendance}
       Name: operator.attendance.destroy
       Controller: Operator\AttendanceController@destroy
       Purpose: Delete attendance record
       URL Example: /operator/attendance/5
       Method Spoofing: @method('DELETE') in form
       Confirmation: Requires confirmation dialog
```

### Quick Action - Mark Present
```
POST /operator/attendance/mark-present
     Name: operator.attendance.mark-present
     Controller: Operator\AttendanceController@markPresent
     Purpose: Quick button to mark employee as present
     Parameter: schedule_id (hidden in form)
     Creates: Attendance with status='hadir', is_late=false
```

### Quick Action - Mark Leave/Sick
```
POST /operator/attendance/mark-leave
     Name: operator.attendance.mark-leave
     Controller: Operator\AttendanceController@markLeave
     Purpose: Quick button to mark employee as on leave
     Parameter: schedule_id (hidden in form)
     Creates: Attendance with status='izin'
```

### Quick Action - Mark Absent
```
POST /operator/attendance/mark-absent
     Name: operator.attendance.mark-absent
     Controller: Operator\AttendanceController@markAbsent
     Purpose: Quick button to mark employee as absent
     Parameter: schedule_id (hidden in form)
     Creates: Attendance with status='alpha'
```

---

## Permission Approval Routes

### View Permission Requests
```
GET  /operator/permissions
     Name: operator.permissions.index
     Controller: Operator\PermissionApprovalController@index
     Purpose: List all permission requests with status filter
     Parameters:
       ?status=pending
       &status=approved
       &status=rejected
       &status=all
     Display: Tab-based filtering interface
```

### View Permission Details
```
GET  /operator/permissions/{permission}
     Name: operator.permissions.show
     Controller: Operator\PermissionApprovalController@show
     Purpose: Show detail of single permission request
     URL Example: /operator/permissions/3
     Display: Full details with approve/reject forms
```

### Approve Permission
```
POST /operator/permissions/{permission}/approve
     Name: operator.permissions.approve
     Controller: Operator\PermissionApprovalController@approve
     Purpose: Approve permission request
     URL Example: /operator/permissions/3/approve
     Parameters:
       approval_notes (optional text)
     Updates: status='approved', approved_by=current_user_id
     Redirect: /operator/permissions (with success message)
```

### Reject Permission
```
POST /operator/permissions/{permission}/reject
     Name: operator.permissions.reject
     Controller: Operator\PermissionApprovalController@reject
     Purpose: Reject permission request
     URL Example: /operator/permissions/3/reject
     Parameters:
       rejection_reason (required text)
     Updates: status='rejected', rejection_reason=reason
     Redirect: /operator/permissions (with success message)
```

---

## Real-time Monitoring Routes

### Monitor Check-in Status
```
GET  /operator/monitoring
     Name: operator.monitoring.index
     Controller: Operator\MonitoringController@index
     Purpose: Real-time display of who checked in today
     Parameters:
       ?date=2024-01-30
       &shift_id=1
     Display: Two-column layout with statistics
```

---

## Report Generation Routes

### Daily Attendance Report
```
GET  /operator/reports/daily
     Name: operator.reports.daily
     Controller: Operator\ReportingController@daily
     Purpose: Single-day attendance summary
     Parameters:
       ?date=2024-01-30 (defaults to today)
     Display: Table with daily attendances
```

### Weekly Attendance Report
```
GET  /operator/reports/weekly
     Name: operator.reports.weekly
     Controller: Operator\ReportingController@weekly
     Purpose: Weekly attendance summary by employee
     Parameters:
       ?start_date=2024-01-29
       &end_date=2024-02-04
     Display: Aggregated table with percentages
```

### Monthly Attendance Report
```
GET  /operator/reports/monthly
     Name: operator.reports.monthly
     Controller: Operator\ReportingController@monthly
     Purpose: Monthly attendance summary by employee
     Parameters:
       ?month=1
       &year=2024
     Display: Aggregated table with all metrics
```

---

## 📋 Complete Route List

### Registration in routes/web.php
```php
Route::middleware(['auth', 'web', 'CheckRole:Operator'])->group(function () {
    // Dashboard
    Route::get('/operator/dashboard', [DashboardController::class, 'index'])->name('operator.dashboard');
    
    // Attendance Management
    Route::resource('operator/attendance', AttendanceController::class)->names('operator.attendance');
    Route::post('operator/attendance/mark-present', [AttendanceController::class, 'markPresent'])->name('operator.attendance.mark-present');
    Route::post('operator/attendance/mark-leave', [AttendanceController::class, 'markLeave'])->name('operator.attendance.mark-leave');
    Route::post('operator/attendance/mark-absent', [AttendanceController::class, 'markAbsent'])->name('operator.attendance.mark-absent');
    
    // Permission Approval
    Route::resource('operator/permissions', PermissionApprovalController::class)->only(['index', 'show'])->names('operator.permissions');
    Route::post('operator/permissions/{permission}/approve', [PermissionApprovalController::class, 'approve'])->name('operator.permissions.approve');
    Route::post('operator/permissions/{permission}/reject', [PermissionApprovalController::class, 'reject'])->name('operator.permissions.reject');
    
    // Monitoring
    Route::get('/operator/monitoring', [MonitoringController::class, 'index'])->name('operator.monitoring.index');
    
    // Reports
    Route::get('/operator/reports/daily', [ReportingController::class, 'daily'])->name('operator.reports.daily');
    Route::get('/operator/reports/weekly', [ReportingController::class, 'weekly'])->name('operator.reports.weekly');
    Route::get('/operator/reports/monthly', [ReportingController::class, 'monthly'])->name('operator.reports.monthly');
});
```

---

## 🔐 Access Control

All routes require:
1. ✅ User must be authenticated (`auth` middleware)
2. ✅ User must have role = 'Operator' (`CheckRole:Operator` middleware)

Example error messages:
- **Not authenticated**: Redirect to login
- **Wrong role**: 403 Forbidden

---

## 💾 Form Submission References

### Manual Attendance Input Form
```
POST /operator/attendance
<input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
<select name="status">
  <option>hadir</option>
  <option>telat</option>
  <option>izin</option>
  <option>alpha</option>
</select>
<input type="datetime-local" name="check_in_time">
<input type="datetime-local" name="check_out_time">
<textarea name="notes"></textarea>
@csrf
<button type="submit">Simpan</button>
```

### Edit Attendance Form
```
PUT /operator/attendance/{attendance}
<input type="datetime-local" name="check_in_time" value="{{ $attendance->check_in_time }}">
<input type="datetime-local" name="check_out_time" value="{{ $attendance->check_out_time }}">
<select name="status" value="{{ $attendance->status }}">...</select>
<input type="checkbox" name="is_late" @if($attendance->is_late) checked @endif>
@csrf
@method('PUT')
<button type="submit">Update</button>
```

### Permission Approval Form
```
POST /operator/permissions/{permission}/approve
<textarea name="approval_notes" placeholder="Optional notes..."></textarea>
@csrf
<button type="submit">Setujui</button>
```

### Permission Rejection Form
```
POST /operator/permissions/{permission}/reject
<textarea name="rejection_reason" placeholder="Alasan penolakan..." required></textarea>
@csrf
<button type="submit">Tolak</button>
```

---

## 🔄 Navigation Using Route Names

Instead of hardcoding URLs, use Laravel route helper:

```php
// Dashboard
{{ route('operator.dashboard') }}
// Result: /operator/dashboard

// Attendance List
{{ route('operator.attendance.index') }}
// Result: /operator/attendance

// Create Attendance
{{ route('operator.attendance.create') }}
// Result: /operator/attendance/create

// Edit Attendance
{{ route('operator.attendance.edit', $attendance) }}
// Result: /operator/attendance/5/edit

// Permissions List
{{ route('operator.permissions.index') }}
// Result: /operator/permissions

// Permission Detail
{{ route('operator.permissions.show', $permission) }}
// Result: /operator/permissions/3

// Approve Permission
{{ route('operator.permissions.approve', $permission) }}
// Result: /operator/permissions/3/approve

// Monitoring
{{ route('operator.monitoring.index') }}
// Result: /operator/monitoring

// Reports
{{ route('operator.reports.daily') }}
{{ route('operator.reports.weekly') }}
{{ route('operator.reports.monthly') }}
```

---

## 📱 Quick Test URLs

### Testing in Browser

**Start with Dashboard:**
```
http://localhost:8000/operator/dashboard
```

**Then test each feature:**
```
http://localhost:8000/operator/attendance
http://localhost:8000/operator/attendance/create
http://localhost:8000/operator/permissions
http://localhost:8000/operator/monitoring
http://localhost:8000/operator/reports/daily
http://localhost:8000/operator/reports/weekly
http://localhost:8000/operator/reports/monthly
```

---

## 🧪 Testing with Query Parameters

### Dashboard with filter
```
http://localhost:8000/operator/dashboard?selected_month=1&selected_year=2024
```

### Attendance with filters
```
http://localhost:8000/operator/attendance?date=2024-01-30&shift_id=1&search=John
```

### Reports with parameters
```
http://localhost:8000/operator/reports/daily?date=2024-01-30
http://localhost:8000/operator/reports/weekly?start_date=2024-01-29&end_date=2024-02-04
http://localhost:8000/operator/reports/monthly?month=1&year=2024
```

---

## ✅ Total Routes Count

| Category | Count |
|----------|-------|
| Dashboard | 1 |
| Attendance CRUD | 7 |
| Attendance Quick Actions | 3 |
| Permission Approval | 4 |
| Monitoring | 1 |
| Reports | 3 |
| **TOTAL** | **19 routes** |

---

## 🚀 Next Steps

1. Verify all routes: `php artisan route:list | Select-String "operator"`
2. Create test operator account
3. Test each route manually
4. Set up cron job for automated reports (optional)
5. Configure email notifications (optional)

---

**Created:** January 30, 2024  
**Version:** 1.0  
**Last Updated:** Complete operator module with all 19 routes fully documented
