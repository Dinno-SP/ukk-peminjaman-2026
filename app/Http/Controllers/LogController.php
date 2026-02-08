<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // Ambil data log terbaru (diurutkan dari yang paling baru)
        $logs = ActivityLog::with('user')->latest()->get();
        return view('admin.logs.index', compact('logs'));
    }
}