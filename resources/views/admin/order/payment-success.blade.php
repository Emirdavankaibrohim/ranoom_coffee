@extends('admin.layouts.master')

@section('content')
<div class="container text-center mt-5">
    <h2 class="text-success fw-bold">Pembayaran Berhasil 🎉</h2>
    <p>Order Code: <strong>{{ $orderCode }}</strong></p>

    <div class="mt-4">
        <a href="{{ route('order.print', $orderCode) }}"
           class="btn btn-primary me-2">
           🖨 Cetak Struk
        </a>

        <a href="{{ route('order.orderlist') }}"
           class="btn btn-secondary">
           📦 Lihat Pesanan
        </a>
    </div>
</div>
@endsection
