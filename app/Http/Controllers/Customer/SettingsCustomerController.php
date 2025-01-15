<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Session;

class SettingsCustomerController extends Controller
{
    //Setting Customer
    public function setting()
    {
      return view('customer.settings.index');
    }

    // Proses setting
    public function proses_setting_customer(Request $request, $id)
    {
       $setting = User::findOrFail($id);
        if ($request->theme == NULL) {
          $setting->theme = '0';
        } else {
          $setting->theme = $request->theme;
        }
        $setting->save();

        Session::flash('success','Setting Berhasil Diupdate !');
        return back();
    }
}
