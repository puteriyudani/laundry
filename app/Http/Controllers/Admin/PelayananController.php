<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{transaksi, customer, harga};
use Auth;
use PDF;
use Mail;
use carbon\carbon;
use Alert;
use Session;
use App\Notifications\{OrderMasuk, OrderSelesai};

class PelayananController extends Controller

{

    public function index()
    {
        $order = transaksi::with('price')->orderBy('id', 'DESC')->get(); // Menghapus user_id pada query
        return view('modul_admin.transaksi.order', compact('order'));
    }

    // Proses simpan order
    public function store(Request $request)
    {
        $request->validate([
            'status_payment'    => 'required',
            'kg'                => 'required|regex:/^[0-9.]+$/',
            'hari'              => 'required',
            'harga_id'          => 'required|exists:hargas,id',  // pastikan harga_id valid
            'jenis_pembayaran'  => 'required'
        ]);

        $order = new transaksi();
        $order->invoice         = $request->invoice;
        $order->tgl_transaksi   = Carbon::now()->format('d-m-Y');  // Fixed date handling
        $order->status_payment  = $request->status_payment;
        $order->harga_id        = $request->harga_id;
        $order->customer_id     = $request->customer_id;
        $order->customer        = namaCustomer($order->customer_id);  // Assuming this function is defined
        $order->email_customer  = email_customer($order->customer_id); // Assuming this function is defined
        $order->hari            = $request->hari;
        $order->kg              = $request->kg;

        // Ambil harga dari model harga berdasarkan harga_id
        $hargaObj = harga::find($request->harga_id);
        if (!$hargaObj) {
            // Tangani jika harga tidak ditemukan
            return redirect()->back()->with('error', 'Harga tidak ditemukan');
        }

        // Pastikan harga ditemukan
        if ($hargaObj) {
            $hitung = $order->kg * $hargaObj->harga;  // Menggunakan harga dari model harga

            // Diskon jika ada
            if ($request->disc != NULL) {
                $disc = ($hitung * $order->disc) / 100;
                $order->harga_akhir = $hitung - $disc;
            } else {
                $order->harga_akhir = $hitung;
            }

            // Set harga untuk order
            $order->harga = $hargaObj->harga;  // Menggunakan harga yang benar dari model
        } else {
            // Jika harga tidak ditemukan, beri pesan error
            Session::flash('error', 'Harga tidak ditemukan.');
            return back();
        }

        $order->jenis_pembayaran = $request->jenis_pembayaran;
        $order->tgl = Carbon::now()->day;
        $order->bulan = Carbon::now()->month;
        $order->tahun = Carbon::now()->year;
        $order->save();

        if ($order) {
            // Notification Telegram
            if (setNotificationTelegramIn(1) == 1) {
                $order->notify(new OrderMasuk());
            }

            // Notification email
            if (setNotificationEmail(1) == 1) {
                // Menyiapkan data Email
                $email = $order->email_customer;
                $data = array(
                    'invoice' => $order->invoice,
                    'customer' => $order->customer,
                    'tgl_transaksi' => $order->tgl_transaksi,
                );

                // Kirim Email
                Mail::send('karyawan.email.email', $data, function ($mail) use ($email, $data) {
                    $mail->to($email, 'no-replay')
                        ->subject("E-Laundry - Nomor Invoice");
                    $mail->from('laundri.dev@gmail.com');
                });
            }

            Session::flash('success', 'Order Berhasil Ditambah !');
            return redirect('pelayanan');
        }
    }

    public function listcs()
    {
        $customer = customer::orderBy('id', 'DESC')->get(); // Menghapus user_id pada query
        return view('modul_admin.transaksi.customer', compact('customer'));
    }

    public function addorders()
    {
        // Ambil data customer dan harga aktif
        $customer = customer::all();
        $harga = harga::where('status', 1)->get();

        // Nomor Form otomatis
        $y = date('Y');
        $number = mt_rand(1000, 9999);
        $newID = $number . Auth::user()->id . '' . $y;
        $tgl = date('d-m-Y');

        // Periksa apakah ada harga aktif
        $cek_harga = harga::where('status', 1)->first();
        $cek_customer = customer::count();

        // Kirimkan nilai harga jika ditemukan, atau 0 jika tidak ada harga aktif
        $harga_value = $cek_harga ? $cek_harga->harga : 0;

        return view('modul_admin.transaksi.addorder', compact('customer', 'newID', 'cek_harga', 'cek_customer', 'harga', 'harga_value'));
    }

    public function listharga(Request $request)
    {
        $list_harga = harga::select('id', 'harga')
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
        $list_jenis = harga::select('id', 'hari')
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

    public function addcs(Request $request)
    {
        $request->validate([
            'nama'                => 'required|unique:customers|max:25',
            'email_customer'      => 'required|unique:customers',
            'alamat'              => 'required',
            'kelamin'             => 'required',
            'no_telp'             => 'required|unique:customers',
        ]);

        $addplg = new customer();
        $addplg->nama = $request->nama;
        $addplg->email_customer = $request->email_customer;
        $addplg->alamat = $request->alamat;
        $addplg->kelamin = $request->kelamin;
        $addplg->no_telp = $request->no_telp;
        $addplg->save(); // Menghapus user_id

        Session::flash('success', 'Customer Berhasil Ditambah !');

        return redirect('list-customer');
    }
}
