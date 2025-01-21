@extends('layouts.backend')
@section('title', 'Tambah Data Order')
@section('content')
    @if ($cek_harga && $harga_value != 0) <!-- Memeriksa harga_value -->
        <!-- Tampilkan form jika harga aktif -->
        <div class="card card-outline-info">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Data Order</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi-customer.store') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row p-t-20">
                            <!-- Nama Customer -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Nama Customer</label>
                                    <input type="text" name="customer_name" value="{{ Auth::user()->name }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="customer_id" value="{{ Auth::user()->id }}">

                            <!-- No Transaksi -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">No Transaksi</label>
                                    <input type="text" name="invoice" value="{{ $newID }}"
                                        class="form-control @error('invoice') is-invalid @enderror" readonly>
                                    @error('invoice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Berat Pakaian -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Berat Pakaian</label>
                                    <input type="text"
                                        class="form-control form-control-danger @error('kg') is-invalid @enderror"
                                        name="kg" placeholder="Berat Pakaian (kg)" autocomplete="off" required>
                                    @error('kg')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Pembayaran dan Pilih Paket -->
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Jenis Pembayaran</label>
                                    <select
                                        class="form-control custom-select @error('jenis_pembayaran') is-invalid @enderror"
                                        name="jenis_pembayaran" required>
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Transfer">Transfer</option>
                                    </select>
                                    @error('jenis_pembayaran')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Pilih Paket</label>
                                    <select id="id" name="harga_id"
                                        class="form-control select2 @error('harga_id') is-invalid @enderror" required>
                                        <option value="">-- Jenis Pakaian --</option>
                                        @foreach ($harga as $h)
                                            <option value="{{ $h->id }}">{{ $h->jenis }}</option>
                                        @endforeach
                                    </select>
                                    @error('harga_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span id="select-hari"></span>
                            </div>
                            <div class="col-md-2">
                                <span id="select-harga"></span>
                            </div>

                            <!-- Diskon -->
                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <label class="control-label">Disc (%)</label>
                                    <input type="number" name="disc" placeholder="Tulis Disc"
                                        class="form-control @error('disc') is-invalid @enderror" min="0"
                                        max="100">
                                    @error('disc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tgl">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                        <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Tampilkan pesan jika tidak ada harga aktif -->
        <div class="card">
            <div class="col text-center">
                <img src="{{ asset('backend/images/pages/empty.svg') }}" style="height:500px; width:100%; margin-top:10px">
                <h2 class="mt-1">Data Harga Kosong / Tidak Aktif !</h2>
                <h4>Mohon hubungi Administrator :)</h4>
                <a href="{{ route('pelayanan.index') }}" class="btn btn-primary mt-3">Kembali</a>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        // Filter Harga dan Hari
        $(document).ready(function() {
            var id = $("#id").val();
            $.get('{{ Url('listhari') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                $("#select-hari").html(resp);
                $.get('{{ Url('listharga') }}', {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    id: id
                }, function(resp) {
                    $("#select-harga").html(resp);
                });
            });
        });

        $(document).on('change', '#id', function(e) {
            var id = $(this).val();
            $.get('{{ Url('listhari') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                $("#select-hari").html(resp);
            });

            $.get('{{ Url('listharga') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                $("#select-harga").html(resp);
            });
        });
    </script>
@endsection
