<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Harga;
use App\Models\Transaksi;
use App\Notifications\OrderMasuk;
use Carbon\Carbon;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan pengguna sudah login
    }

    public function index()
    {
        // Ambil data transaksi hanya untuk customer yang sedang login
        $order = Transaksi::where('customer_id', Auth::user()->id)->get();

        return view('customer.transaksi.index', compact('order'));
    }

    public function addorders()
    {
        // Ambil pengguna yang sedang login
        $currentUser = Auth::user();

        // Pastikan hanya pelanggan yang dapat mengakses
        if ($currentUser->auth !== 'Customer') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil harga aktif
        $harga = Harga::where('status', 1)->get();

        // Nomor Form otomatis (berdasarkan pengguna login)
        $y = date('Y');
        $number = mt_rand(1000, 9999);
        $newID = $number . $currentUser->id . $y; // Tambahkan ID pengguna login
        $tgl = date('d-m-Y');

        // Periksa apakah ada harga aktif
        $cek_harga = Harga::where('status', 1)->first();

        // Kirimkan nilai harga jika ditemukan, atau 0 jika tidak ada harga aktif
        $harga_value = $cek_harga ? $cek_harga->harga : 0;

        // Kirim data ke view (hanya untuk user login)
        return view('customer.transaksi.addorder', compact(
            'currentUser',
            'newID',
            'cek_harga',
            'harga',
            'harga_value'
        ));
    }

    // Proses simpan order
    public function store(Request $request)
    {
        $request->validate([
            'kg'                => 'required|regex:/^[0-9.]+$/',
            'hari'              => 'required',
            'harga_id'          => 'required|exists:hargas,id',  // pastikan harga_id valid
            'jenis_pembayaran'  => 'required'
        ]);

        // Buat instance baru transaksi
        $order = new Transaksi();
        $order->invoice         = $request->invoice;
        $order->tgl_transaksi   = Carbon::now()->format('d-m-Y'); // Tanggal transaksi
        $order->status_payment  = 'Pending'; // Nilai otomatis untuk status_payment
        $order->harga_id        = $request->harga_id;
        $order->customer_id     = Auth::id(); // ID pengguna yang login
        $order->customer        = Auth::user()->name; // Nama pengguna yang login
        $order->email_customer  = Auth::user()->email; // Email pengguna yang login
        $order->hari            = $request->hari;
        $order->kg              = $request->kg;

        // Ambil harga dari model harga berdasarkan harga_id
        $hargaObj = Harga::find($request->harga_id);
        if (!$hargaObj) {
            return redirect()->back()->with('error', 'Harga tidak ditemukan');
        }

        // Perhitungan harga
        $hitung = $order->kg * $hargaObj->harga; // Menghitung total harga berdasarkan kg dan harga

        // Diskon jika ada
        if ($request->disc != NULL) {
            $disc = ($hitung * $request->disc) / 100; // Hitung diskon
            $order->harga_akhir = $hitung - $disc; // Total harga setelah diskon
        } else {
            $order->harga_akhir = $hitung; // Total harga tanpa diskon
        }

        $order->harga = $hargaObj->harga; // Harga satuan
        $order->jenis_pembayaran = $request->jenis_pembayaran;
        $order->tgl = Carbon::now()->day;
        $order->bulan = Carbon::now()->month;
        $order->tahun = Carbon::now()->year;

        // Simpan order ke database
        $order->save();

        if ($order) {
            // Notification Telegram
            if (setNotificationTelegramIn(1) == 1) {
                $order->notify(new OrderMasuk());
            }

            // Notification Email
            if (setNotificationEmail(1) == 1) {
                $email = $order->email_customer;
                $data = [
                    'invoice' => $order->invoice,
                    'customer' => $order->customer,
                    'tgl_transaksi' => $order->tgl_transaksi,
                ];

                Mail::send('customer.email.email', $data, function ($mail) use ($email) {
                    $mail->to($email, 'no-reply')
                        ->subject("E-Laundry - Nomor Invoice");
                    $mail->from('laundri.dev@gmail.com');
                });
            }

            Session::flash('success', 'Order Berhasil Ditambah!');
            return redirect('transaksi-customer');
        }
    }

    public function listharga(Request $request)
    {
        $list_harga = Harga::select('id', 'harga')
            ->where('id', $request->id)
            ->get(); // Menghapus filter user_id
        $select = '';
        $select .= '
                <div class="form-group has-success">
                <label for="id" class="control-label">Harga</label>
                <select id="harga" class="form-control" name="harga" value="harga">
                ';
        foreach ($list_harga as $studi) {
            $select .= '<option value="' . $studi->harga . '">' . $studi->harga . '</option>';
        }
        $select .= '
                </select>
                </div>
                </div>';
        return $select;
    }

    public function listhari(Request $request)
    {
        $list_jenis = Harga::select('id', 'hari')
            ->where('id', $request->id)
            ->get(); // Menghapus filter user_id
        $select = '';
        $select .= '
                <div class="form-group has-success">
                <label for="id" class="control-label">Pilih Hari</label>
                <select id="hari" class="form-control" name="hari" value="hari">
                ';
        foreach ($list_jenis as $hari) {
            $select .= '<option value="' . $hari->hari . '">' . $hari->hari . '</option>';
        }
        $select .= '
                </select>
                </div>
                </div>';
        return $select;
    }
}
