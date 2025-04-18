@extends('layouts.app')

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Breadcrumb -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Transaction Management</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin_transaction') }}">Transaction List</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="row">
                <div class="col-12">
                    <div class="card tbl-card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin_transaction') }}" class="mb-3">
                                <div class="row align-items-end gy-2">
                                    <div class="col-md-5">
                                        <label class="form-label">Search by Cashier</label>
                                        <input type="text" name="search" value="{{ $request->search }}"
                                            class="form-control" placeholder="Enter cashier name...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Transaction Date</label>
                                        <input type="date" name="date" value="{{ $request->date }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-3 d-flex">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Tabel Transaksi -->
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Cashier</th>
                                            <th>Products</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transactions as $transaction)
                                            <tr>
                                                <td><strong>#{{ $transaction->id }}</strong></td>
                                                <td>{{ $transaction->transaction_date->format('Y-m-d H:i') }}</td>
                                                <td>{{ $transaction->user->username }}</td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($transaction->details as $detail)
                                                            <li>{{ Str::limit($detail->product->name, 80, ' ...') }}
                                                                ({{ $detail->quantity }} pcs)
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    Rp{{ number_format($transaction->details->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('transaction.show', $transaction->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="ti ti-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-warning mb-0">No transactions found.</div>
                                                </td>
                                            </tr>
                                        @endforelse
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
                                                @if ($transactions->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Previous</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $transactions->previousPageUrl() }}"
                                                            aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Page Number Links --}}
                                                @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $page == $transactions->currentPage() ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($transactions->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $transactions->nextPageUrl() }}"
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
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection
