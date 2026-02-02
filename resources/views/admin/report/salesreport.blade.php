@extends('admin.layouts.master')

@section('content')
<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">

            <!-- Header -->
            <div class="row align-items-center mb-4">
                <div class="col-6">
                    <h3 class="fw-bold text-dark">📊 Laporan Penjualan Harian</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('salesTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

            <!-- Filter Tanggal -->
            <div class="card p-3 shadow-sm mb-4">
                <form action="{{ route('salesReport') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control"
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100">🔍 Filter</button>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            @if($results->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center" id="salesTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Penjualan (Rp)</th>
                                <th>Pesanan 📦</th>
                                <th>Rata-rata Nilai Pesanan (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->tanggal)->format('d-m-Y') }}</td>
                                    <td>Rp {{ number_format($day->totalSales, 0, ',', '.') }}</td>
                                    <td>{{ $day->totalOrders }}</td>
                                    <td>Rp {{ number_format($day->rataRataNilaiOrder, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <!-- Total Keseluruhan -->
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>Total</td>
                                <td>Rp {{ number_format($results->sum('totalSales'), 0, ',', '.') }}</td>
                                <td>{{ $results->sum('totalOrders') }}</td>
                                <td>
                                    Rp {{
                                        number_format(
                                            $results->sum('totalOrders') > 0
                                                ? $results->sum('totalSales') / $results->sum('totalOrders')
                                                : 0,
                                            0, ',', '.'
                                        )
                                    }}
                                </td>
                            </tr>
                        </tfoot>
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

<!-- Script Ekspor Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'Laporan_Penjualan_Harian_Rupiah.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Laporan Penjualan" });
        XLSX.writeFile(workbook, filename);
    }
</script>
@endsection
