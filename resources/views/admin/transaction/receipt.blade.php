@extends('layouts.app')
@php
    use Carbon\Carbon;
@endphp

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <h5>Transaction Receipt</h5>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}</p>

            <table class="table">
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
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>Rp{{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
            <a href="{{ route('admin_transaction') }}" class="btn btn-secondary mb-3">
                <i class="ti ti-arrow-left"></i> Back to Transaction List
            </a>
            <a href="{{ route('admin_transaction.print', $transaction->id) }}" target="_blank"
                class="btn btn-success mb-3 ms-2">
                <i class="ti ti-printer"></i> Print PDF
            </a>

        </div>
    </div>
@endsection
