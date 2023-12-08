@extends('layouts.master')

@section('title')
    Kartu Stok {{ $nama_produk }} <br> {{ tanggal_indonesia($tanggalAwal, false) }} s/d
    {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection
{{-- <?php
var_dump($produk_name);
?> --}}
@push('css')
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Kartu Stok</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i>
                        Nama Barang</button>
                    {{-- <a href="{{ route('kartustok.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank"
                        class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a> --}}
                </div>
                <div class="box-body table-responsive">
                    <table class="table-stiped table-bordered table">
                        <thead>
                            <th width="5%">No</th>
                            <th>Jumlah Kemasan</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Sisa</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('kartustok.form')
@endsection

@push('scripts')
    <script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('kartustok.data', [$id_produk, $tanggalAwal, $tanggalAkhir]) }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'jml_kemasan'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'keluar'
                    },
                    {
                        data: 'stok_akhir'
                    }
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriode() {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
