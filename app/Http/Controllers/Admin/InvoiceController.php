<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\transaksi;
use Illuminate\Http\Request;
use Auth;

class InvoiceController extends Controller
{
    // Invoice
    public function invoicekar(Request $request)
    {
        // Mengambil data invoice
        $invoice = transaksi::selectRaw('transaksis.*,a.jenis')
            ->leftJoin('hargas as a', 'a.id', '=', 'transaksis.harga_id')
            ->where('transaksis.id', $request->id)
            ->where('transaksis.customer_id', Auth::user()->id)
            ->orderBy('transaksis.id', 'DESC')
            ->get();

        // Mengambil data pelanggan dan pengguna berdasarkan customer_id
        $data = transaksi::selectRaw('transaksis.*,a.name as nama,a.alamat,a.no_telp,a.kelamin,b.name as user_name,b.no_telp as no_telpc')
            ->leftJoin('users as a', 'a.id', '=', 'transaksis.customer_id')
            ->leftJoin('users as b', 'b.id', '=', 'transaksis.customer_id')
            ->where('transaksis.id', $request->id)
            ->where('transaksis.customer_id', Auth::user()->id)
            ->where('a.auth', 'Customer')
            ->orderBy('transaksis.id', 'DESC')
            ->first();

        // Jika data tidak ditemukan
        if (!$data) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        // Menampilkan view dengan data
        return view('modul_admin.laporan.invoice', compact('invoice', 'data'));
    }
}
