<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
