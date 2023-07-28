<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('produk', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $jenis = $item->jenis;
            if ($jenis == "grosir") {
                $label = "label label-success";
                $harga = $item->harga_jual;
            } else {
                $label = "label label-warning";
                $harga = $item->harga_jual;
            }
            $row = array();
            // $row['kode_produk'] = '<span class="' . $label . '">' . $item->produk['kode_produk'] . '</span>';
            $row['nama_produk'] = '<span class="' . $label . '">' . $item->produk['nama_produk'] . '</span>' . ' (' . $item->produk['jml_kemasan'] . ') Rp.' . format_uang($item->produk['harga_beli']) . ' Rp.' . format_uang($harga) . ' Stok=' . format_uang($item->produk['stok'] / $item->produk['jml_kemasan']) . '/' . format_uang($item->produk['stok']);

            $row['harga_jual']  = '<input type="number" class="form-control input-sm harga_jual" data-id="' . $item->id_penjualan_detail . '" value="' . $item->harga_jual . '">';
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_penjualan_detail . '" data-stok="' . $item->stok / $item->jml_kemasan . '" value="' . $item->jumlah / $item->jml_kemasan . '">';
            $row['subtotal']    = format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah / $item->jml_kemasan;
            $total_item += $item->jumlah / $item->jml_kemasan;
        }
        $data[] = [
            'jumlah' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            // 'jumlah'      => '',
            'kode_produk'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'nama_produk', 'jumlah', 'harga_jual'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (!$produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $penjualan = PenjualanDetail::where('id_penjualan', $request->id_penjualan)->where('id_produk', $request->id_produk)->first();
        if (!$penjualan) {
            if ($request->jenis == 'grosir') {
                $jml_kemasan = $produk->jml_kemasan;
                $jumlah = $produk->jml_kemasan;
                $harga_jual = $produk->harga_jual;
            } else {
                $jml_kemasan = 1;
                $jumlah = 1;
                $harga_jual = $produk->harga_ecer;
            }
            $detail = new PenjualanDetail();
            $detail->id_penjualan = $request->id_penjualan;
            $detail->id_produk = $produk->id_produk;
            $detail->harga_jual = $harga_jual;
            $detail->jml_kemasan = $jml_kemasan;
            $detail->jenis = $request->jenis;
            $detail->jumlah = $jumlah;
            $detail->diskon = 0;
            $detail->subtotal = $harga_jual;
            $detail->save();
        } else {
            $detail = PenjualanDetail::find($penjualan->id_penjualan_detail);
            $detail->jumlah = $detail->jml_kemasan + $detail->jumlah;
            $detail->subtotal = $detail->harga_jual * $detail->jumlah / $detail->jml_kemasan;
            $detail->update();
        }
        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        // if ($request->jumlah * $detail->jml_kemasan <= 500) {
        if ($request->jumlah != NULL) {
            $detail->jumlah = $request->jumlah * $detail->jml_kemasan;
            $detail->subtotal = $detail->harga_jual * $detail->jumlah / $detail->jml_kemasan;
            $detail->update();
        } else {
            $detail->harga_jual = $request->harga_jual;
            $detail->subtotal = $request->harga_jual * $detail->jumlah / $detail->jml_kemasan;
            $detail->update();
        }
        // }
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}
