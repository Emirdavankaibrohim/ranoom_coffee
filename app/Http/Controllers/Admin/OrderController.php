<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use App\Models\PaymentRecord;
use App\Models\Product;
use App\Models\TaxSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ORDER LIST
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'orderCode' => 'required',
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'orderType' => 'required',
        ]);

        return view('user.payment', [
            'orderCode' => $request->orderCode,
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'orderType' => $request->orderType,
            'totalAmount' => $request->totalAmount, // Ensure this is passed from Cart
        ]);
    }

    public function orderlist()
    {
        $today         = Carbon::today();
        $user = Auth::user();
        $query = Order::select(
            'orders.order_code',
            'orders.product_id',
            'orders.status',
            'orders.customer_name',
            'orders.customer_phone',
            'orders.created_at'
        );

        // Barista (Chef) only sees Approved orders (Status 2)
        if ($user->role === 'chef') {
            $query->where('orders.status', 2);
        } else {
             // Admin/Cashier see all active orders (or specific status if needed)
             // For now, let's show all valid orders (1, 2, 3, 4)
             // But existing code had where status 1. We change this.
             $query->whereIn('orders.status', [1, 2, 3, 4]);
        }

        $summaryOrders = $query->get();

        $groupedOrders = $summaryOrders->groupBy('order_code');

        $currentPage = request()->get('page', 1);
        $perPage     = 5;

        $paginatedGroupedOrders = new LengthAwarePaginator(
            $groupedOrders->forPage($currentPage, $perPage),
            $groupedOrders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.order.orderlist', [
            'groupedOrders' => $paginatedGroupedOrders,
            'summaryOrders' => $summaryOrders,
        ]);
    }

    // ORDER DETAIL
    public function viewOrder($orderCode)
{
    $details = Order::select(
        'orders.user_id',
        'orders.product_id',
        'orders.status',
        'orders.size',
        'orders.notes',
        'orders.order_code',
        'orders.quantity as qty', // 👈 ALIAS
        'orders.created_at',
        'orders.order_type',
        'orders.customer_name',
        'orders.customer_phone',
        'products.image',
        'products.name',
        'product_sizes.price'
    )
    ->leftJoin('products', 'orders.product_id', '=', 'products.id')
    ->leftJoin('product_sizes', function ($join) {
        $join->on('products.id', '=', 'product_sizes.product_id')
             ->on('orders.size', '=', 'product_sizes.size');
    })
    ->where('orders.order_code', $orderCode)
    ->get();

    return view('admin.order.orderdetail', compact('details'));
}


    // UPDATE → ACCEPT / REJECT
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order_code' => 'required|string',
            'product_id' => 'required|integer',
            'size'       => 'required|string',
            'action'     => 'required|string|in:accept,reject',
        ]);

        $order = Order::where('order_code', $validated['order_code'])
            ->where('product_id', $validated['product_id'])
            ->where('size', $validated['size'])
            ->first();

        if ($order) {
            // Accept -> 2 (Cooking/Approved), Reject -> 4 (Rejected)
            $order->status = $validated['action'] === "accept" ? 2 : 4;
            $order->save();

            return redirect()
                ->route('order.viewOrder', ['orderCode' => $validated['order_code']])
                ->with('success', 'Order updated successfully.');
        }

        return redirect()
            ->route('order.viewOrder', ['orderCode' => $validated['order_code']])
            ->with('error', 'Order not found.');
    }

    // UPDATE COOKING STATUS → DONE
    public function updateCookingStatus(Request $request)
    {
        $validated = $request->validate([
            'order_code' => 'required|string',
            'product_id' => 'required|integer',
            'size'       => 'nullable|string'
        ]);

        $orderQuery = Order::where('order_code', $validated['order_code'])
            ->where('product_id', $validated['product_id']);

        if (!empty($validated['size'])) {
            $orderQuery->where('size', $validated['size']);
        }

        $order = $orderQuery->first();

        if (!$order) {
            return back()->with('error', 'Order item tidak ditemukan.');
        }

        if ($order->status != 2) {
            return back()->with('error', 'Item belum ACCEPT, tidak bisa DONE.');
        }

        $order->update(['status' => 3]);

        return back()->with('success', 'Status berhasil diubah menjadi DONE.');
    }

    // BOOKING PAGE
    public function bookingPage()

