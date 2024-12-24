<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@index');

// Frontend
Route::get('pencarian-laundry','FrontController@search');

Auth::routes([
    'register' => false,
]);

Route::middleware('auth')->group(function () {
  Route::get('/home', 'HomeController@index')->name('home');

  // Modul Admin
  Route::prefix('/')->middleware('role:Admin')->group(function () {
    Route::resource('admin','Admin\AdminController');

    // Pengguna/karyawan
    Route::resource('karyawan','Admin\KaryawanController');
    // Route::get('adm','Admin\AdminController@adm');
    // Route::get('kry','Admin\AdminController@kry');
    // Route::get('kry-add','Admin\AdminController@addkry');

    // Customer
    Route::resource('customer','Admin\CustomerController');
    // Route::get('customer','Admin\AdminController@customer');
    // Route::get('customer-add','Admin\AdminController@addcustomer');
    // Route::post('customer-store','Admin\AdminController@storecustomer');
    // Route::get('customer-edit/{id_customer}','Admin\AdminController@editcustomer');
    // Route::put('customer-update/{id_customer}','Admin\AdminController@updatecustomer');
    // Route::delete('customer-delete/{id_customer}','Admin\AdminController@deletecustomer');
    // Route::get('jml-transaksi','Admin\AdminController@jmlTransaksi');

    // Data Transaksi
    Route::resource('transaksi','Admin\TransaksiController');
    Route::get('filter-transaksi','Admin\TransaksiController@filtertransaksi'); // filter data transaksi by karyawan
    Route::get('invoice-customer/{invoice}','Admin\TransaksiController@invoice'); // lihat invoice

    // Route::get('data-transaksi','Admin\AdminController@datatransaksi');
    Route::get('data-harga','Admin\AdminController@dataharga');
    Route::post('harga-store','Admin\AdminController@hargastore');
    Route::get('edit-harga','Admin\AdminController@hargaedit');

    // Finance
    Route::get('data-finance','Admin\AdminController@finance');

    // Notifikasi
    Route::get('read-notification','Admin\AdminController@notif');

    Route::resource('pelayanan','Admin\PelayananController');

    // Transaksi
    Route::get('add-order','Admin\PelayananController@addorders');
    Route::get('ubah-status-order','Admin\PelayananController@ubahstatusorder');
    Route::get('ubah-status-bayar','Admin\PelayananController@ubahstatusbayar');
    Route::get('ubah-status-ambil','Admin\PelayananController@ubahstatusambil');

    // Customer
    Route::get('list-customer','Admin\PelayananController@listcs');
    Route::get('list-customer-add','Admin\PelayananController@listcsadd');
    Route::post('list-costomer-store','Admin\PelayananController@addcs');

    // Filter
    Route::get('listharga','Admin\PelayananController@listharga');
    Route::get('listhari','Admin\PelayananController@listhari');

    // Laporan
    Route::get('laporan','Admin\LaporanController@laporan');

    // Invoice
    Route::get('invoice-kar/{id}','Admin\InvoiceController@invoicekar');
    Route::get('cetak-invoice/{id}/print','Admin\InvoiceController@cetakinvoice');

    // Setting
    Route::get('settings','Admin\SettingsController@setting');
    Route::put('proses-setting-page/{id}','Admin\SettingsController@proses_set_page')->name('seting-page.update');
    Route::put('set-theme/{id}','Admin\SettingsController@set_theme')->name('setting-theme.update');
    Route::put('set-target-laundry/{id}','Admin\SettingsController@set_target_laundry')->name('set-target.update');
    Route::post('add-bank','Admin\SettingsController@bank')->name('setting.bank');
    Route::put('set-notif/{id}','Admin\SettingsController@notif')->name('set-notif.update');

    // Profile
    Route::get('profile-admin/{id}','Admin\AdminController@profile');
    Route::get('profile-admin-edit','Admin\AdminController@edit_profile');
  });

  // Modul Karyawan
  Route::prefix('/')->middleware('role:Karyawan')->group(function () {
    // Profile
    Route::get('profile-karyawan/{id}','Karyawan\ProfileController@karyawanProfile');
    Route::get('profile-karyawan/edit/{id}','Karyawan\ProfileController@karyawanProfileEdit');
    Route::put('profile-karyawan/update/{id}','Karyawan\ProfileController@karyawanProfileSave');
    Route::put('change-password/{id}','Karyawan\ProfileController@change_password')->name('change.password');

    // Setting
    Route::get('karyawan/setting','Karyawan\SettingsController@setting');
    Route::put('proses-setting-karyawan/{id}','Karyawan\SettingsController@proses_setting_karyawan')->name('proses-setting-karyawan.update');
  });
});
