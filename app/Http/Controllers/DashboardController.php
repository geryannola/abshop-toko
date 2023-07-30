<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();
        $produk = Produk::count();
        $produkStok = Produk::get();
        // $produkStok = Produk::select(DB::raw('sum(stok - stok_buffer) as sisa'))->get();
        // dd($produkStok);
        $supplier = Supplier::count();
        $member = Member::count();
        $nilaiStok = array();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');
        $tanggal_bulan = date('Y-m');

        $penjualanHari = Penjualan::where('created_at', 'LIKE', "%$tanggal_akhir%")->sum('bayar');
        $penjualanBulan = Penjualan::where('created_at', 'LIKE', "%$tanggal_bulan%")->sum('bayar');
        $pembelianBulan = Pembelian::where('created_at', 'LIKE', "%$tanggal_bulan%")->sum('bayar');
        $nilaiStok = Produk::select(DB::raw('sum(harga_beli*stok/jml_kemasan) as harga'))->first('harga');


        $pengunjung = Penjualan::where('created_at', 'LIKE', "%$tanggal_akhir%")->where('bayar', '>', "0")->count();

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan', 'produkStok', 'penjualanHari', 'penjualanBulan', 'pembelianBulan', 'pengunjung', 'nilaiStok'));
        } else {
            return view('kasir.dashboard');
        }
    }
}
