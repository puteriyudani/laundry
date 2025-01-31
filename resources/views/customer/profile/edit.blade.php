@extends('layouts.backend')
@section('title', 'Edit Profile')
@section('content')
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30"> <img src="{{ asset('backend/images/profile/user-uploads/user-01.jpg') }}"
                            class="rounded" width="230" />
                        <h4 class="card-title m-t-10">{{ $edit->name }}</h4>
                        <h6 class="card-subtitle">Customer</h6>
                    </center>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body"> <small class="text-muted">Email address </small>
                    <h6>{{ $edit->email }}</h6> <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ $edit->no_telp }}</h6> <small class="text-muted p-t-30 db">Address</small>
                    <h6>{{ $edit->alamat }}</h6>
                    <small class="text-muted p-t-30 db">Social Profile</small>
                    <br />
                    <button class="btn btn-circle btn-secondary"><i class="fa fa-facebook"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fa fa-twitter"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fa fa-youtube"></i></button>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Profile</h4>
                    <hr>
                    <form action="{{ url('profile-customer/update', $edit->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Nama</label>
                                <input type="text" name="name" value="{{ $edit->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Email</label>
                                <input type="email" name="email" value="{{ $edit->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">No. Telp</label>
                                <input type="number" name="no_telp" value="{{ $edit->no_telp }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="jenis-kelamin">Jenis Kelamin</label>
                                <div class="position-relative">
                                    <select name="kelamin" class="form-control @error('kelamin') is-invalid @enderror">
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ $edit->kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan" {{ $edit->kelamin == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
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
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3">{{ $edit->alamat }}</textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update</button>
                            <a href="{{ url('profile-customer', Auth::user()->id) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
