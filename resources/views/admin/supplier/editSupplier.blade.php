@extends('admin.layouts.master')

@section('content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <form action="{{ route('updateSupplier', $supplierinfo->id) }}" method="POST"
                  class="p-4 rounded shadow-lg"
                  style="background-color: #4a2e18; color: #fdf4e3;">
                @csrf

                <h3 class="text-center fw-bold mb-3 text-white">Perbarui Informasi Supplier</h3>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Supplier</label>
                    <input type="text" name="name" value="{{ old('name', $supplierinfo->name) }}"
                        class="form-control rounded-3 @error('name') is-invalid @enderror"
                        style="background-color: #fdf4e3; color: #2e1c0c;">
                    @error('name')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label class="form-label fw-bold">Sisa Tagihan</label>
                            <input type="number" step="100" name="due_amount"
                                value="{{ old('due_amount', $totalDueAmount) }}"
                                class="form-control rounded-3 @error('due_amount') is-invalid @enderror"
                                style="background-color: #fdf4e3; color: #2e1c0c;">
                            @error('due_amount')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col">
                            <label class="form-label fw-bold">Jumlah Dibayar</label>
                            <input type="number" name="paid_amount" step="100"
                                   class="form-control" required>
                            @error('paid_amount')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kontak</label>
                    <input type="text" name="contact" value="{{ old('contact', $supplierinfo->contact) }}"
                        class="form-control rounded-3 @error('contact') is-invalid @enderror"
                        style="background-color: #fdf4e3; color: #2e1c0c;">
                    @error('contact')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat</label>
                    <textarea name="address" rows="2"
                        class="form-control rounded-3 @error('address') is-invalid @enderror"
                        style="background-color: #fdf4e3; color: #2e1c0c;">{{ old('address', $supplierinfo->address) }}</textarea>
                    @error('address')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold">Status</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" value="Active"
                            {{ old('status', $supplierinfo->status) == 'Active' ? 'checked' : '' }} required>
                        <label class="form-check-label text-white">Aktif</label>
                    </div>

                    <div class="form-check form-check-inline">
                        @if($totalDueAmount > 0)
                            {{-- Radio Tidak Aktif (dinonaktifkan karena masih ada tagihan) --}}
                            <input type="radio" class="form-check-input" disabled>
                            <label class="form-check-label text-white" onclick="alertDue()">Tidak Aktif</label>
                        @else
                            {{-- Radio Tidak Aktif --}}
                            <input class="form-check-input" type="radio" name="status" value="Inactive"
                                {{ old('status', $supplierinfo->status) == 'Inactive' ? 'checked' : '' }} required>
                            <label class="form-check-label text-white">Tidak Aktif</label>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-outline-warning w-100 text-white">
                            Perbarui Data
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('supplier.index') }}" class="btn btn-outline-primary w-100 text-white">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function alertDue() {
        alert("Supplier masih memiliki sisa tagihan. Status tidak dapat diubah menjadi Tidak Aktif.");
    }
</script>
@endsection
