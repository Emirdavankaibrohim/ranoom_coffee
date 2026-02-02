@extends('admin.layouts.master')

@section('content')
<div class="container mt-3">
    <h2 class="fw-bold text-center">Daftar Barang Aset</h2>

    <a href="{{ route('assets.create') }}" class="btn btn-primary mb-3">
        Tambah Data Aset
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor Seri</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Pengguna</th>
                <th>Tanggal Pembelian</th>
                <th>Nilai (Rp)</th>
                <th>Status</th>
                <th>Unit</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($assets as $asset)
                <tr>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->category->name ?? '-' }}</td>
                    <td>{{ $asset->assignedUser->name ?? '-' }}</td>
                    <td>{{ $asset->purchase_date }}</td>
                    <td>
                        Rp {{ number_format($asset->purchase_value, 0, ',', '.') }}
                    </td>
                    <td>{{ $asset->status }}</td>
                    <td>{{ $asset->unit }}</td>
                    <td>{{ $asset->warranty_expiry_date }}</td>
                    <td>
                        <a href="{{ route('assets.edit', $asset->id) }}"
                           class="btn btn-outline-secondary rounded-pill btn-sm me-1">
                            ✏️
                        </a>

                        <form action="{{ route('assets.destroy', $asset->id) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger rounded-pill btn-sm"
                                    onclick="return confirm('Apakah yakin ingin menghapus data ini?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
