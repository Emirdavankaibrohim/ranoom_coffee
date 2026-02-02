@extends('admin.layouts.master')

@section('content')
<section class="container-fluid py-4">
    <h4 class="mb-3">
        Tambah Ukuran & Harga untuk <strong>{{ $product->name }}</strong>
    </h4>

    <form action="{{ route('prodsizestore', $product->id) }}" method="POST">
        @csrf

        <div id="sizePriceContainer">
            <div class="row mb-2 size-price-row">
                <div class="col-md-5">
                    <input
                        type="text"
                        name="sizes[]"
                        class="form-control"
                        placeholder="Ukuran (contoh: Small)"
                        required
                    >
                </div>
                <div class="col-md-5">
                    <input
                        type="text"
                        name="prices[]"
                        class="form-control rupiah"
                        placeholder="Harga (Rp)"
                        required
                    >
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100 remove-size-price">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <button type="button" id="addSizePrice" class="btn btn-primary btn-sm mt-2">
            + Tambah Ukuran & Harga
        </button>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                Simpan Ukuran
            </button>
            <a href="{{ route('product.prodlist') }}" class="btn btn-secondary">
                Kembali ke Daftar
            </a>
        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
    // Format angka ke Rupiah
    function formatRupiah(angka) {
        return angka
            .replace(/[^,\d]/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Event format Rupiah saat mengetik
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('rupiah')) {
            e.target.value = formatRupiah(e.target.value);
        }
    });

    // Tambah baris ukuran & harga
    document.getElementById('addSizePrice').addEventListener('click', function () {
        const container = document.getElementById('sizePriceContainer');
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'size-price-row');

        row.innerHTML = `
            <div class="col-md-5">
                <input
                    type="text"
                    name="sizes[]"
                    class="form-control"
                    placeholder="Ukuran (contoh: Medium)"
                    required
                >
            </div>
            <div class="col-md-5">
                <input
                    type="text"
                    name="prices[]"
                    class="form-control rupiah"
                    placeholder="Harga (Rp)"
                    required
                >
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-size-price">
                    Hapus
                </button>
            </div>
        `;
        container.appendChild(row);
    });

    // Hapus baris
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-size-price')) {
            e.target.closest('.size-price-row').remove();
        }
    });

    // Bersihkan format Rupiah sebelum submit (kirim angka murni)
    document.querySelector('form').addEventListener('submit', function () {
        document.querySelectorAll('.rupiah').forEach(input => {
            input.value = input.value.replace(/\./g, '');
        });
    });
</script>
@endsection
