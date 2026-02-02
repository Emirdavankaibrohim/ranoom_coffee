@extends('admin.layouts.master')

@section('content')
<section class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <!-- Header & Pencarian -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('product.prodcreate') }}"
                   class="btn btn-primary align-items-center justify-content-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg>
                    Tambah Produk
                </a>

                <form action="{{ route('product.prodlist') }}" method="get" class="d-flex">
                    <input type="text" name="searchKey" class="form-control me-1"
                           placeholder="Cari produk..."
                           value="{{ request('searchKey') }}">
                    <button class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Daftar Produk -->
            <div class="row">
                @foreach ($products as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card shadow-sm">

                            <div class="position-relative">
                                <img src="{{ asset('storage/menu/' . $item->image) }}"
     class="card-img-top"
     alt="Gambar Produk"
     style="height: 180px; object-fit: cover;">



                                <!-- Ukuran & Harga -->
                                <span class="position-absolute bottom-0 start-0 m-2 badge bg-warning text-start">
                                    @foreach ($item->sizes as $size)
                                        <small class="text-dark d-block">
                                            {{ strtoupper($size->size) }} -
                                            <strong>Rp {{ number_format($size->price, 0, ',', '.') }}</strong>
                                        </small>
                                    @endforeach
                                </span>
                            </div>

                            <div class="card-body text-center">
                                <h6 class="card-title fw-bold">{{ $item->name }}</h6>

                                <span class="badge {{ $item->available_stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->available_stock > 0 ? 'Stok Tersedia' : 'Stok Habis' }}
                                </span>

                                <!-- Tombol Aksi -->
                                <div class="mt-3">
                                    <a href="{{ route('product.prodedit', $item->id) }}"
                                       class="btn btn-sm btn-outline-success"
                                       title="Edit Produk">
                                        ✏️
                                    </a>

                                    <a href="{{ route('product.proddelete', $item->id) }}"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Hapus Produk">
                                        🗑️
                                    </a>

                                    @if($item->sizes->count() < 3)
                                        <a href="{{ route('prodsize', $item->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Tambah Ukuran">
                                            ➕
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</section>
@endsection
