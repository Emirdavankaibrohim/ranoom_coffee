@extends('admin.layouts.master')

@section('content')
<div class="container mt-3">
    <div class="card shadow-lg border-left-warning rounded-4 p-4 text-black">
        <h2 class="mb-4 fw-bold text-center">Tambah Informasi Pembelian</h2>

        {{-- Form Tambah Item --}}
        <form action="{{ route('supplier.addItem') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nama Bahan</label>
                    <input type="text" name="ingredient_name" class="form-control rounded-3" placeholder="Gula" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <select name="unit" class="form-select rounded-3" required>
                        <option value="kg">Kg</option>
                        <option value="kemasan">Kemasan</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" class="form-control rounded-3" min="1" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Harga Pokok</label>
                    <input type="text" id="cost_price_display" class="form-control rounded-3" placeholder="Rp 0" required>
                    <input type="hidden" name="cost_price" id="cost_price">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-3">
                        Tambah Item
                    </button>
                </div>
            </div>
        </form>

        <hr class="mt-5 mb-4">

        {{-- Tabel Item --}}
        <h4 class="mb-3 fw-bold">Item Pembelian</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Bahan</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                        <th>Harga Pokok</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(session('purchase_items', []) as $index => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ ucfirst($item['unit']) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp {{ number_format($item['cost_price'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('removeItem', $index) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada item yang ditambahkan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Informasi Pembelian --}}
        <div class="mt-5">
            <h4 class="fw-bold">Informasi Pembelian</h4>

            <form action="{{ route('storePurchase') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select rounded-3" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Total Keseluruhan</label>
                                <input type="text" class="form-control rounded-3"
                                       value="Rp {{ number_format(session('total_amount', 0), 0, ',', '.') }}" readonly>
                                <input type="hidden" name="total_amount"
                                       value="{{ session('total_amount', 0) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jumlah Dibayar</label>
                                <input type="text" id="paid_amount_display" class="form-control rounded-3" placeholder="Rp 0" required>
                                <input type="hidden" name="paid_amount" id="paid_amount">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Status Pembayaran</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_status" value="paid" required>
                            <label class="form-check-label">Lunas</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_status" value="partial">
                            <label class="form-check-label">Sebagian</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_status" value="due">
                            <label class="form-check-label">Jatuh Tempo</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success rounded-3 px-4">
                            Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Format Rupiah --}}
<script>
function formatRupiah(input, hidden) {
    input.addEventListener('input', function () {
        let value = this.value.replace(/[^0-9]/g, '');
        hidden.value = value;
        this.value = value
            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value)
            : '';
    });
}

formatRupiah(
    document.getElementById('cost_price_display'),
    document.getElementById('cost_price')
);

formatRupiah(
    document.getElementById('paid_amount_display'),
    document.getElementById('paid_amount')
);
</script>
@endsection
