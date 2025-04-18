@extends('layouts.app')

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Home</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin_product') }}">Product Management</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- Alert Message Section -->
            @if (session()->has('success') || session()->has('successEdit') || session()->has('successDelete'))
                <div class="row">
                    <div class="col-12">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="ti ti-check me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session()->has('successEdit'))
                            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="ti ti-edit me-2"></i>
                                <div>{{ session('successEdit') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session()->has('successDelete'))
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="ti ti-trash me-2"></i>
                                <div>{{ session('successDelete') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            <!-- End Alert Message Section -->

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Product List</h5>
                        <a href="{{ route('admin_product_create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add Product
                        </a>
                    </div>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <form method="GET" action="{{ route('admin_product') }}" class="mb-3">
                                <div class="row align-items-end gy-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Search</label>
                                        <input type="text" name="search" value="{{ $request->search }}"
                                            class="form-control" placeholder="Search by product name...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $request->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="1" {{ $request->status === '1' ? 'selected' : '' }}>
                                                Available</option>
                                            <option value="0" {{ $request->status === '0' ? 'selected' : '' }}>Out of
                                                Stock</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Product Code</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($products->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-warning" role="alert">
                                                        No products found based on your search or filters.
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>
                                                        {{ Str::limit($product->name, 50, ' ...') }}
                                                        @if (session('newest_id') == $product->id)
                                                            <span class="badge bg-primary ms-1">Newest</span>
                                                        @elseif (session('updated_id') == $product->id)
                                                            <span class="badge bg-warning text-dark ms-1">Updated</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->category->name }}</td>
                                                    <td><b>{{ $product->code }}</b></td>
                                                    <td>{{ $product->stock }}</td>
                                                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($product->status == 1)
                                                            <span class="badge bg-success">Available</span>
                                                        @else
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="/product-management/{{ $product->id }}/"
                                                            class="btn btn-info m-1">
                                                            Detail <i class="ti ti-arrow-right"></i>
                                                        </a>
                                                        <a href="/product-management/{{ $product->id }}/edit"
                                                            class="btn btn-warning m-1">
                                                            Edit <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="/product-management-delete/{{ $product->id }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-danger m-1" type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                                Delete <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-center">
                                                {{-- Previous Page Link --}}
                                                @if ($products->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Previous</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $products->previousPageUrl() }}"
                                                            aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Page Number Links --}}
                                                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($products->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $products->nextPageUrl() }}"
                                                            aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Next</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
