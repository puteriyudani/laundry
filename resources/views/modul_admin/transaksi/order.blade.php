@extends('layouts.backend')
@section('title', 'Dashboard Admin')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h6>Info : <code> Untuk Mengubah Status Order & Pembayaran Klik Pada Bagian 'Action' Masing-masing.</code></h6>
            <div class="table-responsive m-t-0">
                <table id="myTable" class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>TGL Transaksi</th>
                            <th>Customer</th>
                            <th>Karyawan</th>
                            <th>Status Order</th>
                            <th>Status Payment</th>
                            <th>Jenis Laundri</th>
                            <th>Total</th>
                            <th>Catatan Admin</th>
                            <th>Catatan Customer</th>
                            <th>Action</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- {{dd($order)}} --}}
                        <?php $no = 1; ?>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td style="font-weight:bold; font-color:black">{{ $item->invoice }}</td>
                                <td>{{ carbon\carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                <td>{{ $item->customer }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Karyawan Tidak Tersedia' }}</td>
                                <td>
                                    @if ($item->status_order == 'Done')
                                        <span class="label label-success">Selesai</span>
                                    @elseif($item->status_order == 'Delivery')
                                        <span class="label label-primary">Sudah Diambil</span>
                                    @elseif($item->status_order == 'Process')
                                        <span class="label label-info">Sedang Proses</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_payment == 'Success')
                                        <span class="label label-success">Sudah Dibayar</span>
                                    @elseif($item->status_payment == 'Pending')
                                        <span class="label label-info">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td>{{ $item->price->jenis ?? 'Jenis Tidak Tersedia' }}</td>

                                <td>
                                    {{ Rupiah::getRupiah($item->harga_akhir) }}
                                </td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>{{ $item->catatan_customer }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <a class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-id-pay="{{ $item->id }}" data-id-name="{{ $item->customer }}"
                                            data-id-bayar="{{ $item->status_payment }}" id="klick"
                                            data-target="#ubah_status_pay" style="color:white">Bayar</a>
                                        <a href="{{ url('invoice-kar', $item->id) }}"
                                            class="btn btn-sm btn-primary">Invoice</a>
                                    @elseif($item->status_payment == 'Success')
                                        @if ($item->status_order == 'Done')
                                            <a class="btn btn-sm btn-success" data-id-ambil="{{ $item->id }}"
                                                id="ambil" style="color:white">Ambil</a>
                                        @elseif($item->status_order == 'Process')
                                            <a class="btn btn-sm btn-info" data-toggle="modal"
                                                data-id="{{ $item->id }}" data-id-nama="{{ $item->customer }}"
                                                data-id-order="{{ $item->status_order }}" id="klikmodal"
                                                data-target="#ubah_status" style="color:white">Selesai</a>
                                            <a href="{{ url('invoice-kar', $item->id) }}"
                                                class="btn btn-sm btn-primary">Invoice</a>
                                        @elseif($item->status_order == 'Delivery')
                                            <a href="" class="btn btn-sm btn-warning">Detail</a>
                                            <a href="{{ url('invoice-kar', $item->id) }}"
                                                class="btn btn-sm btn-primary">Invoice</a>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="modal" data-id="{{ $item->id }}"
                                        data-karyawan-id="{{ $item->karyawan_id }}"
                                        data-catatan="{{ $item->catatan_admin }}" data-target="#editModal">Edit</a>
                                </td>

                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- Modal to Edit Karyawan and Catatan -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ url('update-karyawan-catatan') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Karyawan & Catatan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="transaksi_id" name="transaksi_id">
                                <div class="form-group">
                                    <label for="karyawan_id">Nama Karyawan</label>
                                    <select class="form-control" id="karyawan_id" name="karyawan_id">
                                        @foreach ($karyawans as $karyawan)
                                            <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="catatan_admin">Catatan Admin</label>
                                    <textarea class="form-control" id="catatan_admin" name="catatan_admin"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @include('modul_admin.transaksi.statusorder')
            @include('modul_admin.transaksi.statusbayar')
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // Tampilkan Modal Ubah Status Order
        $(document).on('click', '#klikmodal', function() {
            var id = $(this).attr('data-id');
            var customer = $(this).attr('data-id-nama');
            var status_order = $(this).attr('data-id-order');
            $("#id").val(id)
            $("#customer").val(customer)
            $("#status_order").val(status_order)
        });

        // Proses Ubah Status Order
        $(document).on('click', '#save_status', function() {
            var id = $("#id").val();
            var customer = $("#customer").val();
            var status_order = $("#status_order").val();

            $.get('{{ Url('ubah-status-order') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id,
                customer: customer,
                status_order: status_order
            }, function(resp) {
                $("#id").val('');
                $("#customer").val('');
                $("#status_order").val('');

                location.reload();
            });
        });


        // Tampilkan Modal Ubah Status Pembayaran
        $(document).on('click', '#klick', function() {
            var id = $(this).attr('data-id-pay');
            var customer = $(this).attr('data-id-name');
            var status_payment = $(this).attr('data-id-bayar');
            $("#id_bayar").val(id)
            $("#customer_pay").val(customer)
            $("#status_payment").val(status_payment)
        });

        // Proses Ubah Status Pembayaran
        $(document).on('click', '#simpan_status', function() {
            var id = $("#id_bayar").val();
            var customer = $("#customer_pay").val();
            var status_payment = $("#status_payment").val();

            $.get('{{ Url('ubah-status-bayar') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id,
                customer: customer,
                status_payment: status_payment
            }, function(resp) {
                $("#id_bayar").val('');
                $("#customer_pay").val('');
                $("#status_payment").val('');
                location.reload();
            });
        });

        // Ubah Status Menjadi Diambil
        $(document).on('click', '#ambil', function() {
            var id = $(this).attr('data-id-ambil');
            $.get(' {{ Url('ubah-status-ambil') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                location.reload();
            });
        });

        // DATATABLE
        $(document).ready(function() {
            $('#myTable').DataTable();
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": 2
                    }],
                    "order": [
                        [2, 'asc']
                    ],
                    "displayLength": 25,
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5">' + group +
                                    '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });
            });
        });

        // Edit Karyawan dan Catatan
        $(document).on('click', '[data-target="#editModal"]', function() {
            var transaksi_id = $(this).data('id');
            var karyawan_id = $(this).data('karyawan-id');
            var catatan = $(this).data('catatan');

            $('#transaksi_id').val(transaksi_id);
            $('#karyawan_id').val(karyawan_id);
            $('#catatan_admin').val(catatan);
        });
    </script>
@endsection
