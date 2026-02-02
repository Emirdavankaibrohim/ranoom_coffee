<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranoom Coffee</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/CSS/booking.css') }}">
</head>

<body>
    <div class="container-fluid mt-4">

        <div class="row">

            <!-- LEFT SECTION: Product & Categories -->
            <div class="col-lg-8">

                <!-- Back Button -->
                <a href="{{ route('adminDashboard') }}" class="btn btn-primary d-inline-flex align-items-center mb-2">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back
                </a>

                <!-- Search & Order Code -->
                <div class="d-flex flex-wrap mb-3 align-items-center">
                    <div class="row w-100">

                        <!-- Search Form -->
                        <form action="{{ route('getProductsByCategory') }}" method="GET"
                            class="col-lg-4 mb-2 mb-lg-0">
                            @csrf
                            @if (isset($selectedCategoryId))
                                <input type="hidden" name="categoryId" value="{{ $selectedCategoryId }}">
                            @endif
                            <div class="input-group">
                                <input type="text" name="searchKey" value="{{ request('searchKey') }}"
                                    class="form-control" placeholder="Search products...">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Order Code & New Order Button -->
                        <div class="col-lg-8 d-flex justify-content-end align-items-center mt-2 mt-lg-0 ms-auto">
                            <div class="d-flex align-items-center mx-3">
                                <span class="mx-2"><strong id="orderCodeDisplay" style="color: white;">
    {{ $orderCode ?? 0 }}
