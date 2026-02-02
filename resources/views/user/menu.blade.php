@extends('user.layouts.master')

@section('content')
<section class="container my-4">
    <h2 class="text-center text-white mb-4">Menu Kami</h2>

    <div class="row g-4">
        <div class="col-lg-12">

            {{-- SEARCH & ACTION --}}
            <div class="row g-4 mb-4">
                <div class="col-xl-3">
                    <form action="{{ route('climenu') }}" method="get">
                        <div class="input-group">
                            <input type="search" class="form-control p-3"
                                   name="searchKey"
                                   value="{{ request('searchKey') }}"
                                   placeholder="kata kunci">
                            <button class="input-group-text p-3">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-xl-3">
                    <form action="{{ route('reviewOrder') }}" method="get">
                        <input type="hidden" name="order_code" value="{{ request('order_code') }}">
                        <button class="btn btn-dark w-100 p-3"
                            {{ $orderCount == 0 ? 'disabled' : '' }}>
                            {{ $orderCount == 0 ? 'Tidak ada pesanan' : 'Lihat Pesanan Anda' }}
                        </button>
                    </form>
                </div>

                @if($cartCount > 0)
                <div class="col text-end">
                    <a href="{{ route('cartPage') }}" class="btn btn-primary p-3">
                        <i class="fa fa-shopping-bag me-2"></i> Lihat Keranjang
                    </a>
                </div>
                @endif
            </div>

            <div class="row g-4">

                {{-- KATEGORI --}}
                <div class="col-lg-3">
                    <h4 class="text-white">Kategori</h4>
                    <ul class="list-unstyled fruite-categorie">
                        <li>
                            <a href="{{ route('climenu') }}">
                                <i class="fa-solid fa-list"></i> Semua Kategori
                            </a>
                        </li>
                        @foreach ($categories as $item)
                        <li>
                            <a href="{{ route('climenu', $item->id) }}">
                                <i class="fa-regular fa-circle-dot"></i> {{ $item->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- PRODUK --}}
                <div class="col-lg-9">
                    <div class="row g-3">

                        @foreach ($products as $item)
                        <div class="col-lg-6">
                            <div class="card shadow-sm h-100 p-2">
                                <div class="row g-2 align-items-center">

                                    {{-- IMAGE --}}
                                    <div class="col-5">
                                        <div class="position-relative">
                                            <img src="{{ asset('productImages/' . $item->image) }}"
                                                 class="img-fluid rounded"
                                                 style="height:120px;object-fit:cover;width:100%">
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-1">
                                                {{ $item->category_name }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- INFO --}}
                                    <div class="col-7">
                                        <h6 class="mb-1">{{ $item->name }}</h6>

                                        {{-- HARGA --}}
                                        @foreach($item->sizes->take(3) as $size)
                                            @php
                                                $price = $size->price;
                                                $discount = $item->discount_percentage;
                                                $final = $discount
                                                    ? $price - ($price * $discount / 100)
                                                    : $price;
                                            @endphp

                                            <div class="d-flex justify-content-between" style="font-size:12px">
                                                @if($discount)
                                                    <span class="text-danger fw-bold">
                                                        Rp {{ number_format($final,0,',','.') }}
                                                    </span>
                                                    <del class="text-muted">
                                                        Rp {{ number_format($price,0,',','.') }}
                                                    </del>
                                                @else
                                                    <span class="fw-semibold">
                                                        Rp {{ number_format($price,0,',','.') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach

                                        {{-- DESKRIPSI --}}
                                        <p class="text-muted mt-1" style="font-size:12px">
                                            {{ Str::words($item->description, 8, '...') }}
                                        </p>

                                        {{-- ACTION --}}
                                        <form action="{{ route('addToCart', $item->id) }}"
                                              method="POST"
                                              class="d-flex align-items-center gap-1">
                                            @csrf

                                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="orderCode" value="{{ $orderCode ?? 0 }}">
                                            <input type="hidden" name="notes" id="noteInput_{{ $item->id }}">

                                            {{-- SIZE --}}
                                            @if($item->sizes->count())
                                            <select name="size"
                                                class="form-control form-control-sm text-center"
                                                style="max-width:45px;border:2px solid orange">
                                                @foreach($item->sizes as $size)
                                                    <option value="{{ $size->size }}">
                                                        {{ strtoupper($size->size[0]) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @endif

                                            {{-- NOTE --}}
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm note-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#noteModal"
                                                data-product-id="{{ $item->id }}">
                                                ✏️
                                            </button>

                                            {{-- ADD --}}
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- PAGINATION --}}
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- MODAL NOTE --}}
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Catatan Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea id="noteTextarea"
            class="form-control"
            rows="3"
            placeholder="Contoh: less sugar, no ice"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveNoteBtn">
            Simpan
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let activeProductId = null;

document.querySelectorAll('.note-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        activeProductId = this.dataset.productId;
        const hidden = document.getElementById('noteInput_' + activeProductId);
        document.getElementById('noteTextarea').value = hidden.value || '';
    });
});

document.getElementById('saveNoteBtn').addEventListener('click', function () {
    if (activeProductId) {
        document.getElementById('noteInput_' + activeProductId).value =
            document.getElementById('noteTextarea').value;
    }

    bootstrap.Modal.getInstance(
        document.getElementById('noteModal')
    ).hide();
});
</script>
@endsection
