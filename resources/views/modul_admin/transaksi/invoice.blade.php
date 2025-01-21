@extends('layouts.backend')
@section('title', 'Admin - Invoice Customer')
@section('header', 'Invoice Customer')
@section('content')
    <div class="col-md-12">
        <div class="card card-body printableArea">
            <h3><b>INVOICE</b> <span class="pull-right">{{ $dataInvoice->invoice }}</span></h3>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left">
                        <address>
                            <p class="text-muted m-l-5">
                                Diterima Oleh<span style="margin-left:20px">:</span>
                                {{ optional($dataInvoice->customers)->name ?? '-' }} <br />
                                Alamat<span style="margin-left:68px">:</span>
                                {{ optional($dataInvoice->customers)->alamat ?? '-' }} <br />
                                No. Telp<span style="margin-left:63px">:</span>
                                {{ optional($dataInvoice->customers)->no_telp == 0 ? '-' : optional($dataInvoice->customers)->no_telp ?? '-' }}
                            </p>
                        </address>
                    </div>

                    <div class="pull-right text-right">
                        <address>
                            <h3>Detail Order Customer:</h3>
                            <p class="text-muted m-l-30">
                                {{ optional($dataInvoice->customers)->name ?? '-' }}<br />
                                {{ optional($dataInvoice->customers)->alamat ?? '-' }}<br />
                                {{ optional($dataInvoice->customers)->no_telp == 0 ? '-' : optional($dataInvoice->customers)->no_telp }}
                            </p>
                            <p class="m-t-30">
                                <b>Tanggal Masuk:</b>
                                <i class="fa fa-calendar"></i>
                                {{ Carbon\Carbon::parse($dataInvoice->tgl_transaksi)->format('d F Y') ?? '-' }}
                            </p>
                            <p>
                                <b>Tanggal Diambil:</b>
                                <i class="fa fa-calendar"></i>
                                @if (empty($dataInvoice->tgl_ambil))
                                    Belum Diambil
                                @else
                                    {{ Carbon\Carbon::parse($dataInvoice->tgl_ambil)->format('d F Y') }}
                                @endif
                            </p>
                        </address>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive m-t-20" style="clear: both;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Jenis Pakaian</th>
                                    <th class="text-right">Berat</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalHitung = 0;
                                @endphp
                                @foreach ($invoice as $key => $item)
                                    @php
                                        $hitung = $item->kg * $item->harga;
                                        $totalHitung += $hitung;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $item->price->jenis ?? '-' }}</td>
                                        <td class="text-right">{{ $item->kg }} Kg</td>
                                        <td class="text-right">{{ Rupiah::getRupiah($item->harga) }} /Kg</td>
                                        <td class="text-right">{{ Rupiah::getRupiah($hitung) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="pull-left m-t-10">
                        <h6 class="text-right" style="font-weight:bold">Dengan Menandatangani/Menerima Nota Ini, Berarti
                            Anda Setuju:</h6>
                        <p>
                            1. Isi Deskripsi <br>
                            2. Isi Deskripsi
                        </p>
                    </div>
                    <div class="pull-right m-t-10 text-right">
                        <p>Total: {{ Rupiah::getRupiah($totalHitung) }}</p>
                        @php
                            $disc = ($totalHitung * ($dataInvoice->disc ?? 0)) / 100;
                        @endphp
                        <p>Disc ({{ $dataInvoice->disc ?? 0 }} %): {{ Rupiah::getRupiah($disc) }}</p>
                        <hr>
                        <h3><b>Total Bayar:</b> {{ Rupiah::getRupiah($totalHitung - $disc) }}</h3>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="text-right">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-outline btn-info"
                            style="color:white">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
