@extends('user.layouts.master')
@section('content')

<section id="review" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="text-white text-center mb-4">Tulis Ulasan</h2>

            <div class="card">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('addReview') }}" method="POST">
                        @csrf

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Anda</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <label class="form-label">Penilaian</label>
                            <div class="d-flex gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio"
                                           class="btn-check"
                                           name="rating"
                                           id="star{{ $i }}"
                                           value="{{ $i }}"
                                           required>
                                    <label class="btn btn-light rating-label"
                                           for="star{{ $i }}"
                                           data-value="{{ $i }}">
                                        <i class="fa fa-star"></i> {{ $i }}
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Ulasan -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Ulasan Anda</label>
                            <textarea class="form-control"
                                      id="message"
                                      name="subject"
                                      rows="4"
                                      required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    Kirim Ulasan
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('userDashboard') }}"
                                   class="btn btn-dark w-100 text-center">
                                    Kembali
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
