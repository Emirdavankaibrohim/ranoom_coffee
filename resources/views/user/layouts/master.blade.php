<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- MDB UI Kit CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
    <title>Ranoom Coffee</title>
</head>

<body>

    <header class="text-center py-3" style="background-color: #42280e;">
        <div>
            <h1 class="fw-bold text-white mb-0">Ranoom</h1>
            <h1 class="text-white">Coffee</h1>
        </div>
        
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #42280e;">
        <div class="container">
            <!-- Tombol Menu -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Buka navigasi">
                <i class="fas fa-bars text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('user/home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('climenu') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('contactus') }}">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('reviewPage') }}">Ulasan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('customerProfile') }}">Profil</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link text-white"
                                style="background:none; border:none;">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="text-center py-3" style="background-color: #42280e;">
        <p class="text-white mb-0">
            Ranoom Coffee
        </p>
    </footer>

    @yield('scripts')
    @stack('scripts')

    <script>
        document.querySelector('.navbar-toggler').addEventListener('click', () => {
            document.getElementById('navbarNav').classList.toggle('show');
        });
    </script>

    @if (session('alert'))
        <script>
            Swal.fire({
                title: "{{ session('alert')['type'] == 'success' ? 'Berhasil!' : 'Gagal!' }}",
                text: "{{ session('alert')['message'] }}",
                icon: "{{ session('alert')['type'] }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script>
        function toggleDropdown(menuClass) {
            document.querySelector("." + menuClass).classList.toggle("show");
        }
    </script>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>

    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://unpkg.com/leaflet.photon/leaflet.photon.js"></script>

</body>
</html>
