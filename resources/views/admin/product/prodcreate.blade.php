@extends('admin.layouts.master')

@section('content')
<section class="container-fluid py-4">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-lg-10">
            <div class="card border-1 shadow-sm p-4">
                <h2 class="fw-bold mb-4">Tambah Produk Baru</h2>

                <form action="{{ route('product.prodstore') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-2">

                        <!-- Image -->
                        <div class="col-md-4 text-center">
                            <img id="output" src="{{ asset('defaultImg/default.jpg') }}"
                                 class="img-thumbnail rounded mb-3" style="width: 100%;">
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   onchange="loadFile(event)">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Form -->
                        <div class="col-md-8">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nama Produk</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select name="category_name"
                                            class="form-control @error('category_name') is-invalid @enderror">
                                        <option value="">Pilih Nama Kategori...</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('category_name') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_name')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Harga Rupiah -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Harga (Rp)</label>
                                    <input type="text" id="price_display"
                                           class="form-control"
                                           placeholder="Rp 0"
                                           oninput="formatRupiah(this)">
                                    <input type="hidden" name="price" id="price">
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stock"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           value="{{ old('stock') }}">
                                    @error('stock')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Deskripsi</label>
                                    <textarea name="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        💾 Simpan Produk
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('product.prodlist') }}" class="btn btn-secondary w-100">
                                        ⬅️ Kembali
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

    function formatRupiah(el) {
        let value = el.value.replace(/[^0-9]/g, '');
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        el.value = value ? 'Rp ' + formatted : '';
        document.getElementById('price').value = value;
    }
</script>
@endsection
