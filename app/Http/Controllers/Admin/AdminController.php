<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, transaksi, harga, LaundrySetting};
use Auth;
use Rupiah;
use DB;
use Session;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Halaman admin
    public function adm()
    {
        $adm = User::where('auth', 'Admin')->get();
        return view('modul_admin.pengguna.admin', compact('adm'));
    }

    // Tambah dan Data Harga
    public function dataharga()
    {
        // Ambil semua data harga tanpa memeriksa karyawan
        $harga = harga::orderBy('id', 'DESC')->get(); // Mengambil data harga tanpa 'user_id' atau join dengan 'users'

        return view('modul_admin.laundri.harga', compact('harga'));
    }

    // Proses Simpan Harga
    public function hargastore(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'harga' => 'required',
            'hari'  => 'required',
        ]);

        $addharga = new harga();
        // Tidak perlu lagi menyimpan 'user_id', karena sudah dihapus dari tabel harga
        $addharga->jenis = $request->jenis;
        $addharga->kg = 1000; // satuan gram
        $addharga->harga = $request->harga;
        $addharga->hari = $request->hari;
        $addharga->status = 1; // aktif
        $addharga->save();

        Session::flash('success', 'Tambah Data Harga Berhasil');
        return redirect('data-harga');
    }

    // Proses edit harga
    public function hargaedit(Request $request)
    {
        $request->validate([
            'id_harga' => 'required|exists:hargas,id', // Pastikan id valid
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'hari' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $harga = harga::findOrFail($request->id_harga);
        $harga->update([
            'jenis' => $request->jenis,
            'kg' => $request->kg,
            'harga' => $request->harga,
            'hari' => $request->hari,
            'status' => $request->status,
        ]);

        Session::flash('success', 'Edit Data Harga Berhasil');
        return redirect('data-harga');
    }

    // Laporan
    public function jmlTransaksi(Request $request)
    {
        $jml = User::where('auth', 'Customer')->select(DB::raw('t.id, t.nama, t.alamat, t.kelamin, t.no_telp, a.kg'))
            ->from(DB::raw('(SELECT * from customers order by created_at DESC) t'))
            ->leftJoin('transaksis as a', 'a.customer_id', '=', 't.id')
            ->groupBy('t.id')
            ->get();

        return view('modul_admin.customer.jmltransaksi', compact('jml'));
    }

    // Data Finance Cabang
    public function finance(Request $request)
    {
        $all = transaksi::where('status_payment', 'Success')->sum('harga_akhir');
        $hari = transaksi::where('status_payment', 'Success')
            ->where('tgl', Carbon::now()->day)
            ->where('bulan', Carbon::now()->month)
            ->where('tahun', Carbon::now()->year)
            ->sum('harga_akhir');

        $bulan = transaksi::where('status_payment', 'Success')
            ->where('bulan', Carbon::now()->month)
            ->where('tahun', Carbon::now()->year)
            ->sum('harga_akhir');

        $tahun = transaksi::where('status_payment', 'Success')
            ->where('tahun', Carbon::now()->year)
            ->sum('harga_akhir');

        // Variabel untuk chart
        $ny = transaksi::where('status_payment', 'Success')
            ->where('tahun', Carbon::now()->year)
            ->count(); // Total transaksi tahun ini

        $nm = transaksi::where('status_payment', 'Success')
            ->where('bulan', Carbon::now()->month)
            ->where('tahun', Carbon::now()->year)
            ->count(); // Total transaksi bulan ini

        $nd = transaksi::where('status_payment', 'Success')
            ->where('tgl', Carbon::now()->day)
            ->where('bulan', Carbon::now()->month)
            ->where('tahun', Carbon::now()->year)
            ->count(); // Total transaksi hari ini

        $kg = transaksi::sum('kg');
        $transaksi = transaksi::get();
        $user = transaksi::select('customer_id')->groupBy('customer_id')->get();

        return view('modul_admin.finance.cabang', compact('all', 'hari', 'bulan', 'tahun', 'kg', 'transaksi', 'user', 'ny', 'nm', 'nd'));
    }

    // Profile
    public function profile()
    {
        $profile = User::where('id', Auth::id())->first();
        return view('modul_admin.setting.profile', compact('profile'));
    }

    // Proses edit profile
    public function edit_profile(Request $request)
    {
        $profile = User::find($request->id_profile);
        $profile->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        Session::flash('success', 'Update Profile Berhasil');
        return $profile;
    }
}
