@extends('admin.layouts.master')

@section('content')

<section class="container-fluid py-4 modern-tax-section">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6 ">
            <div class="card p-3 shadow-sm">
                <h3 class="text-dark fw-bold mb-4 text-center">Tambah Informasi Pengiriman</h3>

                <!-- Existing Locations Dropdown -->
                <div class="mb-2">

                    <label for="location_name" class="form-label fw-semibold">Cek Lokasi yang Sudah Ada</label>
                    <select name="location_id" class="form-select @error('location_id') is-invalid @enderror" id="location_id">
                        <option value="">Pilih lokasi yang tersedia...</option>
                        @foreach ($locations as $item)
                            <option value="{{ $item->id  }}"
                                    data-city="{{ $item->city }}"
                                    data-township="{{ $item->township }}"
                                    data-fee="{{ $item->fees }}"
                                @if (old('location_id') == $item->id) selected @endif>
                                {{ $item->city }} - {{ $item->township }} - {{ $item->fees }} Ks
                            </option>
                        @endforeach
                    </select>

                </div>

                <hr>

                <!-- Delivery Info Form -->
                <small class="fw-semibold mb-2">Tambah Baru atau Perbarui</small>
                <form action="{{ route('addDeliFees')}}" method="POST">
                    @csrf

                    <div class="form-floating mb-4">
                        <input type="text" name="city" id="city"
                            class="form-control @error('city') is-invalid @enderror"
                            placeholder="Masukkan nama kota...">
                        <label for="city">Kota</label>
                        @error('city')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="township" id="township"
                            class="form-control @error('township') is-invalid @enderror"
                            placeholder="Masukkan nama kecamatan...">
                        <label for="township">Kecamatan</label>
                        @error('township')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input type="number" name="deli_fees" id="deli_fees"
                            class="form-control @error('deli_fees') is-invalid @enderror"
                            placeholder="Masukkan biaya pengiriman">
                        <label for="deli_fees">Biaya Pengiriman</label>
                        @error('deli_fees')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <button type="submit" value="add" name="action"
                                class="btn btn-primary w-100 shadow-sm rounded-pill">
                                <i class="fas fa-plus-circle me-1"></i> Simpan
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="action" value="update"
                                class="btn btn-dark w-100 shadow-sm rounded-pill">
                                <i class="fas fa-sync-alt me-1"></i> Perbarui
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById('location_id');
        const cityInput = document.getElementById('city');
        const townshipInput = document.getElementById('township');
        const feeInput = document.getElementById('deli_fees');

        select.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const city = selectedOption.getAttribute('data-city');
            const township = selectedOption.getAttribute('data-township');
            const fee = selectedOption.getAttribute('data-fee');

            if (city && township && fee !== null) {
                cityInput.value = city;
                townshipInput.value = township;
                feeInput.value = fee;
            } else {
                cityInput.value = '';
                townshipInput.value = '';
                feeInput.value = '';
            }
        });
    });
</script>
