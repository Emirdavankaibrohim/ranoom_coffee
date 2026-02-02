<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\Purchase_Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    //Route to Supplier List Page
    public function index(){
        $suppliers = Supplier::when(request('searchKey'), function ($query) {
                        $query->where(function ($q) {
                            $q->where('suppliers.name', 'like', '%' . request('searchKey') . '%');
                        });
                    })->paginate(6);

        return view('admin.supplier.supplierList', compact('suppliers'));
    }


    //Route to add Supplier
    public function createSupplierPage(){
        return view('admin.supplier.createSupplier');
    }


    //Route to create
    public function createSupplier(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'contact' =>  'required',
            'address' => 'required',
            'status' => 'required',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')->with('Berhasil', 'Pemasok Berhasil Ditambahkan!');
    }


    //Route to edit page by id
    public function editSupplier($id){

        $totalDueAmount = Purchase::where('supplier_id',$id)->sum('due_amount');

        $supplierinfo = Supplier::findOrFail($id);

        return view('admin.supplier.editSupplier', [
            'supplierinfo' => $supplierinfo,
            'totalDueAmount' => $totalDueAmount
        ]);
    }


    //update Supplier Info
    public function updateSupplier(Request $request, $id){

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'name'        => 'required',
            'contact'     => 'required',
            'address'     => 'required',
            'status'      => 'required',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->name    = $validated['name'];
        $supplier->contact = $validated['contact'];
        $supplier->address = $validated['address'];
        $supplier->status  = $validated['status'];
        $supplier->save();

        $remainingPaid = $validated['paid_amount'];

        $purchases = Purchase::where('supplier_id',$id)
                             ->where('due_amount','>',0)
                             ->orderBy('created_at')
                             ->get();

        foreach($purchases as $purchase){
            if($remainingPaid <= 0) break;

            if($purchase->due_amount <= $remainingPaid){
                $remainingPaid -= $purchase->due_amount;
                $purchase->paid_amount += $purchase->due_amount;
                $purchase->due_amount = 0;
            } else {
                $purchase->paid_amount += $remainingPaid;
                $purchase->due_amount -= $remainingPaid;
                $remainingPaid = 0;
            }

            if($purchase->due_amount == 0){
                $purchase->payment_status = 'Lunas';
            } elseif ($purchase->paid_amount == 0){
                $purchase->payment_status = 'Belum Dibayar';
            } else {
                $purchase->payment_status = 'Sebagian';
            }

            $purchase->save();
        }

        return redirect()->route('supplier.index')->with('Berhasil', 'Pemasok Berhasil Diperbarui!');
    }


    public function deleteSupplier($id){

        $supplier = Supplier::where('id', $id)
                            ->where('status', 'Inactive')
                            ->firstOrFail();

        $supplier->delete();

        return redirect()->route('supplier.index')->with('Berhasil', 'Pemasok Berhasil Dihapus');
    }


    //Purchase Page
   public function purchasePage(){
    $suppliers = Supplier::whereIn('status', ['Active', 'Aktif'])->get();

    return view('admin.supplier.purchase', compact('suppliers'));
}


    public function addItem(Request $request)
    {
        $validatedItem = $request->validate([
            'cost_price'        => 'required|numeric|min:0',
            'quantity'          => 'required|numeric|min:1',
            'ingredient_name'   => 'required|string|max:255',
            'unit'              => 'required|string|max:50',
        ]);

        $total_price = $validatedItem['cost_price'] * $validatedItem['quantity'];

        $purchaseItems = session()->get('purchase_items', []);

        $purchaseItems[] = [
            'name' => $validatedItem['ingredient_name'],
            'unit' => $validatedItem['unit'],
            'quantity' => $validatedItem['quantity'],
            'cost_price' => $validatedItem['cost_price'],
            'total_price' => $total_price,
        ];

        $total_amount = array_sum(array_column($purchaseItems, 'total_price'));

        session([
            'purchase_items' => $purchaseItems,
            'total_amount' => $total_amount
        ]);

        return redirect()->back();
    }


    public function storePurchase(Request $request)
    {
        $purchaseItems = session()->get('purchase_items', []);

        if (empty($purchaseItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang ditambahkan!');
        }

        $ingredientIds = [];

        foreach ($purchaseItems as $item) {
            $ingredient = Ingredient::create([
                'name' => $item['name'],
                'unit' => $item['unit'],
                'cost_price' => $item['cost_price']
            ]);
            $ingredientIds[] = $ingredient->id;
        }

        $purchase = Purchase::create([
            'supplier_id'   => $request->supplier_id,
            'total_amount'  => session('total_amount'),
            'paid_amount'   => $request->paid_amount,
            'due_amount'    => session('total_amount') - $request->paid_amount,
            'payment_status'=> $request->payment_status,
        ]);

        foreach ($purchaseItems as $index => $item) {
            Purchase_Item::create([
                'purchase_id'   => $purchase->id,
                'ingredient_id' => $ingredientIds[$index],
                'quantity'      => $item['quantity'],
                'cost_price'    => $item['cost_price'],
                'total_price'   => $item['total_price'],
            ]);
        }

        session()->forget(['purchase_items', 'total_amount']);

        return redirect()->route('purchasePage')->with('Berhasil', 'Pembelian berhasil ditambahkan!');
    }


    public function removeItem($index){
        $purchaseItems = session()->get('purchase_items', []);

        if (isset($purchaseItems[$index])) {
            unset($purchaseItems[$index]);
            session(['purchase_items' => array_values($purchaseItems)]);
        }

        return redirect()->back();
    }
}
