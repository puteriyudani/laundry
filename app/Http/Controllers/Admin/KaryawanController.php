<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddKaryawanRequest;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Session;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::get();
        return view('modul_admin.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        return view('modul_admin.karyawan.addkry');
    }

    public function store(AddKaryawanRequest $request)
    {
        // Simpan data karyawan
        Karyawan::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'alamat'  => $request->alamat,
            'no_telp' => $request->no_telp,
            'kelamin' => $request->kelamin,
        ]);

        Session::flash('success', 'Tambah Karyawan Berhasil');
        return redirect('karyawan');
    }

    public function edit($id)
    {
        $edit = Karyawan::find($id);
        return view('modul_admin.karyawan.editkry', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $addkaryawan = Karyawan::find($id);
        $addkaryawan->name = $request->name;
        $addkaryawan->email = $request->email;
        $addkaryawan->alamat = $request->alamat;
        $addkaryawan->no_telp = $request->no_telp;
        $addkaryawan->kelamin = $request->kelamin;
        $addkaryawan->save();

        Session::flash('success', 'Update Karyawan Berhasil');
        return redirect('karyawan');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            Session::flash('error', 'Karyawan tidak ditemukan.');
            return redirect('karyawan');
        }

        $karyawan->delete();

        Session::flash('success', 'Hapus Karyawan Berhasil.');
        return redirect('karyawan');
    }
}
