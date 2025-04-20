<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                {{-- <a href="#" class="text-decoration-none"> --}}
                <h2 class="m-0 fw-bold text-primary">Shop<span class="text-dark">App</span></h2>
                {{-- </a> --}}
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @if (auth()->user()->role === 'cashier')
                    <li class="pc-item">
                        <a href="{{ route('dashboard.cashier') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                @endif
                {{-- admin only --}}
                @if (auth()->user()->role === 'admin')
                    <li class="pc-item">
                        <a href="@if (auth()->user()->role == 'admin') /dashboard-admin @else /dashboard-cashier @endif"
                            class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Manage Products</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-package"></i></span><span class="pc-mtext">Product List</span><span
                                class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin_product') }}">All Product</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin_product_create') }}">Add
                                    Product</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin_product_report') }}">Product
                                    Reports</a></li>
                        </ul>
                    <li class="pc-item pc-caption">
                        <label>Manage Product Category</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-box-multiple"></i></span><span class="pc-mtext">Category List
                                List</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="/category">All
                                    Category</a></li>
                            <li class="pc-item"><a class="pc-link" href="/category/create">Add
                                    Category</a></li>
                        </ul>
                    </li>
                @endif
                {{-- admin and cashier --}}
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'cashier')
                    <li class="pc-item pc-caption">
                        <label>Manage Transactions</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-cash"></i></span><span
                                class="pc-mtext">Transaction
                                List</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin_transaction') }}">All
                                    Transaction</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('transaction.create') }}">Add
                                    Transaction</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('transaction.report') }}">Transaction
                                    Reports</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
