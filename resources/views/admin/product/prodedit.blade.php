@extends('admin.layouts.master')

@section('content')
<section class="container-fluid py-4">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-lg-10">
            <div class="card border-1 shadow-sm p-4">
                <h2 class="fw-bold mb-4">Perbarui Produk</h2>

                <form action="{{ route('product.produpdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @php
                        $product = $products->first();
                    @endphp

                    <div class="row mt-2">

                        <!-- Gambar -->
                        <div class="col-md-4 text-center">
                            <input type="hidden" name="oldImage" value="{{ $product->image }}">
                            <input type="hidden" name="productId" value="{{ $product->id }}">

                            <img id="output"
                                 src="{{ asset('productImages/' . $product->image) }}"
                                 class="img-thumbnail rounded mb-3"
                                 style="width: 100%;">

                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   onchange="loadFile(event)">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Form Kanan -->
                        <div class="col-md-8">
                            <div class="row">

                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select name="category_name"
                                            class="form-control @error('category_name') is-invalid @enderror">
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                @selected(old('category_name', $product->category_id) == $item->id)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Nama Produk</label>
                                    <input type="text" name="name"
                                           value="{{ old('name', $product->name) }}"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stock"
                                           value="{{ old('stock', $product->qty) }}"
                                           class="form-control @error('stock') is-invalid @enderror">
                                    @error('stock')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Ukuran -->
                                <div class="col-md-6 mb-4">
                                    <input type="hidden" name="oldSize" id="oldSize">

                                    <label class="form-label fw-bold">Ukuran</label>
                                    <select id="size" name="size"
                                            class="form-control"
                                            onchange="onSizeChange()">
                                        <option value="">Pilih ukuran...</option>
                                        @foreach($products as $item)
                                            <option value="{{ $item->size }}"
                                                data-price="{{ $item->price }}"
                                                @selected(old('size') == $item->size)>
                                                {{ strtoupper($item->size) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('size')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Harga -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Harga (Rp)</label>

                                    <!-- Tampilan -->
                                    <input type="text" id="price_display"
                                           class="form-control"
                                           placeholder="Pilih ukuran untuk melihat harga"
                                           readonly>

                                    <!-- Nilai Asli -->
                                    <input type="hidden" id="price" name="price">

                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Deskripsi</label>
                                    <textarea name="description" rows="3"
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <!-- Tombol -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Perbarui
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('product.prodlist') }}" class="btn btn-secondary w-100">
                                        Kembali
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function loadFile(event) {
        document.getElementById('output').src =
            URL.createObjectURL(event.target.files[0]);
    }

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function onSizeChange() {
        const sizeSelect = document.getElementById("size");
        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];

        const size = selectedOption.value;
        const price = selectedOption.getAttribute("data-price");

        document.getElementById("oldSize").value = size || '';
        document.getElementById("price").value = price || '';
        document.getElementById("price_display").value =
            price ? formatRupiah(price) : '';
    }
</script>
@endsection
