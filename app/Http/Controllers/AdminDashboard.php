<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Jumlah produk yang ditambahkan hari ini
        $todayProductCount = Product::whereDate('created_at', $today)->count();

        // 2. Total produk
        $productCount = Product::count();

        // 3. Produk terbaru (5)
        $newestProducts = Product::latest()->take(5)->get();

        // 4. Statistik jumlah produk ditambahkan per hari (minggu ini)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $dailyProductCounts = collect(range(0, 6))->mapWithKeys(function ($dayOffset) use ($startOfWeek) {
            $date = $startOfWeek->copy()->addDays($dayOffset);
            $count = Product::whereDate('created_at', $date)->count();
            return [$date->format('l') => $count]; // Contoh: ['Monday' => 3]
        });

        // 5. Jumlah produk yang ditambahkan minggu ini
        $weeklyProductCount = Product::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        // 6. Return ke view
        return view('admin.dashboard', [
            'todayProductCount' => $todayProductCount,
            'productCount' => $productCount,
            'newestProducts' => $newestProducts,
            'dailyProductCounts' => $dailyProductCounts,
            'weeklyProductCount' => $weeklyProductCount,
            'title' => 'Admin Dashboard'
        ]);
    }
}
