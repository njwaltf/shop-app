@extends('layouts.app')

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            @foreach ($product as $item)
                <!-- [ breadcrumb ] start -->
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="page-header-title">
                                    <h5 class="m-b-10">Product Detail</h5>
                                </div>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin_product') }}">Product Management</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $item->name }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body d-flex flex-column flex-md-row gap-4 align-items-start">
                                {{-- Product Image --}}
                                <div style="min-width: 300px;">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                        class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-fill">
                                    <h4 class="mb-2">{{ $item->name }}</h4>
                                    {{-- <p><strong>Category:</strong> {{ $item->category->name }}</p> --}}
                                    <p><strong>Code:</strong> {{ $item->code }}</p>
                                    <p><strong>Price:</strong> Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                    <p><strong>Stock:</strong> {{ $item->stock }}</p>
                                    <p><strong>Status:</strong>
                                        @if ($item->status == 1)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </p>

                                    <div class="mt-4">
                                        <a href="{{ route('admin_product') }}" class="btn btn-secondary">
                                            <i class="ti ti-arrow-left"></i> Back to List
                                        </a>
                                        <a href="/product-management/{{ $item->id }}/edit" class="btn btn-warning">
                                            <i class="ti ti-edit"></i> Edit Product
                                        </a>
                                        <form action="/product-management-delete/{{ $item->id }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            {{-- @method('delete') --}}
                                            <button class="btn btn-danger" type="submit"
                                                onclick="return confirm('Are you sure want to delete this product?')">
                                                <i class="ti ti-trash"></i> Delete Product
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
