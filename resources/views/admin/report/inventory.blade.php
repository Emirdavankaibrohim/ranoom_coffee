@extends('admin.layouts.master')

@section('content')
<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">
            <div class="row align-items-center mb-4">
                <div class="col-6">
                     <h3 class="fw-bold text-dark">📊 Analisis Stok Produk</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('stockTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

            <!-- Filter Tanggal -->
            <div class="card p-3 shadow-sm mb-4">
                <form action="{{ route('productAnalysis') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $start ?? '' }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $end ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100">🔍 Filter</button>
                    </div>
                </form>
            </div>

            <!-- Tabel Stok -->
            @if(!empty($stock) && $stock->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="stockTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Produk</th>
                            <th>Kategori</th>
                            <th>Nama Produk</th>
                            <th>Stok Awal</th>
                            <th>Terjual</th>
                            <th>Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stock as $item)
                        <tr>
                            <td>{{ $item->product_id ?? '-' }}</td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td>{{ $item->nama_produk ?? '-' }}</td>
                            <td>{{ $item->stok_awal ?? 0 }}</td>
                            <td>{{ $item->terjual ?? 0 }}</td>
                            <td>{{ $item->sisa_stok ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-secondary text-center">
                🚨 Tidak ada data untuk rentang tanggal ini.
            </div>
            @endif
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'Laporan_Analisis_Stok_Produk.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(workbook, filename);
    }
</script>
@endsection
