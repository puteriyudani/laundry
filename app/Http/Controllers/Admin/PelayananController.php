<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Karyawan, Transaksi, User};
use Mail;
use carbon\carbon;
use Session;
use App\Notifications\{OrderSelesai};

class PelayananController extends Controller

{
    public function index()
    {
        $order = Transaksi::with(['price', 'karyawan'])->orderBy('id', 'DESC')->get();
        $karyawans = Karyawan::all(); // Assuming the Karyawan model is set up
        return view('modul_admin.transaksi.order', compact('order', 'karyawans'));
    }

    public function listcs()
    {
        $customer = User::where('auth', 'Customer')->orderBy('id', 'DESC')->get(); // Menghapus user_id pada query
        return view('modul_admin.transaksi.customer', compact('customer'));
    }

    // Tambah Customer
    public function listcsadd()
    {
        return view('modul_admin.transaksi.addcustomer');
    }

    // Proses Tambah Customer
    // Tambah Customer
    public function addcs(Request $request)
    {
        $request->validate([
            'nama'           => 'required|unique:customers|max:25',
            'email_customer' => 'required|unique:customers',
            'alamat'         => 'required',
            'kelamin'        => 'required',
            'no_telp'        => 'required|unique:customers',
        ]);

        // Buat instance baru dari User
        $addplg = new User();
        $addplg->auth = 'Customer'; // Tetapkan auth sebagai 'Customer'
        $addplg->nama = $request->nama;
        $addplg->email = $request->email_customer; // Gunakan kolom email (bukan email_customer) sesuai konvensi Laravel
        $addplg->alamat = $request->alamat;
        $addplg->kelamin = $request->kelamin;
        $addplg->no_telp = $request->no_telp;
        $addplg->save();

        Session::flash('success', 'Customer Berhasil Ditambah !');
        return redirect('list-customer');
    }

    // Proses Ubah Status Order
    public function ubahstatusorder(Request $request)
    {
        $statusorder = transaksi::find($request->id);
        $statusorder->update([
            'status_order' => $request->status_order,
        ]);
        if ($statusorder->status_order == 'Done') {

            // Cek email notif
            if (setNotificationEmail(1) == 1) {

                // Menyiapkan data
                $email = $statusorder->email_customer;
                $data = array(
                    'invoice' => $statusorder->invoice,
                    'customer' => $statusorder->customer,
                    'tgl_transaksi' => $statusorder->tgl_transaksi,
                );

                // Kirim Email
                Mail::send('karyawan.email.selesai', $data, function ($mail) use ($email, $data) {
                    $mail->to($email, 'no-replay')
                        ->subject("E-Laundry - Laundry Selesai");
                    $mail->from('laundri.dev@gmail.com');
                });
            }

            // Cek status notif untuk telegram
            if (setNotificationTelegramFinish(1) == 1) {
                $statusorder->notify(new OrderSelesai());
            }

            Session::flash('success', 'Status Laundry Berhasil Diubah !');
        }
    }

    // Proses Ubah Status Pembayaran
    public function ubahstatusbayar(Request $request)
    {
        $statusbayar = transaksi::find($request->id);
        $statusbayar->update([
            'status_payment' => $request->status_payment,
        ]);
        Session::flash('success', 'Status Pembayaran Berhasil Diubah !');
        return $statusbayar;
    }

    // Proses Ubah Status Diambil
    public function ubahstatusambil(Request $request)
    {
        $statusbayar = transaksi::find($request->id);
        $statusbayar->update([
            'tgl_ambil' => Carbon::today(),
            'status_order' => 'Delivery'
        ]);
        if ($statusbayar->status_order == 'Delivery') {
            // Cek email notif
            if (setNotificationEmail(1) == 1) {
                // Menyiapkan data
                $email = $statusbayar->email_customer;
                $data = array(
                    'invoice' => $statusbayar->invoice,
                    'customer' => $statusbayar->customer,
                    'tgl_transaksi' => $statusbayar->tgl_transaksi,
                    'tgl_ambil' => $statusbayar->tgl_ambil,
                );

                // Kirim Email
                Mail::send('karyawan.email.diambil', $data, function ($mail) use ($email, $data) {
                    $mail->to($email, 'no-replay')
                        ->subject("E-Laundry - Laundry Sudah Diambil");
                    $mail->from('laundri.dev@gmail.com');
                });
            }
            Session::flash('success', 'Status Laundry Berhasi Diubah !');
        }
    }

    public function updateKaryawanAndCatatan(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'karyawan_id'  => 'required|exists:karyawans,id',
            'catatan_admin' => 'nullable|string|max:255'
        ]);

        $transaksi = Transaksi::find($request->transaksi_id);
        $transaksi->update([
            'karyawan_id' => $request->karyawan_id,
            'catatan_admin' => $request->catatan_admin,
        ]);

        Session::flash('success', 'Karyawan dan Catatan Berhasil Diperbarui!');
        return redirect()->back();
    }
}
