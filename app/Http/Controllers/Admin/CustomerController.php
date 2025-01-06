<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddCustomerRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Session;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = User::where('auth', 'Customer')->get();
        return view('modul_admin.pengguna.customer', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modul_admin.pengguna.addcus');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCustomerRequest $request)
    {
        $adduser = new User();
        $adduser->name          = $request->name;
        $adduser->email         = $request->email;
        $adduser->alamat        = $request->alamat;
        $adduser->no_telp       = $request->no_telp;
        $adduser->kelamin       = $request->kelamin;
        $adduser->status        = 'Active';
        $adduser->auth          = 'Customer';
        $adduser->password      = bcrypt('123456');
        $adduser->save();

        $adduser->assignRole($adduser->auth);

        Session::flash('success', 'Tambah Customer Berhasil');
        return redirect('customer');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = User::find($id);
        return view('modul_admin.pengguna.editcus', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adduser = User::find($id);
        $adduser->status = $request->status;
        $adduser->kelamin = $request->kelamin;
        $adduser->save();

        Session::flash('success', 'Update Customer Berhasil');
        return redirect('customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::find($id);
        if ($delete->status == 'Active') {
            Session::flash('error', 'Error, Status Customer masih aktif');
        } else {
            $delete->delete();
            Session::flash('success', 'Hapus Customer Berhasil');
        }
        return redirect('customer');
    }
}
