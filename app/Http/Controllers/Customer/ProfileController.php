<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Session;
class ProfileController extends Controller
{
    // Profile Customer Cabang
    public function customerProfile($id)
    {
      $user = User::find($id);
      return view('customer.profile.index', compact('user'));
    }

    // Profile Customer Cabang - Edit
    public function customerProfileEdit(Request $request, $id)
    {
      $edit = User::find($id);
      return view('customer.profile.edit', compact('edit'));
    }

    // Profile Customer Cabang - Save
    public function customerProfileSave(Request $request, $id)
    {
      $edit = User::FindorFail($id);
      $edit->id = $request->id;
      $edit->name = $request->name;
      $edit->email = $request->email;
      $edit->alamat = $request->alamat;
      $edit->no_telp = $request->no_telp;
      $edit->kelamin = $request->kelamin;
      $edit->save();


      alert()->success('Update Data Berhasil');
      $id = $edit->id;
      return redirect('profile-customer/' .$id.'');
    }

    // Change Password Customer
    public function change_password(Request $request, $id)
    {
      $request->validate([
        'password'  => 'required|confirmed',
      ]);

      $change_password = User::findOrFail($id);
      $change_password->password = bcrypt($request->password);
      $change_password->save();

      Session::flash('success','Password Berhasil Diubah !');
      return \redirect()->back();
    }
}
