<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use Illuminate\Http\Request;

class PermissionApprovalController extends Controller
{
    // Lihat semua permission requests
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        
        $permissions = Permissions::with(['user', 'schedule', 'schedule.shift'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('operator.permissions.index', [
            'permissions' => $permissions,
            'status' => $status
        ]);
    }

    // Detail permission request
    public function show(Permissions $permission)
    {
        $permission->load(['user', 'schedule', 'schedule.shift']);
        
        return view('operator.permissions.show', [
            'permission' => $permission
        ]);
    }

    // Approve permission
    public function approve(Request $request, Permissions $permission)
    {
        $permission->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approval_notes' => $request->input('notes')
        ]);
        
        return back()->with('success', 'Pengajuan disetujui');
    }

    // Reject permission
    public function reject(Request $request, Permissions $permission)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:5'
        ]);
        
        $permission->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejection_reason' => $validated['reason']
        ]);
        
        return back()->with('success', 'Pengajuan ditolak');
    }
}
