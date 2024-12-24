<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Transaksi};
use Rupiah;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil transaksi dengan relasi price tanpa melibatkan user_id
        $transaksi = Transaksi::with('price')
            ->orderBy('created_at','desc')
            ->get();

        return view('modul_admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Filter transaksi berdasarkan status atau parameter lainnya.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtertransaksi(Request $request)
    {
        // Menampilkan transaksi tanpa filter user_id, karena tidak ada lagi relasi dengan user_id
        if ($request->user_id == 'all') {
            $transaksi = Transaksi::with('price')
                ->orderBy('created_at','desc')
                ->get();
        } else {
            // Kondisi jika filter berdasarkan parameter lain selain user_id
            $transaksi = Transaksi::with('price')
                ->orderBy('created_at','desc')
                ->get();
        }

        $return = "";
        $no = 1;
        foreach ($transaksi as $item) {
            $return .= "<tr>
                <td>".$no."</td>
                <td>".$item->tgl_transaksi."</td>
                <td>".$item->customer."</td>
                <td>".$item->status_order."</td>
                <td>".$item->status_payment."</td>
                <td>".$item->price->jenis."</td>";
            $return .= "
                <input type='hidden' value='".$item->kg * $item->harga."'>
                <td>".Rupiah::getRupiah($item->kg * $item->harga)."</td>
            ";
            if ($item->status_order == "Delivery") {
                $return .= "<td><a href='invoice-customer/$item->id' class='btn btn-sm btn-success' style='color:white'>Invoice</a>
                    <a class='btn btn-sm btn-info' style='color:white'>Detail</a></td>";
            } elseif ($item->status_order == "Done") {
                $return .= "<td> <a href='invoice-customer/$item->id' class='btn btn-sm btn-success' style='color:white'>Invoice</a>
                    <a class='btn btn-sm btn-info' style='color:white'>Detail</a></td>";
            } elseif ($item->status_order == "Process") {
                $return .= "<td> <a href='invoice-customer/$item->id' class='btn btn-sm btn-success' style='color:white'>Invoice</a>
                    <a class='btn btn-sm btn-info' style='color:white'>Detail</a></td>";
            }
            $return .= "</td>
            </tr>";
            $no++;
        }
        return $return;
    }

    /**
     * Menampilkan detail invoice untuk transaksi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoice(Request $request)
    {
        $invoice = Transaksi::with('price')
            ->where('invoice', $request->invoice)
            ->orderBy('id','DESC')
            ->get();

        $dataInvoice = Transaksi::with('customers.users')
            ->where('invoice', $request->invoice)
            ->first();

        return view('modul_admin.transaksi.invoice', compact('invoice', 'dataInvoice'));
    }

    /**
     * Menampilkan form untuk membuat transaksi baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan transaksi yang baru dibuat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Menampilkan transaksi berdasarkan id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit transaksi berdasarkan id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Mengupdate transaksi berdasarkan id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Menghapus transaksi berdasarkan id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
