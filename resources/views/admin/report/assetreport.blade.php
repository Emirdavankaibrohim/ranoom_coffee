@extends('admin.layouts.master')

@section('content')
<section class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 mt-4">

            <!-- Header -->
            <div class="row align-items-center mb-4">
                <div class="col-6">
                    <h3 class="fw-bold text-dark">📦 Laporan Aset</h3>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-success" onclick="exportTableToExcel('assetTable')">
                        <i class="fas fa-file-excel"></i> Ekspor ke Excel
                    </button>
                </div>
            </div>

            <!-- Filter -->
            <div class="card p-3 shadow-sm mb-4">
                <form action="{{ route('assetReport') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control"
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100">🔍 Filter</button>
                    </div>
                </form>
            </div>

            <!-- Tabel -->
            @if(!empty($results) && count($results) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center" id="assetTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Nomor Seri</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Pengguna Ditugaskan</th>
                                <th>Tanggal Pembelian</th>
                                <th>Nilai Pembelian (Rp)</th>
                                <th>Penyusutan (%)</th>
                                <th>Kadaluarsa Garansi</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $asset)
                                <tr>
                                    <td>{{ $asset->serial_number }}</td>
                                    <td>{{ $asset->name }}</td>
                                    <td>{{ $asset->category ? $asset->category->name : '-' }}</td>
                                    <td>{{ $asset->assignedUser ? $asset->assignedUser->name : '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d-m-Y') }}</td>
                                    <td>
                                        Rp {{ number_format($asset->purchase_value, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $asset->depreciation_rate ?? '-' }}</td>
                                    <td>
                                        {{ $asset->warranty_expiry_date
                                            ? \Carbon\Carbon::parse($asset->warranty_expiry_date)->format('d-m-Y')
                                            : '-' }}
                                    </td>
                                    <td>{{ $asset->status }}</td>
                                    <td>{{ $asset->notes }}</td>
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

<!-- Script Export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'Laporan_Aset_Rupiah.xlsx') {
        const table = document.getElementById(tableId);
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Laporan Aset" });
        XLSX.writeFile(workbook, filename);
    }
</script>
@endsection
