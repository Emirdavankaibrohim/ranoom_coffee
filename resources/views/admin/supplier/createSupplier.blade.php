@extends('admin.layouts.master')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4">
                <h4 class="mb-3 fw-bold text-center">Tambah Supplier Baru</h4>

                <form action="{{ route('createSupplier') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Supplier</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kontak</label>
                        <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror">
                        @error('contact')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2"></textarea>
                        @error('address')
                            <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('supplier.index') }}" class="btn btn-primary w-100 text-white">
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
