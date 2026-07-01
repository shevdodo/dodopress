<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->paginate(15);
        
        $title = 'Audit Logs';
        return view('dashboard.audit_logs.index', compact('logs', 'title'));
    }
}
