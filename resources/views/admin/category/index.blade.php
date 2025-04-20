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
                                <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category Management</a>
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
                        <h5 class="mb-0">Category List</h5>
                        <a href="/category/create" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add Category
                        </a>
                    </div>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <form method="GET" action="/category" class="mb-3">
                                <div class="row align-items-end gy-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Search</label>
                                        <input type="text" name="search" value="{{ $request->search }}"
                                            class="form-control" placeholder="Search by category name...">
                                    </div>
                                    <div class="col-md-2 d-flex">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-warning" role="alert">
                                                        No categories found based on your search.
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ Str::limit($category->name, 50, ' ...') }}
                                                        @if (session('newest_id') == $category->id)
                                                            <span class="badge bg-primary ms-1">Newest</span>
                                                        @elseif (session('updated_id') == $category->id)
                                                            <span class="badge bg-warning text-dark ms-1">Updated</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('category.edit', $category->id) }}"
                                                            class="btn btn-warning m-1">
                                                            Edit <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="{{ route('category.destroy', $category->id) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger m-1" type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this category?')">
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
                                                @if ($categories->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Previous</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $categories->previousPageUrl() }}"
                                                            aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Page Number Links --}}
                                                @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $page == $categories->currentPage() ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($categories->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $categories->nextPageUrl() }}"
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
