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

                    <div class="alert alert-info alert-dismissible" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> Perubahan berhasil disimpan
                    </div>
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <form class="form-produk" action="{{ route('transaksi.scan') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <input type="hidden" name="jenis" id="jenis">
                                    <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                                class="fa fa-arrow-right"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table-stiped table-bordered table-penjualan table">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                                @csrf
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">
                                <input type="hidden" name="id_member" id="id_member"
                                    value="{{ $memberSelected->id_member }}">
                                <input type="hidden" name="diskon" id="diskon" class="form-control"
                                    value="{{ !empty($memberSelected->id_member) ? $diskon : 0 }}" readonly>

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_member" class="col-lg-2 control-label">Member</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_member"
                                                value="{{ $memberSelected->kode_member }}">
                                            <span class="input-group-btn">
                                                <button onclick="tampilMember()" class="btn btn-info btn-flat"
                                                    type="button"><i class="fa fa-arrow-right"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            value="{{ !empty($memberSelected->id_member) ? $diskon : 0 }}" readonly>
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="diterima" class="form-control" name="diterima"
                                            value="{{ $penjualan->diterima ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_whatsapp" class="col-lg-2 control-label">No Telepon / WhatsAPP</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="no_whatsapp" class="form-control" name="no_whatsapp"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <!-- <div class="d-flex"> -->
                                        <a class="btn btn-warning btn-lg btn-block btn-limaratus pb-8">500</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-seribu">1.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-duaribu">2.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-limaribu">5.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-sepuluh">10.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-duapuluh">20.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-limapuluh">50.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-warning btn-lg btn-block btn-seratus">100.000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-success btn-lg btn-block btn-nol2">00</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-success btn-lg btn-block btn-nol3">000</a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-info btn-lg btn-block btn-bayar" id="tampil-terima"></a>
                                    </div>
                                    <div class="col-xs-4 col-sm-2 col-lg-3">
                                        <a class="btn btn-danger btn-lg btn-block btn-hapus">Hapus</a>
                                        <!-- </div> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="kembali" name="kembali" class="form-control"
                                            value="0" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('penjualan_detail.produk')
    @includeIf('penjualan_detail.member')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {

            $('body').addClass('sidebar-collapse');

            table = $('.table-penjualan').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('transaksi.data', $id_penjualan) }}",
                    },
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
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').val());
                    setTimeout(() => {
                        $('#diterima').trigger('input');
                    }, 300);
                });
            $('#kode_produk').focus();
            table2 = $('.table-produk').DataTable();

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let stok = $(this).data('stok');
                let jml_kemasan = $(this).data('jml_kemasan');
                let jml = $(this).data('jml');
                let stokreal = stok / jml_kemasan;

                let jumlah = parseInt($(this).val());

                if (jumlah > 10000) {
                    $(this).val(10000);
                    alert('Jumlah tidak boleh lebih dari 10000');
                    return;
                }
                if (jumlah > stokreal) {
                    $(this).val(stokreal);
                    alert('Jumlah Melebihi stok');
                    return;
                }

                $.post(`{{ url('/transaksi') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah,
                        'stok': stok,
                        'stokreal': stokreal,
                        'jml': jml,
                        'jml_kemasan': jml_kemasan
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        // alert('Tidak dapat menyimpan data');
                        return;
                    });
            });
            $(document).on('input', '.harga_jual', function() {
                let id = $(this).data('id');
                let harga_jual = parseInt($(this).val());

                if (harga_jual < 1) {
                    $(this).val(1);
                    alert('Jumlah tidak boleh kurang dari 1');
                    return;
                }

                $.post(`{{ url('/transaksi') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'harga_jual': harga_jual
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        // alert('Tidak dapat menyimpan data');
                        return;
                    });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('#diterima').on('input', function() {
                if ($(this).val() == "") {
                    $(this).val().select();
                }

                loadForm($('#diskon').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            });

            $('.btn-simpan').on('click', function() {
                $('.form-penjualan').submit();
            });

            $(".btn-limaratus").click(function() {
                var nilai = parseInt($("#diterima").val()) + 500;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-seribu").click(function() {
                var nilai = parseInt($("#diterima").val()) + 1000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-duaribu").click(function() {
                var nilai = parseInt($("#diterima").val()) + 2000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-limaribu").click(function() {
                var nilai = parseInt($("#diterima").val()) + 5000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });

            $(".btn-sepuluh").click(function() {
                var nilai = parseInt($("#diterima").val()) + 10000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });

            $(".btn-duapuluh").click(function() {
                var nilai = parseInt($("#diterima").val()) + 20000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });

            $(".btn-limapuluh").click(function() {
                var nilai = parseInt($("#diterima").val()) + 50000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });

            $(".btn-seratus").click(function() {
                var nilai = parseInt($("#diterima").val()) + 100000;
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-nol2").click(function() {
                var nilai = $("#diterima").val() + '00';
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-nol3").click(function() {
                var nilai = $("#diterima").val() + '000';
                $("#diterima").val(nilai);
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-bayar").click(function() {
                $("#diterima").val($('#bayar').val());
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });
            $(".btn-hapus").click(function() {
                // $("#diterima").val($('#bayar').val('0'));
                $("#diterima").val('0');
                loadForm($('#diskon').val(), $("#diterima").val());
                return;
            });

        });
        // $(document).ready(function() {

        //     $("#sepuluh").click(function() {
        //         $("#diterima").val("10000");
        //     });
        // });

        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            $('#jenis').val('grosir');
            hideProduk();
            tambahProduk();
        }

        function pilihProdukEcer(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            $('#jenis').val('eceran');
            hideProduk();
            tambahProduk();
        }

        function tambahProduk() {
            $.post("{{ route('transaksi.store') }}", $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

        function tampilMember() {
            $('#modal-member').modal('show');
        }

        function pilihMember(id, kode, diskon) {
            $('#id_member').val(id);
            $('#kode_member').val(kode);
            $('#diskon').val(diskon);
            // $('#diskon').val('{{ $diskon }}');
            loadForm($('#diskon').val());
            $('#diterima').val(0).focus().select();
            hideMember();
        }

        function hideMember() {
            $('#modal-member').modal('hide');
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        }

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.btn-bayar').val(response.bayar);
                    $('#tampil-terima').text(response.bayarrp);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
