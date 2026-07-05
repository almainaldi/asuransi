<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transaction;

class TransaksiController extends Controller
{
    public function index()
    {
        // Mengambil semua data customer untuk combobox
        $customers = Customer::all();
        // Mengambil data transaksi beserta relasi data customernya
        $transactions = Transaction::with('customer')->get();

        return view('transaksi.index', compact('customers', 'transactions'));
    }

    public function insert(Request $request)
    {
        $transaction = new Transaction();
        $transaction->customer_id = $request->customer_id;
        $transaction->product_name = $request->product_name;
        $transaction->quantity = $request->quantity;
        $transaction->price = $request->price;
        $transaction->save();

        return redirect()->route('transaksi.index');
    }

    public function delete($id)
    {
        Transaction::destroy($id);
        return redirect()->route('transaksi.index');
    }
}