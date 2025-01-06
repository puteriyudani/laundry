<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{transaksi, customer, harga};

class LaporanController extends Controller
{
    // Halaman Laporan
    public function laporan()
    {
        // Mengambil semua transaksi dengan status 'Done' atau 'Delivery'
        $laporan = transaksi::whereIn('status_order', ['Done', 'Delivery'])->get();

        return view('modul_admin.laporan.index', compact('laporan'));
    }
}
