<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'users' => 123,
            'vendors' => 44,
            'orders_today' => 12,
            'revenue' => 'SAR 4,320',
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
