<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.category.index', [
            'categories' => $categories,
            'title' => 'Category Management',
            'request' => $request
        ]);
    }

    public function create()
    {
        return view('admin.category.create', [
            'title' => 'Add Category'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100', 'unique:categories,name']
        ]);

        $category = Category::create($validatedData);
        session()->flash('newest_id', $category->id);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', [
            'title' => 'Category Edit',
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100', 'unique:categories,name,'.$category->id]
        ]);

        $category->update($validatedData);
        session()->flash('updated_id', $category->id);

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
