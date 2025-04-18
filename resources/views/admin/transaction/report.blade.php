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
                                <h5 class="m-b-10">Dashboard</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard-admin">Main Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ PDF Button Moved Here ] -->
            <div class="row mb-4">
                <div class="col-md-12 text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Download Transaction Report (PDF)
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="{{ route('transaction.report.pdf', ['range' => 'today']) }}">Today</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('transaction.report.pdf', ['range' => 'week']) }}">This Week</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('transaction.report.pdf', ['range' => 'month']) }}">This Month</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('transaction.report.pdf', ['range' => 'year']) }}">This Year</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('transaction.report.pdf', ['range' => 'all']) }}">All Time</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ Statistics Cards ] start -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Products</h6>
                            <h4 class="mb-3">{{ $productCount }} <span
                                    class="badge bg-light-primary border border-primary"><i class="ti ti-trending-up"></i>
                                    59.3%</span></h4>
                            <p class="mb-0 text-muted text-sm">Increased by <span class="text-primary">35,000</span> this
                                year</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Today's Transactions</h6>
                            <h4 class="mb-3">{{ $todayTransactionCount ?? 'N/A' }} <span
                                    class="badge bg-light-warning border border-warning"><i class="ti ti-trending-down"></i>
                                    27.4%</span></h4>
                            <p class="mb-0 text-muted text-sm">Compared to previous day</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">Total Revenue</h6>
                            <h4 class="mb-3">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} <span
                                    class="badge bg-light-danger border border-danger"><i class="ti ti-trending-down"></i>
                                    27.4%</span></h4>
                            <p class="mb-0 text-muted text-sm">From the beginning of the year</p>
                        </div>
                    </div>
                </div>
                <!-- [ Statistics Cards ] end -->

                <!-- Weekly Income -->
                <div class="col-md-12 col-xl-10">
                    <h5 class="mb-3">Weekly Income</h5>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">This Week</h6>
                            <h3 class="mb-3">Rp {{ number_format($weeklyIncome ?? 0, 0, ',', '.') }}</h3>
                            <div style="height: 360px;">
                                <canvas id="income-overview-chart"></canvas>
                            </div>


                            <script>
                                window.onload = function() {
                                    const ctx = document.getElementById('income-overview-chart').getContext('2d');
                                    const incomeChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: {!! json_encode($dailyTransactionCounts->keys()) !!},
                                            datasets: [{
                                                label: 'Total Transactions',
                                                data: {!! json_encode($dailyTransactionCounts->values()) !!},
                                                backgroundColor: function(context) {
                                                    const bgColors = [
                                                        'rgba(13, 110, 253, 0.7)', // Bootstrap Primary
                                                        'rgba(25, 135, 84, 0.7)', // Success
                                                        'rgba(255, 193, 7, 0.7)', // Warning
                                                        'rgba(220, 53, 69, 0.7)', // Danger
                                                        'rgba(32, 201, 151, 0.7)', // Teal
                                                        'rgba(111, 66, 193, 0.7)', // Custom Purple
                                                        'rgba(255, 99, 132, 0.7)' // Pink
                                                    ];
                                                    return bgColors[context.dataIndex % bgColors.length];
                                                },
                                                borderColor: 'rgba(0, 0, 0, 0.1)',
                                                borderWidth: 1,
                                                borderRadius: 10,
                                                barPercentage: 0.6,
                                                categoryPercentage: 0.6
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            layout: {
                                                padding: {
                                                    top: 10,
                                                    bottom: 10
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    grid: {
                                                        display: false
                                                    },
                                                    ticks: {
                                                        color: '#6c757d', // Bootstrap secondary text
                                                        font: {
                                                            size: 12,
                                                            weight: '500'
                                                        }
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    grid: {
                                                        color: '#dee2e6', // Bootstrap border color
                                                        borderDash: [4, 4]
                                                    },
                                                    ticks: {
                                                        color: '#6c757d',
                                                        precision: 0,
                                                        font: {
                                                            size: 12
                                                        }
                                                    }
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    backgroundColor: '#0d6efd',
                                                    titleColor: '#fff',
                                                    bodyColor: '#fff',
                                                    borderColor: '#0a58ca',
                                                    borderWidth: 1,
                                                    cornerRadius: 8,
                                                    padding: 10,
                                                    callbacks: {
                                                        label: function(context) {
                                                            return context.parsed.y + ' transaction';
                                                        }
                                                    }
                                                },
                                                title: {
                                                    display: false
                                                }
                                            }
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <div style="height: 15px;"></div>
                <!-- Latest Products -->
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

            </div> <!-- end row -->
        </div>
    </div>
@endsection
