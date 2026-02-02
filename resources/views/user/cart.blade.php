@extends('user.layouts.master')

@section('content')
<section class="container py-3" style="background-color: #ffffff;">
    <div class="row g-4">

        <!-- DAFTAR KERANJANG -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Keranjang Belanja</h4>
                <a href="{{ route('climenu') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Menu
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-secondary">
                        <tr class="text-center">
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Ukuran</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Harga Diskon</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cartItems as $item)
                        <tr class="text-center">
                            <td>
                                <img src="{{ asset('productImages/' . urlencode($item->image)) }}"
     class="img-fluid rounded-circle"
     style="width:50px;height:50px; object-fit:cover;"
     alt="{{ $item->name }}">


                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->size }}</td>
                            <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                            <td>{{ $item->cart_qty }}</td>
                            <td>Rp {{ number_format($item->discountPrice,0,',','.') }}</td>
                            <td>Rp {{ number_format($item->discountPrice * $item->cart_qty,0,',','.') }}</td>
                            <td>
                                <form action="{{ route('removeCart', $item->cartId) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Keranjang kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('climenu') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Menu
                </a>
            </div>
        </div>

        <!-- RINGKASAN PESANAN -->
        <div class="col-lg-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">

                    @php
                        $orderCode = $cartItems->first()->orderCode ?? '';
                        $subtotal = $cartItems->sum(fn($i) => $i->discountPrice * $i->cart_qty);
                        $taxAmount = $taxRate ? ceil(($subtotal * $taxRate)/100) : 0;
                        $deliveryFee = 0;
                        $total = $subtotal + $taxAmount + $deliveryFee;
                    @endphp

                    <div class="mb-3 text-center">
                        <h5 class="fw-bold">Kode Pesanan</h5>
                        <p>{{ $orderCode }}</p>
                    </div>

                    <form id="checkoutForm" action="{{ route('order.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="orderCode" value="{{ $orderCode }}">
                        <input type="hidden" name="deliveryFee" id="deliveryFeeInput" value="0">
                        <input type="hidden" name="totalAmount" id="totalAmountInput" value="{{ $total }}">

                        <input type="text" name="customer_name" class="form-control mb-2" placeholder="Nama Customer" required>
                        <input type="text" name="phone" class="form-control mb-3" placeholder="No Telepon / WhatsApp" required>

                        <label for="orderType" class="form-label">Jenis Pesanan:</label>
                        <select id="orderType" name="orderType" class="form-select mb-3" required>
                            <option value="1">Bawa Pulang</option>
                            <option value="2">Makan di Tempat</option>
                            <option value="3">Delivery / Antar</option>
                        </select>

                        <hr class="bg-light">

                        <div class="d-flex justify-content-between">
                            <span>Pajak</span>
                            <span>Rp <span id="taxAmountDisplay">{{ number_format($taxAmount,0,',','.') }}</span></span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Biaya Pengiriman</span>
                            <span>Rp <span id="deliveryFeeDisplay">0</span></span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Subtotal</span>
                            <span>Rp <span id="subtotalDisplay">{{ number_format($subtotal,0,',','.') }}</span></span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>Rp <span id="totalDisplay">{{ number_format($total,0,',','.') }}</span></span>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">
                            Konfirmasi Pesanan
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</section>

<script>
    const orderType = document.getElementById('orderType');
    const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
    const deliveryFeeInput = document.getElementById('deliveryFeeInput');
    const subtotal = {{ $subtotal }};
    const taxAmount = {{ $taxAmount }};
    const totalDisplay = document.getElementById('totalDisplay');

    orderType.addEventListener('change', function() {
        let deliveryFee = 0;
        if(this.value == '3') {
            deliveryFee = 15000; // default delivery
        }
        deliveryFeeDisplay.innerText = deliveryFee.toLocaleString('id-ID');
        deliveryFeeInput.value = deliveryFee;
        let finalTotal = subtotal + taxAmount + deliveryFee;
        totalDisplay.innerText = finalTotal.toLocaleString('id-ID');
        document.getElementById('totalAmountInput').value = finalTotal;
    });
</script>
@endsection

