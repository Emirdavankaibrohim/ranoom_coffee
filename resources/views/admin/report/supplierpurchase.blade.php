@extends('admin.layouts.master')

@section('content')

<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">
            <div class="row align-items-center mb-4">
                <div class="col-6">
                     <h3 class="fw-bold text-dark">📋 Ringkasan Laporan Pembelian</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('purchaseTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

            <!-- Bagian Filter -->
            <div class="card p-3 shadow-sm mb-4">
                <form action="{{ route('supplierPurchase') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100">🔍 Filter</button>
                    </div>
                </form>
            </div>

            <!-- Bagian Tabel -->
            @if(!empty($supplierPurchase) && count($supplierPurchase) > 0)
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="purchaseTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Pemasok</th>
                            <th>Kontak</th>
                            <th>Total Pembelian</th>
                            <th>Dibayar</th>
                            <th>Belum Dibayar</th>
                            <th>Tanggal</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach($supplierPurchase as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->contact }}</td>

        <td>Rp {{ number_format($item->total_pembelian, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($item->total_tunggakan, 0, ',', '.') }}</td>

        <td>{{ $item->tanggal }}</td>

        <td>
            <span class="badge bg-{{ $item->total_tunggakan == 0 ? 'success' : 'danger' }}">
                {{ $item->total_tunggakan == 0 ? 'Lunas' : 'Belum Lunas' }}
            </span>
        </td>
    </tr>
    @endforeach
</tbody>

                </table>
            </div>
            @else
            <div class="alert alert-secondary text-center" role="alert">
                🚨 Tidak ada data untuk rentang tanggal ini.
            </div>
            @endif

        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'Laporan_Pembelian_Pemasok.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(workbook, filename);
    }
</script>

@endsection
