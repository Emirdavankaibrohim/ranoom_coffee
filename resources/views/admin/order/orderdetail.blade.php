@extends('admin.layouts.master')
@section('content')
    <section class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-lg-12 mt-4">
                <div class="card shadow-sm border-2">
                    <div class="card-body p-4">
                        <!-- Cart Section -->
                        <h3 class="mb-4 fw-bold text-center">Rincian Pesanan</h3>

                        <div class="table-responsive">
                            <!-- Customer Details -->
                            <div class="mb-4">
                                <h5 class="fw-bold">Data Pelanggan</h5>
                                <p class="mb-1">Nama: <strong>{{ $details->first()->customer_name ?? '-' }}</strong></p>
                                <p class="mb-1">No. Telepon: <strong>{{ $details->first()->customer_phone ?? '-' }}</strong></p>
                                <p>Status:
                                    @php $status = $details->first()->status; @endphp
                                    @if($status == 1) <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                    @elseif($status == 2) <span class="badge bg-primary">Diproses</span>
                                    @elseif($status == 3) <span class="badge bg-success">Selesai</span>
                                    @else <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Cart Table -->
                            <table class="table table-hover align-middle text-center">
                                <thead class="table-secondary">
    <tr>
        <th>No</th>
        <th>GAMBAR</th>
        <th class="text-center whitespace-nowrap">NAMA PRODUK</th>
        <th class="text-center whitespace-nowrap">HARGA</th>
        <th class="text-center whitespace-nowrap">JUMLAH</th>
        <th class="text-center whitespace-nowrap">UKURAN</th>
        <th class="text-center whitespace-nowrap">JENIS PESANAN</th>
        <th class="text-center whitespace-nowrap">CATATAN</th>
        <th class="text-center whitespace-nowrap">STATUS</th>
    </tr>
</thead>
                                <tbody>
                                    
                                    @foreach ($details as $item)
                                        <tr>
                                             {{-- NOMOR (MULAI DARI 1) --}}
                                    <td class="fw-bold">{{ $loop->iteration }}</td>
                                            <td>
                                                <img class="rounded-circle"
                                                    src="{{ asset('productImages/' . $item->image) }}" alt="Product Image"
                                                    style="width: 64px; height: 64px;">
                                            </td>
                                            <td class="align-middle">{{ $item->name }}</td>
                                            <td class="align-middle">{{ $item->price }}</td>
                                            <td class="align-middle fw-bold">{{ $item->qty }}</td>
                                            <td class="align-middle">{{ strtoupper(substr($item->size, 0, 1)) }}</td>

                                            <td class="align-middle text-danger fw-bolder">
                                                @switch($item->order_type)
                                                    @case(1)
                                                        Bawa Pulang
                                                    @break

                                                    @case(2)
                                                        Makan di Tempat
                                                    @break

                                                    @case(3)
                                                        Antar / Delivery
                                                    @break

                                                    @default
                                                        Tidak Diketahui
                                                @endswitch
                                            </td>
                                            <td class="align-middle fw-bolder">
                                                @if ($item->notes)
                                                    <span class="badge bg-warning text-dark">{{ $item->notes }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                {{-- Logic for Admin/Cashier: Approve Status 1 -> 2 --}}
                                                @if ((auth()->user()->role === 'admin' || auth()->user()->role === 'cashier') && $item->status == 1)
                                                    <form action="{{ route('order.updateOrder') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="order_code" value="{{ $item->order_code }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        <input type="hidden" name="size" value="{{ $item->size }}">
                                                        <input type="hidden" name="action" value="accept">
                                                        <button type="submit" class="btn btn-primary shadow-sm me-2">Approve</button>
                                                    </form>
                                                    <form action="{{ route('order.updateOrder') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="order_code" value="{{ $item->order_code }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        <input type="hidden" name="size" value="{{ $item->size }}">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="btn btn-danger shadow-sm">Reject</button>
                                                    </form>

                                                {{-- Logic for Chef: Cook Status 2 -> 3 --}}
                                                @elseif (auth()->user()->role === 'chef' && $item->status == 2)
                                                    <form action="{{ route('order.updateCookingStatus', $item->id ?? $item->order_code) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="order_code" value="{{ $item->order_code }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        <input type="hidden" name="size" value="{{ $item->size }}">
                                                        <button type="submit" class="btn btn-success shadow-sm">Done</button>
                                                    </form>
                                                    <form action="{{ route('order.updateOrder') }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="order_code" value="{{ $item->order_code }}">
                                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                        <input type="hidden" name="size" value="{{ $item->size }}">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="btn btn-warning shadow-sm">Cancel</button>
                                                    </form>

                                                {{-- Status Display for others/completed --}}
                                                @elseif ($item->status == 2)
                                                    <span class="badge bg-primary">Sedang Diproses</span>
                                                @elseif ($item->status == 3)
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif ($item->status == 4)
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                     <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('order.orderlist') }}" class="btn text-start mb-2"
                            style="background-color: #4e2318; color: white;"><svg xmlns="http://www.w3.org/2000/svg"
                                width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left me-2"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                            </svg>Back</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
