@extends('layouts.app')
@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Product Dashboard</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item">Product Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ PDF Button Moved Here ] -->
            <div class="row mb-4">
                <div class="col-md-12 text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Download Product Report (PDF)
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="{{ route('product.report.pdf', ['range' => 'today']) }}">Today</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.report.pdf', ['range' => 'week']) }}">This
                                    Week</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.report.pdf', ['range' => 'month']) }}">This
                                    Month</a></li>
                            <li><a class="dropdown-item" href="{{ route('product.report.pdf', ['range' => 'year']) }}">This
                                    Year</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('product.report.pdf', ['range' => 'all']) }}">All
                                    Time</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Statistik -->
            <div class="row">

                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Products</h6>
                            <h4 class="mb-3">{{ $productCount }} </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Today's New Products</h6>
                            <h4 class="mb-3">{{ $todayProductCount ?? 0 }} </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">This Week's New Products</h6>
                            <h4 class="mb-3">{{ $weeklyProductCount ?? 0 }} </h4>
                        </div>
                    </div>
                </div>

                <!-- Grafik Produk Mingguan -->
                <div class="col-md-12 col-xl-10">
                    <h5 class="mb-3">Weekly Product Overview</h5>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Products Added This Week</h6>
                            <h3 class="mb-3">{{ $weeklyProductCount }}</h3>
                            <div style="height: 360px;">
                                <canvas id="product-overview-chart"></canvas>
                            </div>

                            <script>
                                window.onload = function() {
                                    const ctx = document.getElementById('product-overview-chart').getContext('2d');
                                    const chart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: {!! json_encode($dailyProductCounts->keys()) !!},
                                            datasets: [{
                                                label: 'Products Added',
                                                data: {!! json_encode($dailyProductCounts->values()) !!},
                                                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                                                borderRadius: 10,
                                                barPercentage: 0.6,
                                                categoryPercentage: 0.6
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(context) {
                                                            return context.parsed.y + ' products';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>

                <!-- Produk Terbaru -->
                <div class="col-md-12 col-xl-8">
                    <h5 class="mb-3">Latest Products</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($newestProducts as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? '-' }}</td>
                                                <td><span class="badge bg-success">New</span></td>
                                                <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
