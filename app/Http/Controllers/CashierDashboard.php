<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashierDashboard extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayTransactionCount = Transaction::whereDate('transaction_date', $today)->count();

        $totalRevenue = Transaction::with('details')->get()->flatMap->details
            ->sum(fn ($item) => $item->quantity * $item->price);

        $weeklyIncome = Transaction::whereBetween('transaction_date', [
            Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
        ])->with('details')->get()->flatMap->details
            ->sum(fn ($item) => $item->quantity * $item->price);

        $productCount = Transaction::count();
        $newestProducts = Product::latest()->take(5)->get();

        // Transaksi per hari minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $dailyTransactionCounts = collect(range(0, 6))->mapWithKeys(function ($dayOffset) use ($startOfWeek) {
            $date = $startOfWeek->copy()->addDays($dayOffset);
            $count = Transaction::whereDate('transaction_date', $date)->count();
            return [$date->format('l') => $count];
        });

        return view('cashier.dashboard', [
            'weeklyIncome' => $weeklyIncome,
            'productCount' => $productCount,
            'newestProducts' => $newestProducts,
            'todayTransactionCount' => $todayTransactionCount,
            'totalRevenue' => $totalRevenue,
            'dailyTransactionCounts' => $dailyTransactionCounts,
            'title' => 'Cashier Dashboard'
        ]);
    }
}
