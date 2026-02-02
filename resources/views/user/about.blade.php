@extends('user.layouts.master')
@section('content')
    <section id="about" class="container my-3">
        {{-- <h2 class="text-center text-white mb-3">Tentang Kami</h2> --}}
        <div class="row text-white">
            <div class="col-md-6">
                <h3 class="mb-4">Tentang Kami</h3>
                <p>
                    Selamat datang di <strong>RANOOM Coffee</strong>! Kami memulai perjalanan kami pada tahun 2020
                    dengan misi menghadirkan pengalaman kopi terbaik untuk para pecinta kopi.
                    Berlokasi di pusat kota, kami menyediakan suasana yang nyaman untuk bersantai
                    dan menikmati berbagai pilihan minuman favorit Anda.
                </p>
                <p>
                    Alamat: RANOOM COFFEE, Jalan Karya, Jl. Panglima Dompak, Perumahan Wedadari No. 3,
                    Batu IX, Kecamatan Tanjungpinang Timur, Kota Tanjungpinang,
                    Kepulauan Riau 29112
                </p>
                <p>Telepon: 0822-9776-4291</p>
                <p>Email: contact@ranoomcoffee.com</p>
            </div>

            <div class="col-md-6">
                <h3 class="mb-4 text-center">Lokasi Kami</h3>

                <!-- Google Maps -->
                <div style="height: 300px; overflow: hidden; border-radius: 8px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d31914.736765436242!2d104.508037!3d0.885261!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d96ddf0f4882a9%3A0x3e360bc95a62aec0!2sRanoom%20coffee%20roastery%20%26%20lifestyle!5e0!3m2!1sen!2sid!4v1764650833121!5m2!1sen!2sid"
                        width="100%" height="300" style="border:0;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </section>
@endsection
