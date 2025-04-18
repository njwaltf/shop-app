<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            font-size: 20px;
        }

        .logo .text-primary {
            color: #0d6efd;
        }

        .logo .text-dark {
            color: #000;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <h2 class="m-0 fw-bold text-primary">Shop<span class="text-dark">App</span></h2>
        </div>
        <p>Jl. Teknologi No.1, KampusKita</p>
        <p>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i') }}</p>
        <div class="divider"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr></tr>
            @foreach ($transaction->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right total">Total</td>
                <td class="text-right total">Rp{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="divider"></div>

    <div class="footer">
        <p>Thank you for shopping with ShopApp</p>
        <p>This receipt serves as your official proof of purchase</p>
    </div>

</body>

</html>
