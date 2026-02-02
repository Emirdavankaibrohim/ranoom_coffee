@extends('admin.layouts.master')

@section('content')
<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">
            <!-- Header & Ekspor -->
            <div class="row align-items-center mb-4">
                <div class="col-6">
                    <h3 class="fw-bold text-dark">📋 Umpan Balik Pelanggan</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('feedbackTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

            <!-- Bagian Filter -->
            <div class="card p-3 shadow-sm mb-4">
                <form action="{{ route('feedbackReport') }}" method="GET" class="row g-3">
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

            <!-- Tabel Umpan Balik -->
            @if (!empty($feedback) && count($feedback) > 0)
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="feedbackTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Rating</th>
                            <th>Subjek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedback as $item)
                        <tr>
                            <td>{{ $item->created_at->format('j-F-Y') }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->rating }}</td>
                            <td>{{ $item->subject }}</td>
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

<!-- Script untuk Ekspor ke Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'Laporan_Umpan_Balik_Pelanggan.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(workbook, filename);
    }
</script>
@endsection
