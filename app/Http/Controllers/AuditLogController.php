<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Optional filtering by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Optional filtering by action type
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Optional filtering by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();
        
        // Get all unique actions for the filter dropdown
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        
        // Get users who have generated logs
        $users = \App\Models\User::whereIn('id', AuditLog::select('user_id')->distinct())->orderBy('name')->get();

        return view('audit_logs.index', compact('logs', 'actions', 'users'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user', 'auditable');
        return view('audit_logs.show', compact('auditLog'));
    }
}
