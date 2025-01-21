@extends('layouts.backend')
@section('title', 'Dashboard Customer')
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
            <h4 class="card-title">
                <a href="{{ url('add-order') }}" class="btn btn-primary">Tambah</a>
            </h4>
            <div class="table-responsive m-t-0">
                <table id="myTable" class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>TGL Transaksi</th>
                            <th>Karyawan</th>
                            <th>Status Order</th>
                            <th>Status Payment</th>
                            <th>Jenis Laundri</th>
                            <th>Total</th>
                            <th>Catatan Admin</th>
                            <th>Catatan Customer</th>
                            <th>Action</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td style="font-weight:bold; font-color:black">{{ $item->invoice }}</td>
                                <td>{{ carbon\carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
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

                                <!-- Action Buttons Column -->
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <button class="btn btn-sm btn-danger" style="opacity: 1" disabled>Harus Di
                                            Bayar</button>
                                    @elseif($item->status_payment == 'Success')
                                        @if ($item->status_order == 'Process')
                                            <button class="btn btn-sm btn-warning" style="opacity: 1"
                                                disabled>Menunggu</button>
                                        @elseif ($item->status_order == 'Done')
                                            <button class="btn btn-sm btn-success" style="opacity: 1" disabled>Harus Di
                                                Ambil</button>
                                        @elseif($item->status_order == 'Delivery')
                                            <button class="btn btn-sm btn-info" style="opacity: 1" disabled>Selesai</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // DATATABLE
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
