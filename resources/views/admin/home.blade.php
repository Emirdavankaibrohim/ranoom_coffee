@extends('admin.layouts.master')

@section('content')
<section class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col">

            {{-- INFO CARD --}}
            <div class="row mt-4">
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'cashier')

                {{-- STOK MENIPIS --}}
                <div class="col-xl-3 col-md-6 col-sm-12 mb-2">
                    <div class="card border-left-warning shadow h-100"
                        data-bs-toggle="modal" data-bs-target="#outOfStockModal">
                        <div class="card-body">
                            <div class="text-sm fw-bold text-danger mb-1">Stok Menipis</div>
                            <div class="h5 fw-bold">{{ $outofstock->count() }}</div>
                        </div>
                    </div>
                </div>

                {{-- PEMBELIAN BULANAN --}}
                <div class="col-xl-3 col-md-6 col-sm-12 mb-2">
                    <div class="card border-left-warning shadow h-100">
                        <div class="card-body">
                            <div class="text-xs fw-bold text-success mb-1">Pembelian Bulanan</div>
                            <div class="h5 fw-bold">
                                Rp {{ number_format($purchaseCost,0,',','.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENJUALAN HARIAN --}}
                <div class="col-xl-3 col-md-6 col-sm-12 mb-2">
                    <div class="card border-left-warning shadow h-100">
                        <div class="card-body">
                            <div class="text-xs fw-bold text-primary mb-1">Penjualan Harian</div>
                            <div class="h5 fw-bold">
                                Rp {{ number_format($dailySales,0,',','.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENJUALAN BULANAN --}}
                <div class="col-xl-3 col-md-6 col-sm-12 mb-2">
                    <div class="card border-left-warning shadow h-100">
                        <div class="card-body">
                            <div class="text-xs fw-bold text-secondary mb-1">Penjualan Bulanan</div>
                            <div class="h5 fw-bold">
                                Rp {{ number_format($monthlySales,0,',','.') }}
                            </div>
                        </div>
                    </div>
                </div>

                @endif
            </div>

            @php
                $topProductLabels = [];
                $topProductCounts = [];

                if (auth()->user()->role === 'admin') {
                    $topProductLabels = $topProducts->pluck('name');
                    $topProductCounts = $topProducts->pluck('total_quantity_sold');
                }
            @endphp

            {{-- GRAFIK ADMIN --}}
            @if(auth()->user()->role === 'admin')
            <div class="row mt-3">

                {{-- PRODUK TERLARIS --}}
                <div class="col-xl-6 mb-3">
                    <div class="card shadow p-3 h-100">
                        <h5 class="fw-bold">Produk Terlaris</h5>
                        <canvas id="topProductsChart" height="200"></canvas>
                    </div>
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="col-xl-6 mb-3">
                    <div class="card shadow p-3 h-100">
                        <h5 class="fw-bold">Metode Pembayaran</h5>

                        @foreach (['cash'=>'Tunai','mobile'=>'QR / Mobile','card'=>'Kartu'] as $key=>$label)
                            @php
                                $count = $paymentMethods->where('payment_method',$key)->first()->count ?? 0;
                            @endphp

                            <p class="fw-bold mt-3">
                                {{ $label }}
                                <span class="float-end">{{ $count }}</span>
                            </p>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ min($count * 10,100) }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            @endif

            {{-- GRAFIK PENJUALAN --}}
            <div class="row mt-3">
                <div class="col-xl-8">
                    <div class="card shadow">
                        <div class="card-header" style="background:#50301a">
                            <h6 class="fw-bold text-white mb-0">Ringkasan Penjualan</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="myAreaChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Jenis Pesanan</h5>
                            <canvas id="myPieChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const rupiah = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);

new Chart(document.getElementById('myAreaChart'), {
    type: 'line',
    data: {
        labels: @json($salesOverview->pluck('date')),
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: @json($salesOverview->pluck('daily_sales')),
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => rupiah(ctx.raw)
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: value => rupiah(value)
                }
            }
        }
    }
});

new Chart(document.getElementById('myPieChart'), {
    type: 'pie',
    data: {
        labels: @json($orderType->pluck('order_type')),
        datasets: [{ data: @json($orderType->pluck('count')) }]
    }
});

@if(auth()->user()->role === 'admin')
new Chart(document.getElementById('topProductsChart'), {
    type: 'doughnut',
    data: {
        labels: @json($topProductLabels),
        datasets: [{ data: @json($topProductCounts) }]
    }
});
@endif
</script>
@endsection
