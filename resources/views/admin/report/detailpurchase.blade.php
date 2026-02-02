@extends('admin.layouts.master')

@section('content')

<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">
            <div class="row align-items-center mb-4">
                <div class="col-6">
                     <h3 class="fw-bold text-dark">Detail Pembelian</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('salesTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

        <!-- Filter Section -->
        <div class="card p-3 shadow-sm mb-4">
            <form action="{{ route('purchaseDetails') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">🔍 Filter</button>
                </div>
            </form>
        </div>

        @if(!empty($details) && count($details) > 0)
        <table class="table table-bordered text-center" id="salesTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>Pemasok</th>
                    <th>Nama</th>
                    <th>Harga Modal</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Total Harga</th>
                    <th>Tanggal</th>
               </tr>
            </thead>
            <tbody>
    @foreach($details as $item)
    <tr>
        <td>{{ $item->supplier }}</td>
        <td>{{ $item->nama_bahan }}</td>
        <td>Rp {{ number_format($item->harga_modal, 0, ',', '.') }}</td>
        <td>{{ $item->jumlah }}</td>
        <td>{{ $item->satuan }}</td>
        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
        <td>{{ $item->tanggal }}</td>
    </tr>
    @endforeach
</tbody>

        </table>
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
    function exportTableToExcel(tableId, filename = 'Detail Pembelian.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(workbook, filename);
    }
</script>
@endsection
