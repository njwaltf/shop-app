<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }

        // Filter kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10); // Pagination setiap 10 produk

        return view('admin.product.index', [
            'products' => $products,
            'categories' => Category::all(),
            'title' => 'Product Management',
            'request' => $request
        ]);
    }

    public function reportPdf(Request $request)
    {
        $range = $request->input('range');
        $query = Product::query();

        switch ($range) {
            case 'today':
                $query->whereDate('created_at', now());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'all':
            default:
                // tidak ada filter
                break;
        }

        $products = $query->latest()->get();

        $pdf = Pdf::loadView('admin.product.report-pdf', [
            'products' => $products,
            'range' => ucfirst($range),
        ]);

        return $pdf->download("product_report_{$range}.pdf");
    }
    public function report()
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
        return view('admin.product.dashboard', [
            'todayProductCount' => $todayProductCount,
            'productCount' => $productCount,
            'newestProducts' => $newestProducts,
            'dailyProductCounts' => $dailyProductCounts,
            'weeklyProductCount' => $weeklyProductCount,
            'title' => 'Product Reports'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create', [
            'title' => 'Add Product',
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:500'],
            'status' => ['required'],
            'stock' => ['nullable'],
            'price' => ['required'],
            'category_id' => ['required'],
            'image' => ['nullable']
        ]);
        $validatedData['price'] = preg_replace('/[^0-9]/', '', $validatedData['price']);

        $validatedData['code'] = strtoupper(Str::random(8));
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('product-images');
        }
        $product = Product::create($validatedData);

        session()->flash('newest_id', $product->id);

        return redirect()->route('admin_product')->with('success', 'Product added!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product, Request $request)
    {
        return view('admin.product.show', [
            'title' => 'Product Detail',
            'product' => Product::where('id', $request->id)->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', [
            'title' => 'Update Product',
            'categories' => Category::all(),
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'status' => ['required'],
            'stock' => ['nullable', 'integer'],
            'price' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048']
        ]);

        $validatedData['price'] = preg_replace('/[^0-9]/', '', $validatedData['price']);

        if ($request->file('image')) {
            if ($request->image) {
                Storage::delete($request->image);
            }
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        Product::where('id', $request->id)->update($validatedData);

        session()->flash('updated_id', $request->id);

        return redirect()->route('admin_product')->with('successEdit', 'Product updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Product::destroy($request->id);
        return redirect('/product-management')->with('successDelete', 'Product Deleted!');
    }
}
