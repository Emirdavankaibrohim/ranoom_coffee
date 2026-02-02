<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'size'       => 'nullable|string', // size boleh kosong
            'notes'      => 'nullable|string',
        ]);

        // Jika size tidak dikirim → isi NULL
        $size = $request->size ?? null;

        Cart::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'qty'        => $request->qty,
            'size'       => $size,             // <- PENTING
            'orderCode'  => 'ORD-' . strtoupper(uniqid()),
            'notes'      => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
}
