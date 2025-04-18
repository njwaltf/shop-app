@extends('layouts.app')

@section('main')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Add Transaction</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Transactions</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('transaction.store') }}" method="POST">
                        @csrf

                        <div class="mb-3 position-relative">
                            <label for="product_search" class="form-label">Search Product</label>
                            <input type="text" id="product_search" class="form-control"
                                placeholder="Type product name or code..." autocomplete="off">
                            <div id="product_results" class="border rounded bg-white w-100 position-absolute z-3"></div>
                        </div>

                        <div id="selected-products" class="mt-4"></div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin_transaction') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-shopping-cart"></i> Complete Transaction
                            </button>
                        </div>
                    </form>
                    <!-- Error Message -->
                    <div id="error-message" class="text-danger mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #product_results {
            max-height: 250px;
            overflow-y: auto;
            display: none;
        }

        .product-preview {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
        }

        .product-preview img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .product-preview:hover {
            background-color: #f8f9fa;
        }

        .product-tag {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .product-tag span {
            font-weight: 500;
        }

        .product-tag input[type="number"] {
            width: 80px;
            margin-left: 15px;
        }

        .product-tag i {
            color: red;
            cursor: pointer;
            margin-left: 15px;
        }
    </style>

    <script>
        let allProducts = @json($products);
        let selected = [];

        const searchInput = document.getElementById('product_search');
        const productResults = document.getElementById('product_results');
        const selectedProductsDiv = document.getElementById('selected-products');
        const errorMessage = document.getElementById('error-message');

        // Search functionality
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim();
            productResults.innerHTML = '';
            if (keyword.length === 0) {
                productResults.style.display = 'none';
                return;
            }

            const filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(keyword) || p.code.toLowerCase().includes(keyword)
            );

            if (filtered.length === 0) {
                productResults.style.display = 'none';
                return;
            }

            filtered.forEach(product => {
                const div = document.createElement('div');
                div.className = 'product-preview';
                div.innerHTML = `
                    <img src="/storage/${product.image}" alt="${product.name}">
                    <div>
                        <strong>${product.name}</strong><br>
                        <small>Kode: ${product.code} | Stok: ${product.stock} | Rp${product.price.toLocaleString()}</small>
                    </div>
                `;
                div.onclick = () => {
                    if (!selected.find(p => p.id === product.id)) {
                        selected.push({
                            ...product,
                            quantity: 1
                        });
                        renderSelectedProducts();
                    }
                    searchInput.value = '';
                    productResults.innerHTML = '';
                    productResults.style.display = 'none';
                };
                productResults.appendChild(div);
            });

            productResults.style.display = 'block';
        });

        // Render selected products
        function renderSelectedProducts() {
            selectedProductsDiv.innerHTML = '';
            let totalPrice = 0;
            selected.forEach(product => {
                const container = document.createElement('div');
                container.classList.add('product-tag');
                container.innerHTML = `
                    <div>
                        <span>${product.name}</span> - Rp${product.price.toLocaleString()}
                        <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                        <input type="number" name="products[${product.id}][quantity]" class="form-control d-inline" placeholder="Qty" value="${product.quantity}" min="1" max="${product.stock}" onchange="updateQuantity(${product.id}, this.value)">
                    </div>
                    <i onclick="removeProduct(${product.id})" class="ti ti-x"></i>
                `;
                selectedProductsDiv.appendChild(container);
                totalPrice += product.price * product.quantity;
            });

            // Show total price preview
            const totalPriceElement = document.createElement('div');
            totalPriceElement.innerHTML = `Total: Rp${totalPrice.toLocaleString()}`;
            selectedProductsDiv.appendChild(totalPriceElement);

            // Hide error message
            errorMessage.style.display = 'none';
        }

        // Update quantity and check for stock overflow
        function updateQuantity(productId, quantity) {
            const product = selected.find(p => p.id === productId);
            const stock = product.stock;

            // Prevent quantity from exceeding stock
            if (quantity > stock) {
                errorMessage.innerText = `The quantity of ${product.name} cannot exceed the available stock (${stock})`;
                errorMessage.style.display = 'block';
                return;
            }

            // Update the quantity in the selected array
            product.quantity = quantity;
            renderSelectedProducts();
        }

        // Remove product from the list
        function removeProduct(id) {
            selected = selected.filter(p => p.id !== id);
            renderSelectedProducts();
        }
    </script>
@endsection
