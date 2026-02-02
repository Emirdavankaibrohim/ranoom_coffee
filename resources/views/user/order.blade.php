@extends('user.layouts.master')

@section('content')
<section class="container my-5 py-4" style="background-color: #f8f9fa; border-radius: 10px;">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <!-- Tombol Kembali -->
                    <a href="{{ route('climenu') }}" class="btn btn-primary justify-content-end">
                        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                    </a>

                    <h2 class="mb-2 text-center">Pesanan Anda</h2>

                    <div class="table-responsive">
                        <!-- Tabel Pesanan -->
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Kode Pesanan</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Asli</th>
                                    <th>Harga Setelah Diskon</th>
                                    <th>Ukuran</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $item)
                                    <tr>
                                        <td class="text-primary align-middle">
                                            {{ $item->order_code }}
                                        </td>

                                        <td class="align-middle">
                                            <img src="{{ asset('productImages/' . $item->image) }}"
                                                 alt="{{ $item->name }}"
                                                 class="img-fluid rounded-circle"
                                                 style="width: 50px; height: 50px;">
                                        </td>

                                        <td class="align-middle">
                                            {{ $item->name }}
                                        </td>

                                        <td class="align-middle">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>

                                        <td class="align-middle">
                                            Rp {{ number_format($item->totalprice, 0, ',', '.') }}
                                        </td>

                                        <td class="align-middle">
                                            {{ $item->size }}
                                        </td>

                                        <td class="align-middle">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="align-middle">
                                            {{ $item->notes }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Ringkasan Total -->
                    <div class="mt-4 text-end">
                        <h5 class="fw-bold">
                            Total Pembayaran: Rp {{ number_format($orderTotal, 0, ',', '.') }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