{
    $categories = Category::all();
    $cartOrder  = DB::table('carts')->select('orderCode')->distinct()->pluck('orderCode');

    $orderCode = session('orderCode'); // ambil dari session

    return view('admin.order.booking', [
        'categories' => $categories,
        'cartOrder'  => $cartOrder,
        'orderCode'  => $orderCode, // ⬅️ wajib
    ]);
}


    public function storeOrderCode(Request $request)
    {
        $request->session()->put('orderCode', $request->input('orderCode'));
        return response()->json(['status' => 'success']);
    }

    // PRODUCT LIST BY CATEGORY
    public function getProductsByCategory(Request $request)
    {
        $userId = optional(Auth::user())->id;
        $categories = Category::all();

        $selectedCategoryId = $request->query('categoryId');
        $orderCode = $request->query('orderCode', $request->session()->get('orderCode', 'N/A'));

        $productQuery = Product::query();

        if ($selectedCategoryId) {
            $productQuery->where('category_id', $selectedCategoryId);
        }

        if ($request->filled('searchKey')) {
            $productQuery->where('name', 'like', '%' . $request->searchKey . '%');
        }

        $products = $productQuery->get();

        $cartItemsCount = Cart::where('user_id', $userId)
            ->where('orderCode', $orderCode)
            ->select('product_id', 'size', DB::raw('SUM(qty) as total_quantity'))
            ->groupBy('product_id', 'size')
            ->get();

        $today     = Carbon::today();
        $discounts = Discount::select('discount_percentage', 'product_id')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        $discountedProducts    = [];
        $allDiscountPercentage = null;

        foreach ($discounts as $discount) {
            if ($discount->product_id) {
                $discountedProducts[$discount->product_id] = $discount->discount_percentage;
            } else {
                $allDiscountPercentage = $discount->discount_percentage;
            }
        }

        $cartItems = Product::selectRaw('
            IFNULL(
                IF(discounts.product_id IS NOT NULL,
                    product_sizes.price - (product_sizes.price * discounts.discount_percentage / 100),
                    product_sizes.price
                ), product_sizes.price
            ) as discountPrice,
            IFNULL(discounts.discount_percentage, 0) as discount_percentage,
            products.id,
            products.name,
            products.image,
            product_sizes.price,
            product_sizes.size,
            carts.qty as cart_qty,
            carts.id as cartId,
            carts.orderCode
        ')
            ->leftJoin('carts', 'products.id', '=', 'carts.product_id')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->leftJoin('product_sizes', function ($join) {
                $join->on('products.id', '=', 'product_sizes.product_id')
                    ->on('carts.size', '=', 'product_sizes.size');
            })
            ->where('carts.user_id', $userId)
            ->where('carts.orderCode', $orderCode)
            ->get();

        $smallestUnit = 10;
        $subTotal     = $cartItems->sum(fn($item) => $item->discountPrice * $item->cart_qty);

        $taxSetting = TaxSetting::first();
        $taxRate    = $taxSetting->tax_rate ?? 0;
        $taxAmount  = ceil((($subTotal * $taxRate) / 100) / $smallestUnit) * $smallestUnit;

        $orderType   = $request->query('orderType', '');
        $deliveryFee = 0;

        if ($orderType === 'delivery') {
            $locationId  = $request->query('deliveryLocation');
            $location    = \App\Models\DeliveryFees::find($locationId);
            $deliveryFee = $location ? $location->fees : 0;
        }

        $total = ceil(($subTotal + $taxAmount + $deliveryFee) / $smallestUnit) * $smallestUnit;

        foreach ($products as $product) {
            $product->sizes = DB::table('product_sizes')
                ->where('product_id', $product->id)
                ->get(['size', 'price']);
        }

        return view('admin.order.booking', [
            'productbyCategory'  => $products,
            'categories'         => $categories,
            'discount'           => $discounts ?? 0,
            'selectedCategoryId' => $selectedCategoryId,
            'orderCode'          => $orderCode,
            'orderType'          => $orderType,
            'deliveryFee'        => $deliveryFee,
            'total'              => $total,
            'cartItemsCount'     => $cartItemsCount,
            'cartItems'          => $cartItems,
            'subTotal'           => $subTotal,
            'taxRate'            => $taxRate,
            'taxAmount'          => $taxAmount,
        ]);
    }

    // ADD TO CART
   public function addItems(Request $request)
{
    $request->validate([
        'orderCode'  => 'required|string',
        'product_id' => 'required|exists:products,id',
        'qty'        => 'required|integer|min:1',
        'size'       => 'required|in:Small,Medium,Large',
        'notes'      => 'nullable|string',
    ]);

    $cart = Cart::where([
        'user_id'    => auth()->id(),
        'orderCode'  => $request->orderCode,
        'product_id' => $request->product_id,
        'size'       => $request->size,
    ])->first();

    if ($cart) {
        // 👉 INI YANG BIKIN SUBTOTAL NAIK
        $cart->qty += $request->qty;
        $cart->notes = $request->notes;
        $cart->save();
    } else {
        Cart::create([
            'user_id'    => auth()->id(),
            'orderCode'  => $request->orderCode,
            'product_id' => $request->product_id,
            'size'       => $request->size,
            'qty'        => $request->qty,
            'notes'      => $request->notes,
        ]);
    }

    return back()->with('success', 'Produk berhasil masuk keranjang');
}



    // CLEAR CART
    public function clearCart(Request $request)
    {
        Cart::where('orderCode', $request->orderCode)->delete();
        return back()->with('success', 'Successfully cleared the cart items.');
    }

    public function getOrderCodes()
    {
        return response()->json(
            DB::table('carts')->select('orderCode')->distinct()->pluck('orderCode')
        );
    }

    // CONFIRM ORDER → CREATE PAYMENT RECORD
    public function orderConfirm(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Ambil cart sesuai orderCode
            $cartItems = Cart::where('orderCode', $request->orderCode)->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Cart kosong, tidak ada order yang bisa diproses.');
            }

            if ($request->orderCode === 'N/A' || empty($request->orderCode)) {
                return back()->with('error', 'Kode Pesanan tidak valid. Silakan buat pesanan baru.');
            }

            // 2. Simpan data order ke table orders (satu orderCode bisa punya banyak item)
            // Map order type dari string ke integer untuk konsistensi
            $orderTypeMap = [
                'take_away' => 1,
                'eat_in'    => 2,
                'delivery'  => 3,
            ];
            $mappedOrderType = $orderTypeMap[$request->orderType] ?? $request->orderType;

            foreach ($cartItems as $item) {
                Order::create([
                    'order_code' => $item->orderCode,
                    'user_id'    => $item->user_id,
                    'product_id' => $item->product_id,
                    'size'       => $item->size,
                    'quantity'   => $item->qty,
                    'notes'      => $item->notes,
                    'order_type' => $mappedOrderType,
                    'status'     => 1,  // Status 1: Pending Acceptance
                    'totalprice' => 0,  // bisa dihitung jika perlu
                    'customer_name' => $request->customerName,
                    'customer_phone' => $request->customerPhone,
                    'delivery_location_id' => $request->deliveryLocation, // Add validation if needed
                ]);
            }

        // 3. Logic pembayaran
        $paidAmount = $request->paymentMethod === 'cash'
            ? $request->cashReceived
            : $request->totalAmount;

        $changeAmount = $request->paymentMethod === 'cash'
            ? $request->changeDue
            : 0;

        // 4. Simpan payment record
        PaymentRecord::create([
            'order_code'     => $request->orderCode,
            'user_id'        => auth()->id(),
            'net_amount'     => $request->totalAmount,
            'paid_amount'    => $paidAmount,
            'change_amount'  => $changeAmount,
            'payment_method' => $request->paymentMethod,
            'status'         => 1,
        ]);

        // 5. Hapus cart
        Cart::where('orderCode', $request->orderCode)->delete();

        DB::commit();

        return redirect()->route('order.payment.success', $request->orderCode);

    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
    }
}


