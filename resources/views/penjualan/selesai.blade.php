@extends('layouts.master')

@section('title')
Transaksi Penjualan
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-penjualan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
@parent
<li class="active">Transaksi Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="alert alert-success alert-dismissible">
                    <i class="fa fa-check icon"></i>
                    Data Transaksi telah selesai.
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-detail">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            @foreach ($detail as $key => $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->produk->nama_produk}}</td>
                                <td>{{format_uang($item->harga_jual)}}</td>
                                <td>{{format_uang($item->jumlah/$item->jml_kemasan)}}</td>
                                <td>{{format_uang($item->subtotal)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-8">
                    <div class="tampil-bayar bg-primary">Kembali : {{format_uang($penjualan->diterima-$penjualan->bayar)}}</div>
                    <div class="tampil-terbilang"></div>
                </div>
            </div>
            <div class="box-footer">

                @if ($setting->tipe_nota == 1)
                <button class="btn btn-warning btn-flat" onclick="notaKecil('{{ route('transaksi.nota_kecil')}}', 'Nota Kecil')">Cetak Ulang Nota</button>
                @else
                <button class="btn btn-warning btn-flat" onclick="notaBesar('{{ route('transaksi.nota_besar')}}', 'Nota PDF')">Cetak Ulang Nota</button>
                @endif
                <a href="{{ route('transaksi.baru') }}" class="btn btn-primary btn-flat">Transaksi Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let table;
    // tambahkan untuk delete cookie innerHeight terlebih dahulu
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }

    function notaBesar(url, title) {
        popupCenter(url, title, 900, 675);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title,
            `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }
    $(function() {
        table = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'nama_produk'
                },
                {
                    data: 'harga_jual'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'subtotal'
                },
            ]
        })
    });
</script>
@endpush