</strong>
</span>
                                <button type="button" class="btn btn-primary" id="newOrderButton">Pesanan Baru</button>
                            </div>

                            <!-- Dropdown Kode Pesanan -->
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    Kode Pesanan
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" id="orderCodeDropdown">
                                    <!-- Order codes akan dimuat via JS -->
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Category List -->
                <div class="row mb-3">
                    @foreach ($categories as $category)
                        <div class="col-md-3 mb-2">
                            <form action="{{ route('getProductsByCategory') }}" method="GET">
                                @csrf
                                <input type="hidden" name="categoryId" value="{{ $category->id }}">
                                <button type="submit" class="category-btn w-100">
                                    <div class="card category-card text-center">
                                        <h6 class="card-title">{{ $category->name }}</h6>
                                    </div>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Product List -->
                @if ((isset($selectedCategoryId) || request('searchKey')) && $productbyCategory->isNotEmpty())
                    <div class="row">
                        @foreach ($productbyCategory as $item)
                            @php
                                $selectedSize = $existingCart->size ?? ($item->sizes[0]->size ?? 'M');
                            @endphp
                            <div class="col-md-3 mb-3">
                                <form action="{{ route('additems', $item->id) }}" method="POST"
    class="h-100 d-flex flex-column">
    @csrf

    <input type="hidden" name="orderCode" class="order-code"
        value="{{ $orderCode }}">

    <input type="hidden" name="product_id" value="{{ $item->id }}">

    <input type="hidden" name="qty" class="quantity-input"
        value="{{ $cartItemsCount[$item->id] ?? 1 }}">

    <input type="hidden" name="notes" id="noteInput_{{ $item->id }}">

    @if (count($item->sizes) === 1)
        <input type="hidden" name="size" value="{{ $item->sizes[0]->size }}">
    @endif

    <div class="card h-100 shadow-sm border-1 product-card">
                                        <div class="position-relative">
                                            <img src="{{ asset('productImages/' . $item->image) }}"
                                                class="card-img-top product-image" alt="{{ $item->name }}">
                                            <span
                                                class="badge product-badge position-absolute top-0 start-0 m-2">{{ $item->name }}</span>
                                        </div>

                                        <div class="card-body d-flex flex-column">
                                            <p class="mb-2 text-muted small">Price:
                                                <strong class="text-dark" id="price-{{ $item->id }}">
                                                    {{ number_format($item->sizes[0]->price ?? 0) }}
                                                </strong>
                                            </p>

                                            <div class="input-group input-group-sm m-2">
                                                <!-- Minus -->
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm btn-minus"
                                                    data-product-id="{{ $item->id }}">
                                                    <i class="fa-solid fa-circle-minus"></i>
                                                </button>

                                                <!-- Quantity Display -->
                                                <input type="text" class="form-control text-center qty"
                                                    name="quantity_display" value="{{ $cartItemsCount[$item->id] ?? 1 }}"
                                                    readonly style="max-width: 40px;">

                                                <!-- Plus -->
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm btn-plus"
                                                    data-product-id="{{ $item->id }}">
                                                    <i class="fa-solid fa-circle-plus"></i>
                                                </button>

                                                <!-- Size Dropdown -->
                                                @if (count($item->sizes) > 0)
                                                    <select name="size"
                                                        class="form-control form-control-sm text-center fw-bold ms-1 size-dropdown"
                                                        data-product-id="{{ $item->id }}"
                                                        style="max-width: 40px; border: 2px solid rgb(255, 166, 0); border-radius: 4px;"
                                                        {{ count($item->sizes) === 1 ? 'disabled' : '' }}>
                                                        @foreach ($item->sizes as $size)
                                                            <option value="{{ $size->size }}"
                                                                data-price="{{ $size->price }}"
                                                                {{ $size->size == $selectedSize ? 'selected' : '' }}>
                                                                {{ strtoupper(substr($size->size, 0, 1)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>

                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                                <button type="button" class="btn btn-outline-primary btn-sm ms-1"
                                                    data-bs-toggle="modal" data-bs-target="#noteModal"
                                                    data-product-id="{{ $item->id }}">
                                                    ✏️
                                                </button>
                                                <button type="submit" class="btn btn-success btn-sm mt-1">Tambahkan
                                                    Keranjang</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            <!-- RIGHT SECTION: Ticket & Payment -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">

                        <!-- Ticket Table -->
<div class="table-responsive">
    <table class="table table-hover align-middle text-nowrap small">
        <thead class="table-secondary">
            <tr>
                <th>Barang</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Ukuran</th>
                <th class="text-center">Diskon</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($cartItems) && $cartItems->isNotEmpty())
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ $item->cart_qty }}</td>
                        <td class="text-center">{{ number_format($item->price) }}</td>
                        <td class="text-center">{{ strtoupper(substr($item->size, 0, 1)) }}</td>
                        <td class="text-center">{{ intval($item->discount_percentage) }}%</td>
                        <td class="text-end">
                            {{ number_format($item->discountPrice * $item->cart_qty) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">Tidak ada barang di keranjang.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


                        <!-- Customer Details -->
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="customerNameInput" placeholder="Masukkan nama pelanggan">
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="customerPhoneInput" placeholder="Masukkan nomor telepon">
                        </div>

                        <!-- Order Type -->
                        <div class="mt-2">
                            <select id="orderType" name="orderType" class="form-select"
                                onchange="setOrderType(this.value)">
                                <option value="" disabled {{ !request('orderType') ? 'selected' : '' }}>Pilih
                                    Jenis Pesanan</option>
                                <option value="eat_in" {{ request('orderType') === 'eat_in' ? 'selected' : '' }}>Makan
                                    di Tempat</option>
                                <option value="take_away"
                                    {{ request('orderType') === 'take_away' ? 'selected' : '' }}>Bawa Pulang</option>
                                <option value="delivery" {{ request('orderType') === 'delivery' ? 'selected' : '' }}>
                                    Diantarkan</option>
                            </select>
                        </div>

                        <!-- Delivery Location -->
                        @if (request('orderType') === 'delivery')
                            <div class="mt-2">
                                <select id="deliveryLocation" name="deliveryLocation" class="form-select"
                                    onchange="setDeliveryLocation(this.value)">
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    @foreach (\App\Models\DeliveryFees::all() as $location)
                                        <option value="{{ $location->id }}"
                                            {{ request('deliveryLocation') == $location->id ? 'selected' : '' }}>
                                            {{ $location->township }} ({{ number_format($location->fees) }} IDR)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Total Summary -->
<div class="mt-3">
    <div class="d-flex justify-content-between">
        <span>Subtotal</span>
        <span>{{ number_format($subtotal ?? 0) }}</span>
    </div>

    <div class="d-flex justify-content-between">
        <span>Pajak</span>
        <span>{{ number_format($taxAmount ?? 0) }}</span>
    </div>

    @if (($deliveryFee ?? 0) > 0)
    <div class="d-flex justify-content-between">
        <span>Biaya Pengiriman</span>
        <span>{{ number_format($deliveryFee) }}</span>
    </div>
@endif


    <hr>

    <div class="d-flex justify-content-between fw-bold fs-5">
        <span>Total</span>
        <span>{{ number_format($total ?? 0) }}</span>
    </div>
</div>


                        <!-- Payment Method -->
                        <div class="mt-4">
                            <h5>Pilih Metode Pembayaran</h5>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="showPaymentSection('cash')">Cash</button>
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="showPaymentSection('mobile')">Mobile Payment</button>
                            </div>

                            <div id="paymentDetails" class="mt-3">
                                <!-- Cash -->
                                <div id="cashPaymentSection" style="display:none;">
                                    <label for="cashReceived">Uang Diterima</label>
                                    <input type="number" class="form-control" id="cashReceived"
                                        placeholder="Masukkan uang diterima" onchange="calculateChange()">
                                    <p class="mt-2">Kembalian: <span id="changeDue">0</span></p>
                                </div>

                                <!-- Mobile Payment -->
                                <div id="mobilePaymentSection" style="display:none; text-align:center;">
                                    <p>Pindai Kode QR atau selesaikan pembayaran menggunakan aplikasi seluler</p>
                                    <img src="{{ asset('adminProfile/QRcode.jpeg') }}"
                                        style="width:150px; height:150px;" class="img-thumbnail mb-2">
                                    <p class="text-muted" style="font-size:0.9rem;">Buka aplikasi pembayaran seluler
                                        Anda dan pindai kode untuk melanjutkan.</p>
                                </div>
                            </div>

                            <!-- Confirm Payment -->
                            <form action="{{ route('orderConfirm') }}" method="POST" id="paymentForm">
    @csrf
    <input type="hidden" name="orderCode" value="{{ $orderCode ?? '' }}">
    <input type="hidden" name="paymentMethod" id="selectedPaymentMethod">
    <input type="hidden" name="orderType" value="{{ request('orderType') }}">
    <input type="hidden" name="deliveryLocation" value="{{ request('deliveryLocation') }}">
    <input type="hidden" name="totalAmount" value="{{ $total ?? 0 }}">
    <input type="hidden" name="cashReceived" id="hiddenCashReceived">
    <input type="hidden" name="changeDue" id="hiddenChangeDue">
    <input type="hidden" name="customerName" id="hiddenCustomerName">
    <input type="hidden" name="customerPhone" id="hiddenCustomerPhone">

    <button type="submit"
    id="confirm-payment-btn"
    class="btn btn-primary mt-3 w-100">
    Konfirmasi Pembayaran
</button>

</form>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Note Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambahkan Instruksi Khusus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" id="noteTextarea" rows="3" placeholder="Contoh: tanpa susu"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveNoteBtn">Simpan Catatan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // ===== Dropdown Order Codes =====
        const dropdownMenuLink = document.getElementById('dropdownMenuLink');
        const orderCodeDropdown = document.getElementById('orderCodeDropdown');

        dropdownMenuLink?.addEventListener('click', () => {
            orderCodeDropdown.innerHTML = '';
            fetch("{{ route('getOrderCodes') }}")
                .then(res => res.json())
                .then(orderCodes => {
                    orderCodes.forEach(code => {
                        const li = document.createElement('li');
                        li.innerHTML =
                            `<a class="dropdown-item order-code-link" href="{{ route('getProductsByCategory') }}?orderCode=${code}">${code}</a>`;
                        orderCodeDropdown.appendChild(li);
                    });
                }).catch(console.error);
        });

        // ===== New Order Button =====
        document.getElementById('newOrderButton')?.addEventListener('click', () => {
            const orderCode = 'ORD-' + Date.now();
            fetch("{{ route('storeOrderCode') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        orderCode
                    })
                })
                .then(res => {
                     if(res.ok) {
                        document.getElementById('orderCodeDisplay').innerText = orderCode;
                        // Update Hidden Inputs
                        document.querySelectorAll('input[name="orderCode"]').forEach(el => el.value = orderCode);
                     }
                })
                .catch(console.error);
        });

        // ===== Quantity Buttons =====
        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const qtyDisplay = this.closest('.input-group').querySelector('.qty');
                const hiddenQty = this.closest('form').querySelector('.quantity-input');
                let qty = parseInt(qtyDisplay.value) || 1;
                qty += 1;
                qtyDisplay.value = qty;
                hiddenQty.value = qty;
            });
        });

        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const qtyDisplay = this.closest('.input-group').querySelector('.qty');
                const hiddenQty = this.closest('form').querySelector('.quantity-input');
                let qty = parseInt(qtyDisplay.value) || 1;
                if (qty > 1) {
                    qty -= 1;
                    qtyDisplay.value = qty;
                    hiddenQty.value = qty;
                }
            });
        });

        // ===== Size Dropdown Change =====
        document.querySelectorAll('.size-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const price = this.options[this.selectedIndex].dataset.price;
                const productId = this.dataset.productId;
                const priceElement = document.getElementById('price-' + productId);
                if (priceElement) priceElement.textContent = parseInt(price).toLocaleString();
            });
        });

        // ===== Payment Section =====
        function showPaymentSection(method) {
            document.getElementById('cashPaymentSection').style.display = 'none';
            document.getElementById('mobilePaymentSection').style.display = 'none';
            if (method === 'cash') document.getElementById('cashPaymentSection').style.display = 'block';
            if (method === 'mobile') document.getElementById('mobilePaymentSection').style.display = 'block';
            document.getElementById('selectedPaymentMethod').value = method;
        }



        // ===== Note Modal =====
        let selectedProductId = null;
        document.querySelectorAll('[data-bs-target="#noteModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedProductId = this.dataset.productId;
                document.getElementById('noteTextarea').value = document.getElementById('noteInput_' +
                    selectedProductId)?.value || '';
            });
        });
        document.getElementById('saveNoteBtn').addEventListener('click', () => {
            if (selectedProductId) {
                document.getElementById('noteInput_' + selectedProductId).value = document.getElementById(
                    'noteTextarea').value;
            }
            bootstrap.Modal.getInstance(document.getElementById('noteModal')).hide();
        });

        // ===== Order Type & Delivery Location =====
        function setOrderType(type) {
            const url = new URL(window.location.href);
            url.searchParams.set('orderType', type);
            url.searchParams.delete('deliveryLocation');
            window.location.href = url.toString();
        }

        function setDeliveryLocation(id) {
            const url = new URL(window.location.href);
            url.searchParams.set('deliveryLocation', id);
            window.location.href = url.toString();
        }

        function calculateChange() {
    const cash = parseFloat(document.getElementById('cashReceived').value) || 0;
    const total = {{ $total ?? 0 }};
    const change = cash - total;

    if (change < 0) {
        alert("Uang tidak cukup");
        return;
    }

    document.getElementById('changeDue').innerText = change.toLocaleString();
    document.getElementById('hiddenCashReceived').value = cash;
    document.getElementById('hiddenChangeDue').value = change;
}

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const method = document.getElementById('selectedPaymentMethod').value;
    if (!method) {
        e.preventDefault();
        alert('Pilih metode pembayaran terlebih dahulu');
    }
});


document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const method = document.getElementById('selectedPaymentMethod').value;
    const orderType = document.getElementById('orderType').value;

    if (!orderType) {
        e.preventDefault();
        alert('Pilih jenis pesanan terlebih dahulu!');
        return;
    }

    if (!method) {
        e.preventDefault();
        alert('Pilih metode pembayaran terlebih dahulu!');
        return;
    }

    // Set Customer Details
    document.getElementById('hiddenCustomerName').value = document.getElementById('customerNameInput').value;
    document.getElementById('hiddenCustomerPhone').value = document.getElementById('customerPhoneInput').value;
});


    </script>
</body>

</html>
