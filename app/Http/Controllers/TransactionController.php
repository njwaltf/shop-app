<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product']);

        // Search berdasarkan nama kasir
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%'.$request->search.'%');
            });
        }

        // Filter berdasarkan tanggal transaksi
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('transaction_date', $request->date);
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transaction.index', [
            'transactions' => $transactions,
            'request' => $request,
            'title' => 'Manage Transaction'
        ]);
    }

    public function print(Transaction $transaction)
    {
        $transaction->load('details.product');
        $total = $transaction->details->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });

        $pdf = Pdf::loadView('admin.transaction.receipt-pdf', [
            'transaction' => $transaction,
            'total' => $total
        ]);

        return $pdf->stream('receipt_'.$transaction->id.'.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->where('status', 1)
            ->get();

        return response()->json($products);
    }

    public function create()
    {
        // Ambil semua produk aktif
        $products = Product::where('status', 1)->get();
        // dd($products);
        return view('admin.transaction.create', [
            'products' => $products,
            'title' => 'Add Transaction'
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
        ]);

        $details = [];
        $total = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $qty = $productData['quantity'];
            $price = $product->price;

            // Ensure there is enough stock
            if ($product->stock < $qty) {
                return redirect()->back()->with('error', "Not enough stock for product: {$product->name}");
            }

            // Reduce the stock
            $product->stock -= $qty;
            $product->save(); // Save the updated stock

            $total += $qty * $price;

            $details[] = [
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
            ];
        }

        // Insert transaction details
        TransactionDetail::insert($details);

        return view('admin.transaction.receipt', [
            'transaction' => $transaction->load('details.product'),
            'total' => $total,
            'title' => 'Transaction Receipt'
        ]);
    }

    public function reportPdf(Request $request)
    {
        $range = $request->input('range');
        $query = Transaction::with('details.product');

        switch ($range) {
            case 'today':
                $query->whereDate('transaction_date', now());
                break;
            case 'week':
                $query->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year);
                break;
            case 'year':
                $query->whereYear('transaction_date', now()->year);
                break;
            case 'all':
            default:
                // tidak ada filter
                break;
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('admin.transaction.report-pdf', [
            'transactions' => $transactions,
            'range' => ucfirst($range),
        ]);

        return $pdf->download("transaction_report_{$range}.pdf");
    }

    public function report()
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

        return view('admin.transaction.report', [
            'weeklyIncome' => $weeklyIncome,
            'productCount' => $productCount,
            'newestProducts' => $newestProducts,
            'todayTransactionCount' => $todayTransactionCount,
            'totalRevenue' => $totalRevenue,
            'dailyTransactionCounts' => $dailyTransactionCounts,
            'title' => 'Transaction Reports'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('admin.transaction.show', [
            'transaction' => Transaction::with(['details.product'])->findOrFail($id),
            'title' => "Transaction Detail"
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
