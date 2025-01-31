<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{transaksi, customer, User};
use Auth;
use DB;
use Carbon\carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->auth === "Admin") {
                $customer = user::where('auth', 'Customer')->get();
                $masuk = transaksi::whereIN('status_order', ['Process', 'Done', 'Delivery'])->count();
                $selesai = transaksi::where('status_order', 'Done')->count();
                $diambil = transaksi::where('status_order', 'Delivery')->count();
                $sudahbayar = transaksi::where('status_payment', 'Success')->count();
                $belumbayar = transaksi::where('status_payment', 'Pending')->count();
                $data = DB::table("transaksis")
                    ->select("id", DB::raw("(COUNT(*)) as customer"))
                    ->orderBy('created_at')
                    ->groupBy(DB::raw("MONTH(created_at)"))
                    ->count();

                // Statistik Harian
                $hari = DB::table('transaksis')
                    ->select('tgl', DB::raw('count(id) AS jml'))
                    ->whereYear('created_at', '=', date("Y", strtotime(now())))
                    ->whereMonth('created_at', '=', date("m", strtotime(now())))
                    ->groupBy('tgl')
                    ->get();

                $tanggal = '';
                $batas =  31;
                $nilai = '';
                for ($_i = 1; $_i <= $batas; $_i++) {
                    $tanggal = $tanggal . (string)$_i . ',';
                    $_check = false;
                    foreach ($hari as $_data) {
                        if ((int)@$_data->tgl === $_i) {
                            $nilai = $nilai . (string)$_data->jml . ',';
                            $_check = true;
                        }
                    }
                    if (!$_check) {
                        $nilai = $nilai . '0,';
                    }
                }

                // Statistik Bulanan
                $jan = transaksi::where('bulan', 1)->where('tahun', Carbon::now()->format('Y'))->count();
                $feb = transaksi::where('bulan', 2)->where('tahun', Carbon::now()->format('Y'))->count();
                $mar = transaksi::where('bulan', 3)->where('tahun', Carbon::now()->format('Y'))->count();
                $apr = transaksi::where('bulan', 4)->where('tahun', Carbon::now()->format('Y'))->count();
                $mey = transaksi::where('bulan', 5)->where('tahun', Carbon::now()->format('Y'))->count();
                $juni = transaksi::where('bulan', 6)->where('tahun', Carbon::now()->format('Y'))->count();
                $july = transaksi::where('bulan', 7)->where('tahun', Carbon::now()->format('Y'))->count();
                $aug = transaksi::where('bulan', 8)->where('tahun', Carbon::now()->format('Y'))->count();
                $sep = transaksi::where('bulan', 9)->where('tahun', Carbon::now()->format('Y'))->count();
                $oct = transaksi::where('bulan', 10)->where('tahun', Carbon::now()->format('Y'))->count();
                $nov = transaksi::where('bulan', 11)->where('tahun', Carbon::now()->format('Y'))->count();
                $dec = transaksi::where('bulan', 12)->where('tahun', Carbon::now()->format('Y'))->count();

                return view('modul_admin.index')
                    ->with('data', $data)
                    ->with('customer', value: $customer)
                    ->with('masuk', $masuk)
                    ->with('selesai', $selesai)
                    ->with('sudahbayar', $sudahbayar)
                    ->with('belumbayar', $belumbayar)
                    ->with('_tanggal', substr($tanggal, 0, -1))
                    ->with('_nilai', substr($nilai, 0, -1))
                    ->with('diambil', $diambil)
                    ->with('jan', $jan)
                    ->with('feb', $feb)
                    ->with('mar', $mar)
                    ->with('apr', $apr)
                    ->with('mey', $mey)
                    ->with('juni', $juni)
                    ->with('july', $july)
                    ->with('aug', $aug)
                    ->with('sep', $sep)
                    ->with('oct', $oct)
                    ->with('nov', $nov)
                    ->with('dec', $dec);
            } elseif (Auth::user()->auth === "Customer") {
                $masuk = transaksi::whereIN('status_order', ['Process', 'Done', 'Delivery'])->where('customer_id', auth::user()->id)->count();
                $selesai = transaksi::where('status_order', 'Done')->where('customer_id', auth::user()->id)->count();
                $diambil = transaksi::where('status_order', 'Delivery')->where('customer_id', auth::user()->id)->count();
                $sudahbayar = transaksi::where('status_payment', 'Success')->where('customer_id', auth::user()->id)->count();
                $belumbayar = transaksi::where('status_payment', 'Pending')->where('customer_id', auth::user()->id)->count();

                // Statistik Harian
                $hari = DB::table('transaksis')
                    ->select('tgl', DB::raw('count(id) AS jml'))
                    ->whereYear('created_at', '=', date("Y", strtotime(now())))
                    ->whereMonth('created_at', '=', date("m", strtotime(now())))
                    ->groupBy('tgl')
                    ->where('customer_id', auth::user()->id)
                    ->get();

                $tanggal = '';
                $batas =  31;
                $nilai = '';
                for ($_i = 1; $_i <= $batas; $_i++) {
                    $tanggal = $tanggal . (string)$_i . ',';
                    $_check = false;
                    foreach ($hari as $_data) {
                        if ((int)@$_data->tgl === $_i) {
                            $nilai = $nilai . (string)$_data->jml . ',';
                            $_check = true;
                        }
                    }
                    if (!$_check) {
                        $nilai = $nilai . '0,';
                    }
                }

                // Statistik Bulanan
                $jan = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 1)->where('customer_id', auth::user()->id)->count();
                $feb = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 2)->where('customer_id', auth::user()->id)->count();
                $mar = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 3)->where('customer_id', auth::user()->id)->count();
                $apr = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 4)->where('customer_id', auth::user()->id)->count();
                $mey = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 5)->where('customer_id', auth::user()->id)->count();
                $juni = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 6)->where('customer_id', auth::user()->id)->count();
                $july = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 7)->where('customer_id', auth::user()->id)->count();
                $aug = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 8)->where('customer_id', auth::user()->id)->count();
                $sep = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 9)->where('customer_id', auth::user()->id)->count();
                $oct = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 10)->where('customer_id', auth::user()->id)->count();
                $nov = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 11)->where('customer_id', auth::user()->id)->count();
                $dec = transaksi::where('tahun', Carbon::now()->format('Y'))->where('bulan', 12)->where('customer_id', auth::user()->id)->count();

                return view('customer.index')
                    ->with('masuk', $masuk)
                    ->with('diambil', $diambil)
                    ->with('selesai', $selesai)
                    ->with('sudahbayar', $sudahbayar)
                    ->with('belumbayar', $belumbayar)
                    ->with('_tanggal', substr($tanggal, 0, -1))
                    ->with('_nilai', substr($nilai, 0, -1))
                    ->with('jan', $jan)->with('feb', $feb)->with('mar', $mar)->with('apr', $apr)->with('mey', $mey)->with('juni', $juni)->with('july', $july)->with('aug', $aug)->with('sep', $sep)->with('oct', $oct)->with('nov', $nov)->with('dec', $dec);
            } else {
                Auth::logout();
            }
        }
    }
}
