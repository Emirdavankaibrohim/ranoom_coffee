@extends('user.layouts.master')

@section('content')
<section class="container py-5" style="background-color: #ffffff;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Pembayaran QRIS</h4>
                </div>
                <div class="card-body text-center p-5">
                    <h5 class="mb-3">Silakan pindai kode QR di bawah ini</h5>
                    <h3 class="fw-bold text-primary mb-4">Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</h3>
                    
                    {{-- QR Code Image --}}
                    <div class="mb-4">
                        <img src="{{ asset('adminProfile/QRcode.jpeg') }}" alt="QRIS Code" class="img-fluid border p-2 rounded" style="max-width: 250px;">
                    </div>

                    <p class="text-muted mb-4">
                        Pindai menggunakan aplikasi pembayaran apa saja (GoPay, OVO, Dana, BCA Mobile, dll).
                    </p>

                    <form action="{{ route('paymentConfirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="orderCode" value="{{ $orderCode }}">
                        <input type="hidden" name="customer_name" value="{{ $customer_name }}">
                        <input type="hidden" name="phone" value="{{ $phone }}">
                        <input type="hidden" name="orderType" value="{{ $orderType }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa-solid fa-check-circle me-2"></i> Saya Sudah Membayar
                            </button>
                            <a href="{{ route('cartPage') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
