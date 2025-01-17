@extends('layouts.backend')
@section('title', 'Admin - Data Harga Laundry')
@section('content')
    @include('partials.flash-message')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Data Harga Laundry
                            <a class="btn btn-primary" style="color:white">Tambah</a>
                        </h4>
                        <div class="table-responsive m-t-0">
                            <table id="myTable" class="table display table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis</th>
                                        <th>Lama</th>
                                        <th>Kg</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($harga as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->hari }} Hari</td>
                                            <td>{{ $item->kg }} Kg</td>
                                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->status == '1')
                                                    <span class="label label-primary">Aktif</span>
                                                @else
                                                    <span class="label label-warning">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-success" data-toggle="modal"
                                                    data-id="{{ $item->id }}" data-id-jenis="{{ $item->jenis }} "
                                                    data-id-kg="{{ $item->kg }}" data-id-harga="{{ $item->harga }}"
                                                    data-id-hari="{{ $item->hari }}" data-id-status="{{ $item->status }}"
                                                    id="click_harga" data-target="#edit_harga" style="color:white">Edit</a>
                                            </td>
                                        </tr>
                                        @php $no++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @include('modul_admin.laundri.editharga')
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-black">Form Tambah Data Harga</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('harga-store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row p-t-20">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Jenis Paket</label>
                                            <input type="text" name="jenis" value="{{ old('jenis') }}"
                                                class="form-control @error('jenis') is-invalid @enderror"
                                                placeholder="Tambahkan Jenis Paket" required autocomplete="off">
                                            <small class="form-control-feedback">Pisahkan dengan format '+' jika jenis
                                                lebih dari satu</small>
                                            @error('jenis')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Berat Per-Kg</label>
                                            <input type="text" class="form-control" value="1000 gram" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Harga Per-Kg</label>
                                            <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                                name="harga" value="{{ old('harga') }}" placeholder="Harga Per-Kg"
                                                required>
                                            <small class="form-control-feedback">Tuliskan tanpa tanda ',' dan
                                                '.'</small>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Lama Hari</label>
                                            <input type="number" name="hari" value="{{ old('hari') }}"
                                                class="form-control @error('hari') is-invalid @enderror"
                                                placeholder="Lama Hari" required>
                                            @error('hari')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).on('click', '#click_harga', function() {
            const id = $(this).data('id');
            const jenis = $(this).data('id-jenis');
            const kg = $(this).data('id-kg');
            const hari = $(this).data('id-hari');
            const harga = $(this).data('id-harga');
            const status = $(this).data('id-status');

            $('#id_harga').val(id);
            $('#jenis').val(jenis.trim());
            $('#kg').val(kg);
            $('#hari').val(hari);
            $('#harga').val(harga);
            $('#status').val(status);
        });

        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
