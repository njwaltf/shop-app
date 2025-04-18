<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierDashboard extends Controller
{
    public function index()
    {
        return view('cashier.dashboard', [
            'title' => 'Cashier Dashboard'
        ]);
    }
}
