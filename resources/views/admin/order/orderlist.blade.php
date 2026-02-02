@extends('admin.layouts.master')
@section('content')
    <section class="container mt-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-12">
                <div class="card border-left-warning shadow border-2">
                    <div class="card-body">
                        <h2 class="text-medium text-center fw-bold mb-3">Pesanan Harian Pelanggan</h2>
                        <a href="{{ route('adminDashboard') }}"
                            class="btn btn-secondary align-items-center justify-content-center shadow-sm mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-arrow-return-left me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                            </svg> Back
                        </a>
                        <div class="table-responsive ">
                            <table class="table table-hover table-bordered align-middle text-center">
                                <thead class="table">
                                    <tr>
                                           <th>Kode Pesanan</th>
    <th>Nama Customer</th>
    <th>No HP</th>
    <th>Status Bayar</th>
    <th>Tanggal</th>
    <th>Status Order</th>
    <th>Aksi</th>
                                    </tr>
                                </thead>
<tbody>
@foreach($groupedOrders as $orderCode => $orders)
@php
    $first = $orders->first();
    // Highlight untuk pesanan baru yang menunggu verifikasi
    $rowClass = ($first->status == 1) ? 'table-warning' : '';
@endphp

<tr class="{{ $rowClass }}">
    <!-- ORDER CODE -->
    <td>{{ $orderCode }}</td>

    <!-- CUSTOMER NAME -->
    <td>{{ $first->customer_name ?? '-' }}</td>

    <!-- PHONE -->
    <td>{{ $first->customer_phone ?? '-' }}</td>

    <!-- PAYMENT STATUS -->
    <td>
        @if($first->status >= 1 && $first->status != 4)
            <span class="badge bg-success">SUDAH BAYAR</span>
        @else
            <span class="badge bg-danger">BATAL/GAGAL</span>
        @endif
    </td>

    <!-- DATE -->
    <td>{{ optional($first->created_at)->format('d M Y H:i') ?? '-' }}</td>

    <!-- ORDER STATUS -->
    <td>
        @if($first->status == 1)
            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
        @elseif($first->status == 2)
            <span class="badge bg-primary">Sedang Diproses</span>
        @elseif($first->status == 3)
            <span class="badge bg-success">Selesai</span>
        @else
            <span class="badge bg-danger">Ditolak</span>
        @endif
    </td>

    <!-- ACTION -->
    <td>
        <a href="{{ route('order.viewOrder', $orderCode) }}" class="btn btn-sm btn-info">
            Detail
        </a>
    </td>
</tr>
@endforeach
</tbody>


                            </table>
                            <div class="d-flex justify-content-end">{{ $groupedOrders->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