// PAYMENT SUCCESS PAGE
public function paymentSuccess($orderCode)
{
    return view('admin.order.payment-success', compact('orderCode'));
}
    // PRINT SLIP
    public function generatePaymentSlip(Request $request)
    {
        $orderCode = $request->orderCode;
        $paymentData = $this->getPaymentRecordData($orderCode);

        if (!$paymentData) {
            return response()->json(['error' => 'Payment Record not found'], 404);
        }

        return response(view('admin.order.payment-slip', $paymentData)->render());
    }

    public function printPaymentSlip($orderCode)
    {
        $data = $this->getPaymentRecordData($orderCode);

        if (!$data) {
            return response('Payment Record not found', 404);
        }

        return view('admin.order.payment-slip', [
            'records'     => $data['records'],
            'subTotalAmt' => $data['subTotalAmt'],
            'deliveryFee' => $data['deliveryFee'],
            'taxAmount'   => $data['taxAmount'],
        ]);
    }

    // PAYMENT RECORD PAGE
    public function paymentRecord()
    {
        return view('admin.order.payment-record', ['records' => collect()]);
    }

    public function searchRecord(Request $request)
    {
        $data = $this->getPaymentRecordData($request->searchKey);

        return response(
            view('admin.order.payment-record', [
                'records'      => $data['records'] ?? collect(),
                'subTotalAmt'  => $data['subTotalAmt'] ?? 0,
                'delivery_fee' => $data['deliveryFee'] ?? 0,
                'taxAmount'    => $data['taxAmount'] ?? 0,
            ])->render()
        );
    }

    // PAYMENT RECORD HELPER
    private function getPaymentRecordData($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            return null;
        }

        $smallestUnit = 10;

        $records = PaymentRecord::selectRaw('
            SUM(
                IFNULL(
                    IF(discounts.product_id IS NOT NULL,
                        product_sizes.price - (product_sizes.price * discounts.discount_percentage / 100),
                        product_sizes.price
                    ), 0
                ) * orders.quantity
            ) as total_price,
            products.name as ProductName,
            payment_records.user_id as CashierID,
            payment_records.created_at as Date,
            payment_records.net_amount,
            payment_records.paid_amount,
            payment_records.change_amount,
            IFNULL(discounts.discount_percentage, 0) as discount_percentage,
            payment_records.payment_method,
            orders.quantity,
            orders.totalprice,
            orders.order_code,
            product_sizes.price,
            product_sizes.size
        ')
        ->leftJoin('orders', 'payment_records.order_code', '=', 'orders.order_code')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
        ->leftJoin('product_sizes', function ($join) {
            $join->on('product_sizes.product_id', '=', 'products.id')
                 ->on('product_sizes.size', '=', 'orders.size');
        })
        ->where('payment_records.order_code', $orderCode)
        ->groupBy(
            'products.name',
            'payment_records.user_id',
            'payment_records.created_at',
            'payment_records.net_amount',
            'payment_records.paid_amount',
            'payment_records.change_amount',
            'discounts.discount_percentage',
            'payment_records.payment_method',
            'orders.quantity',
            'orders.totalprice',
            'orders.order_code',
            'product_sizes.price',
            'product_sizes.size'
        )
        ->get();

        $subTotalAmt = $records->sum('total_price');

        $taxRate   = optional(TaxSetting::first())->tax_rate ?? 0;
        $taxAmount = ceil((($subTotalAmt * $taxRate) / 100) / $smallestUnit) * $smallestUnit;

        $deliveryFee = 0;
        if (!empty($order->delivery_location_id)) {
            $deliveryFee = optional(\App\Models\DeliveryFees::find($order->delivery_location_id))->fees ?? 0;
        }

        return [
            'records'     => $records,
            'subTotalAmt' => $subTotalAmt,
            'deliveryFee' => $deliveryFee,
            'taxAmount'   => $taxAmount,
        ];
    }
}
