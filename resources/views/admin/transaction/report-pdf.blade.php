<h3>Transaction Report - {{ $range }}</h3>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Date</th>
            <th>Cashier</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $trx)
            @foreach ($trx->details as $detail)
                <tr>
                    <td>{{ $trx->transaction_date->format('d-m-Y') }}</td>
                    <td>{{ $trx->user->username }}</td>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
