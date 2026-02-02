<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    // ===========================
    // RINGKASAN
    // ===========================
    public function reportOverview()
    {
        return view('admin.report.overview');
    }

    // ===========================
    // LAPORAN PENJUALAN
    // ===========================
    public function salesReportPage()
    {
        $results = Order::select(
                DB::raw('SUM(quantity) as totalOrders'),
                DB::raw('SUM(totalprice) as totalSales'),
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COALESCE(SUM(totalprice) / NULLIF(SUM(quantity),0), 0) as rataRataNilaiOrder')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.report.salesreport', compact('results'));
    }

    public function salesReport(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = Order::select(
                DB::raw('SUM(quantity) as totalOrders'),
                DB::raw('SUM(totalprice) as totalSales'),
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COALESCE(SUM(totalprice) / NULLIF(SUM(quantity),0), 0) as rataRataNilaiOrder')
            )
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($start) $query->whereDate('created_at', '>=', $start);
        if ($end)   $query->whereDate('created_at', '<=', $end);

        $results = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.report.salesreport', compact('results', 'start', 'end'));
    }

    // ===========================
    // ANALISIS STOK
    // ===========================
    public function inventoryPage()
{
    $stock = Product::select(
            'products.id as product_id',
            'products.name as nama_produk',
            'products.qty as stok_awal',
            DB::raw('0 as terjual'),
            DB::raw('products.qty as sisa_stok'),
            'categories.name as kategori'
        )
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->orderBy('products.id')
        ->get();

    return view('admin.report.inventory', compact('stock'));
}


public function productAnalysis(Request $request)
{
    $start = $request->start_date;
    $end   = $request->end_date;

    $stock = Product::select(
            'products.id as product_id',
            'products.name as nama_produk',
            'products.qty as stok_awal',
            DB::raw('COALESCE(SUM(orders.quantity),0) as terjual'),
            DB::raw('products.qty - COALESCE(SUM(orders.quantity),0) as sisa_stok'),
            'categories.name as kategori'
        )
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('orders', function ($join) use ($start, $end) {
            $join->on('products.id', '=', 'orders.product_id')
                 ->whereIn('orders.status', ['2', '3']);

            if ($start) {
                $join->whereDate('orders.created_at', '>=', $start);
            }

            if ($end) {
                $join->whereDate('orders.created_at', '<=', $end);
            }
        })
        ->groupBy(
            'products.id',
            'products.name',
            'products.qty',
            'categories.name'
        )
        ->orderBy('products.id')
        ->get();

    return view('admin.report.inventory', compact('stock', 'start', 'end'));
}








    // ===========================
    // RINGKASAN PEMBELIAN SUPPLIER
    // ===========================
    public function supplierPurchasePage()
    {
        $supplierPurchase = Supplier::select(
                'suppliers.name',
                'suppliers.contact',
                DB::raw('SUM(purchases.total_amount) as total_pembelian'),
                DB::raw('SUM(purchases.paid_amount) as total_dibayar'),
                DB::raw('SUM(purchases.due_amount) as total_tunggakan'),
                DB::raw('DATE(purchases.created_at) as tanggal')
            )
            ->leftJoin('purchases', 'suppliers.id', '=', 'purchases.supplier_id')
            ->groupBy(
                'tanggal',
                'suppliers.id',
                'suppliers.name',
                'suppliers.contact'
            )
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.report.supplierpurchase', compact('supplierPurchase'));
    }

    public function supplierPurchase(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = Supplier::select(
                'suppliers.name',
                'suppliers.contact',
                DB::raw('SUM(purchases.total_amount) as total_pembelian'),
                DB::raw('SUM(purchases.paid_amount) as total_dibayar'),
                DB::raw('SUM(purchases.due_amount) as total_tunggakan'),
                DB::raw('DATE(purchases.created_at) as tanggal')
            )
            ->leftJoin('purchases', 'suppliers.id', '=', 'purchases.supplier_id');

        if ($start) $query->whereDate('purchases.created_at', '>=', $start);
        if ($end)   $query->whereDate('purchases.created_at', '<=', $end);

        $supplierPurchase = $query
            ->groupBy(
                'tanggal',
                'suppliers.id',
                'suppliers.name',
                'suppliers.contact'
            )
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.report.supplierpurchase', compact('supplierPurchase', 'start', 'end'));
    }

    // ===========================
    // DETAIL PEMBELIAN
    // ===========================
    public function purchasedetailsPage()
    {
        $details = Supplier::select(
                'suppliers.name as supplier',
                'ingredients.name as nama_bahan',
                'ingredients.cost_price as harga_modal',
                'ingredients.unit as satuan',
                'purchase__items.quantity as jumlah',
                'purchase__items.total_price as total_harga',
                DB::raw('DATE(purchases.created_at) as tanggal')
            )
            ->leftJoin('purchases', 'suppliers.id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase__items', 'purchases.id', '=', 'purchase__items.purchase_id')
            ->leftJoin('ingredients', 'purchase__items.ingredient_id', '=', 'ingredients.id')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.report.detailpurchase', compact('details'));
    }

    public function purchaseDetails(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = Supplier::select(
                'suppliers.name as supplier',
                'ingredients.name as nama_bahan',
                'ingredients.cost_price as harga_modal',
                'ingredients.unit as satuan',
                'purchase__items.quantity as jumlah',
                'purchase__items.total_price as total_harga',
                DB::raw('DATE(purchases.created_at) as tanggal')
            )
            ->leftJoin('purchases', 'suppliers.id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase__items', 'purchases.id', '=', 'purchase__items.purchase_id')
            ->leftJoin('ingredients', 'purchase__items.ingredient_id', '=', 'ingredients.id');

        if ($start) $query->whereDate('purchases.created_at', '>=', $start);
        if ($end)   $query->whereDate('purchases.created_at', '<=', $end);

        $details = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.report.detailpurchase', compact('details', 'start', 'end'));
    }

    // ===========================
    // LAPORAN ASET
    // ===========================
    public function assetPage()
    {
        $results = Asset::with(['category', 'assignedUser'])
            ->orderBy('purchase_date', 'desc')
            ->get();

        return view('admin.report.assetreport', compact('results'));
    }

    public function assetReport(Request $request)
    {
        $query = Asset::with(['category', 'assignedUser']);

        if ($request->start_date) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $results = $query->orderBy('purchase_date', 'desc')->get();

        return view('admin.report.assetreport', compact('results'));
    }

    // ===========================
    // LAPORAN FEEDBACK
    // ===========================
    public function feedbackPage()
    {
        $feedback = Review::select(
                'reviews.name',
                'reviews.rating',
                'reviews.subject',
                'reviews.created_at'
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.report.feedback', compact('feedback'));
    }

    public function feedbackReport(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = Review::select(
            'reviews.name',
            'reviews.rating',
            'reviews.subject',
            'reviews.created_at'
        );

        if ($start) $query->whereDate('reviews.created_at', '>=', $start);
        if ($end)   $query->whereDate('reviews.created_at', '<=', $end);

        $feedback = $query->orderBy('created_at', 'desc')->get();

        return view('admin.report.feedback', compact('feedback', 'start', 'end'));
    }
}
