<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;
use PDF;

use Illuminate\Support\Facades\DB;

class KartustokController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::all()->pluck('nama_produk', 'id_produk');
        $id_produk = 4;
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');
        $nama_produk = 0;

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $id_produk = $request->id_produk;
            $produk_name = Produk::find($id_produk);
            // $produk_name = Produk::select('nama_produk')
            // ->where('id_produk',$id_produk)
            // ->get();
            $nama_produk = $produk_name->nama_produk;
        }

        return view('kartustok.index', compact('id_produk','produk','tanggalAwal', 'tanggalAkhir', 'nama_produk'));
    }

    // public function getData($produk,$awal, $akhir)
    // {
    //     $no = 1;
    //     $data = array();
    //     $pendapatan = 0;
    //     $total_pendapatan = 0;
    //     $total_profit = 0;

    //     while (strtotime($awal) <= strtotime($akhir)) {
    //         $tanggal = $awal;
    //         $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

    //         $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
    //         $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
    //         $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

    //         $profit = PenjualanDetail::JOIN('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')->where('penjualan_detail.created_at', 'LIKE', "%$tanggal%")->where('penjualan.diterima', '!=', 0)
    //         ->sum(DB::raw('penjualan_detail.subtotal-(penjualan_detail.jumlah/penjualan_detail.jml_kemasan*penjualan_detail.harga_beli)'));

    //         $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
    //         $total_pendapatan += $pendapatan;
    //         $total_profit += $profit;

    //         $row = array();
    //         $row['DT_RowIndex'] = $no++;
    //         $row['tanggal'] = tanggal_indonesia($tanggal, false);
    //         $row['penjualan'] = format_uang($total_penjualan);
    //         $row['pembelian'] = format_uang($total_pembelian);
    //         $row['pengeluaran'] = format_uang($total_pengeluaran);
    //         $row['pendapatan'] = format_uang($pendapatan);
    //         $row['profit'] = format_uang($profit);

    //         $data[] = $row;
    //     }

    //     $data[] = [
    //         'DT_RowIndex' => '',
    //         'tanggal' => '',
    //         'penjualan' => '',
    //         'pembelian' => '',
    //         'pengeluaran' => 'Total Pendapatan',
    //         'pendapatan' => format_uang($total_pendapatan),
    //         'profit' => format_uang($total_profit),

    //     ];

    //     return $data;
    // }

    public function data($id, $awal, $akhir)
    {
        // $produk = PenjualanDetail::select('id_pembelian_detail, created_at, jml_kemasan, id_produk, jumlah, keluar')->get();
        $silver = DB::table("penjualan_detail")
        ->select(
            "penjualan_detail.id_penjualan_detail",
            "penjualan_detail.id_produk",
            "penjualan_detail.jenis",
            "penjualan_detail.created_at",
            "penjualan_detail.masuk",
            "penjualan_detail.jumlah",
            "penjualan_detail.stok_akhir"
            )
            ->where('id_produk',$id)
            ->whereDate('created_at','>=', $awal)
            ->whereDate('created_at','<=', $akhir);

            $gold = DB::table("pembelian_detail")
            ->select(
                "pembelian_detail.id_pembelian_detail",
                "pembelian_detail.id_produk",
                "pembelian_detail.jml_kemasan",
                "pembelian_detail.created_at",
                "pembelian_detail.jumlah",
                "pembelian_detail.keluar",
            "pembelian_detail.stok_akhir"
        )
            ->union($silver)
            ->where('id_produk', $id)
            ->whereDate('created_at', '>=', $awal)
            ->whereDate('created_at', '<=', $akhir)
            // ->where('id_produk', $id)
            ->orderBy('created_at', 'DESC')
            ->get();
            // var_dump($gold);
            // die();

        return datatables()
            ->of($gold)
            ->addIndexColumn()
            ->addColumn('select_all', function ($gold) {
                return '
                    <input type="checkbox" name="id_member[]" value="' . $gold->id_pembelian_detail . '">
                ';
            })

            ->addColumn('aksi', function ($gold) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('member.update', $gold->id_pembelian_detail) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('member.destroy', $gold->id_pembelian_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}
