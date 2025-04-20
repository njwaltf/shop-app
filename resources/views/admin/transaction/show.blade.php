@extends('layouts.app')

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Transaction Detail</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin_transaction') }}">Transaction</a></li>
                        <li class="breadcrumb-item active">#{{ $transaction->id }}</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5>Transaction ID: #{{ $transaction->id }}</h5>
                    <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y - H:i') }}</p>
                    <p><strong>Total:</strong> Rp{{ number_format($transaction->total, 0, ',', '.') }}</p>
                    <p><strong>Cashier by:</strong> {{ $transaction->user->username }}</p>

                    <hr>

                    <h6>Transaction Items:</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->name ?? 'Deleted Product' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>
                                    @php
                                        $total = $transaction->details->sum(function ($detail) {
                                            return $detail->quantity * $detail->price;
                                        });
                                    @endphp
                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>


                    <div class="mt-3">
                        <a href="{{ route('admin_transaction') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('admin_transaction.print', $transaction->id) }}" class="btn btn-primary">
                            <i class="ti ti-receipt"></i> View Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
