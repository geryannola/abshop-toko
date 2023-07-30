@extends('layouts.master')

@section('title')
Dashboard
@endsection

@section('breadcrumb')
@parent
<li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $kategori }}</h3>

                <p>Total Kategori</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $produk }}</h3>

                <p>Total Produk</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $member }}</h3>

                <p>Total Member</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('member.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $supplier }}</h3>

                <p>Total Supplier</p>
            </div>
            <div class="icon">
                <i class="fa fa-truck"></i>
            </div>
            <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
<!-- Small boxes (Stat box) KEDUA -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-money" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <!-- <span class="info-box-text">Penjualan Hari Ini</span> -->
                <p>Penjualan Hari Ini</p>
                <span class="info-box-number">{{format_uang($penjualanHari)}}</span>
                <p>Rupiah</p>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-balance-scale" aria-hidden="true"></i>
            </span>

            <div class="info-box-content">
                <!-- <span class="info-box-text">Penjualan Bulan Ini</span> -->
                <p>Penjualan Bulan Ini</p>
                <span class="info-box-number">{{format_uang($penjualanBulan)}}</span>
                <p>Rupiah</p>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-shopping-basket" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <!-- <span class="info-box-text">Pengunjung Hari ini</span> -->
                <p>Pengunjung Hari ini</p>
                <span class="info-box-number">{{format_uang($pengunjung)}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-balance-scale" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <!-- <span class="info-box-text">Pengeluaran Bulan ini</span> -->
                <p>Pengeluaran Bulan ini</p>
                <span class="info-box-number">{{format_uang($pembelianBulan)}}</span>
                <p>Rupiah</p>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <!-- <span class="info-box-text">Pengunjung Hari ini</span> -->
                <p>Nilai Stok</p>
                <span class="info-box-number">{{format_uang($nilaiStok->harga)}}</span>
                <p>Rupiah</p>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-balance-scale" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <!-- <span class="info-box-text">Pengeluaran Bulan ini</span> -->
                <p></p>
                <span class="info-box-number"></span>
                <p>Rupiah</p>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<!-- Main row -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row (main row) -->

<!-- Main row -->
<div class="row">
    <div class="col-lg-12">
        <!-- PRODUCT LIST -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Stok Barang</b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($produkStok as $produk)
                    <?php
                    $dos = floor($produk->stok / $produk->jml_kemasan);
                    $sisa = ($produk->stok - ($dos * $produk->jml_kemasan));
                    ?>
                    <li class="item">
                        <!-- <div class="product-info"> -->
                        <a href="" class="">{{ $produk->nama_produk }}
                            <h3 class="pull-right">{{format_uang($dos)}} / {{format_uang($sisa)}}</h3>
                        </a>
                        <span class="product-description">
                            {{$produk->merk}}, Harga {{format_uang($produk->harga_beli)}} dengan harga Jual {{format_uang($produk->harga_jual)}}.
                        </span>
                        <span class="product-description">
                            {{format_uang($produk->harga_beli*$produk->stok/$produk->jml_kemasan)}}.
                        </span>
                        <!-- </div> -->
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
        <!-- /.col -->
    </div>
    <!-- /.row (main row) -->

    @endsection

    @push('scripts')
    <!-- ChartJS -->
    <script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
    <script>
        $(function() {
            // Get context with jQuery - using jQuery's .get() method.
            var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas);

            var salesChartData = {
                labels: <?= json_encode($data_tanggal); ?>,
                datasets: [{
                    label: 'Pendapatan',
                    fillColor: 'rgba(60,141,188,0.9)',
                    strokeColor: 'rgba(60,141,188,0.8)',
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: <?= json_encode($data_pendapatan) ?>
                }]
            };

            var salesChartOptions = {
                pointDot: false,
                responsive: true
            };

            salesChart.Line(salesChartData, salesChartOptions);
        });
    </script>
    @endpush