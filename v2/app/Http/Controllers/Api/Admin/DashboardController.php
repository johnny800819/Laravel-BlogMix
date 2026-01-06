<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_revenues' => Order::whereIn('status', ['paid', 'shipped'])->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_articles' => Article::count(),
            'system_status' => [
                'server_name' => config('app.name'),
                'php_version' => PHP_VERSION,
                'ip_address' => request()->ip(),
                'server_time' => now()->toDateTimeString(),
            ]
        ]);
    }
}
