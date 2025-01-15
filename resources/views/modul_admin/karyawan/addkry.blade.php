@extends('layouts.backend')
@section('title', 'Form Tambah Data Karyawan')
@section('header', 'Tambah Karyawan')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Data Karyawan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @error('errors')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <form action="{{ route('karyawan.store') }}" method="POST" class="form form-vertical">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <div class="position-relative">
                                            <input type="text" name="name" id="nama"
                                                class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="email-id-icon">Email</label>
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email-id-icon"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat Karyawan</label>
                                        <div class="position-relative">
                                            <textarea type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                                rows="3" value="{{ old('alamat') }}"></textarea>
                                            @error('alamat')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="no-telp">No. Telp</label>
                                        <div class="position-relative">
                                            <input type="number" name="no_telp" id="no-telp"
                                                class="form-control @error('no_telp') is-invalid @enderror"
                                                placeholder="No. Telp" value="{{ old('no_telp') }}">
                                            @error('no_telp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <div class="position-relative">
                                            <select name="kelamin" id="kategori"
                                                class="form-control @error('kelamin') is-invalid @enderror">
                                                <option value="" disabled selected>Pilih Kategori</option>
                                                <option value="Cuci"
                                                    {{ old('kelamin') == 'Cuci' ? 'selected' : '' }}>Cuci
                                                </option>
                                                <option value="Gosok"
                                                    {{ old('kelamin') == 'Gosok' ? 'selected' : '' }}>Gosok
                                                </option>
                                            </select>
                                            @error('kelamin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="jenis-kelamin">Jenis Kelamin</label>
                                        <div class="position-relative">
                                            <select name="kelamin" id="jenis-kelamin"
                                                class="form-control @error('kelamin') is-invalid @enderror">
                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki"
                                                    {{ old('kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="Perempuan"
                                                    {{ old('kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                            @error('kelamin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                                    <a href=" {{ route('karyawan.index') }} "
                                        class="btn btn-outline-warning mr-1 mb-1">Batal</a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
