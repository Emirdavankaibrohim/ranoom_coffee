@extends('user.layouts.master')

@section('content')
<section class="container-fluid py-4">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-12">
            <form action="{{ route('updateProfile', Auth::user()->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="city" id="city">

                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="card shadow-lg rounded-4">
                            <div class="card-body p-4">
                                <h2 class="text-center text-dark fw-bold mb-4">
                                    Perbarui Akun Anda
                                </h2>

                                <div class="row g-4">
                                    <!-- Foto Profil -->
                                    <div class="col-md-4 text-center">
                                        <label class="form-label text-muted mb-2">
                                            Foto Profil
                                        </label>

                                        <input type="hidden" name="oldImage" value="{{ auth()->user()->profile }}">

                                        <div class="mb-3">
                                            <img id="output"
                                                 class="img-profile img-thumbnail rounded-circle"
                                                 style="width: 150px;"
                                                 src="{{ auth()->user()->profile
                                                        ? asset('customerProfile/' . auth()->user()->profile)
                                                        : asset('admin/images/undraw_profile.svg') }}">
                                        </div>

                                        <input type="file"
                                               name="image"
                                               class="form-control @error('image') is-invalid @enderror"
                                               onchange="loadFile(event)">
                                        @error('image')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror

                                        <a href="{{ route('climenu') }}"
                                           class="btn btn-outline-danger w-100 rounded-pill mt-4">
                                            Kembali
                                        </a>
                                    </div>

                                    <!-- Nama & Telepon -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Email
                                            </label>
                                            <input type="email"
                                                   name="email"
                                                   id="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ auth()->user()->email }}"
                                                   disabled>
                                            @error('email')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Nama
                                            </label>
                                            <input type="text"
                                                   name="name"
                                                   id="name"
                                                   placeholder="Masukkan nama Anda"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', auth()->user()->name ?? auth()->user()->nickname) }}"
                                                   @if (auth()->user()->provider !== 'simple') disabled @endif>
                                            @error('name')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">
                                                Nomor Telepon
                                            </label>
                                            <input type="text"
                                                   name="phone"
                                                   id="phone"
                                                   placeholder="+62xxxxxxxxxx"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', auth()->user()->phone) }}">
                                            @error('phone')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <input type="submit"
                                               value="Perbarui Data"
                                               class="btn btn-primary w-100 mt-4 mb-3 rounded-pill">
                                    </div>

                                    <!-- Alamat Usaha -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">
                                                Alamat Usaha
                                            </label>
                                            <input type="text"
                                                   name="address"
                                                   id="address"
                                                   class="form-control @error('address') is-invalid @enderror"
                                                   placeholder="Ranoom Coffee, Kota Tanjungpinang, Kepulauan Riau"
                                                   value="{{ old('address', auth()->user()->address ?? 'Ranoom Coffee, Kota Tanjungpinang, Kepulauan Riau') }}">
                                            @error('address')
                                                <small class="invalid-feedback">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Peta -->
                                    <div id="map" class="map-container rounded mb-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@section('scripts')
<script>
    function loadFile(event) {
        const output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    }

    // Default lokasi: Ranoom Coffee, Tanjungpinang
    const map = L.map('map').setView([0.9189, 104.4665], 15);

    const marker = L.marker([0.9189, 104.4665], {
        draggable: true
    }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker.on('dragend', function () {
        const latlng = marker.getLatLng();

        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('address').value =
                    data.display_name || 'Ranoom Coffee, Kota Tanjungpinang, Indonesia';

                const city = data.address.city
                            || data.address.town
                            || data.address.village
                            || 'Tanjungpinang';

                document.getElementById('city').value = city;
            });
    });
</script>
@endsection
