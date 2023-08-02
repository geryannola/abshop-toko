<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use DB;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $tanggal = date('Y-m-d');
        $penjualan = Penjualan::join('penjualan_detail', 'penjualan_detail.id_penjualan','=','penjualan.id_penjualan')->join('users', 'users.id', '=', 'penjualan.id_user')
        // ->select(DB::raw("SUM(penjualan_detail.subtotal) AS profit"))
        ->select('penjualan.created_at', 'penjualan.total_item', 'penjualan.bayar', 'penjualan.total_harga', 'penjualan.id_penjualan', 'users.name')
        // ->sum('profit')
        ->groupby('penjualan_detail.id_penjualan')
        ->where('penjualan.diterima', '!=', 0)
        ->where('penjualan.created_at', 'LIKE', "%$tanggal%")
        ->orderBy('penjualan.id_penjualan', 'desc')
        ->get();
        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('waktu', function ($penjualan) {
                return jam_indonesia($penjualan->created_at);
            })
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->bayar);
            })
            // ->addColumn('profit', function ($penjualan) {
            //     return 'Rp. ' . format_uang($penjualan->profit);
            // })
            // ->editColumn('diskon', function ($penjualan) {
            //     return $penjualan->diskon . '%';
            // })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="notaKecil2(`' . route('transaksi.nota_kecil2', $penjualan->id_penjualan) . '`)" class="btn btn-sm btn-secondary btn-flat"><i class="fa fa-print" aria-hidden="true"></i></button>
                   <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-eye"></i></button>  
                    <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member','profit'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        if ($request->diterima != 0) {
            if ($request->diterima >= $request->bayar) {
                $penjualan = Penjualan::findOrFail($request->id_penjualan);
                $penjualan->id_member = $request->id_member;
                $penjualan->total_item = $request->total_item;
                $penjualan->total_harga = $request->total;
                $penjualan->diskon = $request->diskon;
                $penjualan->bayar = $request->bayar;
                $penjualan->diterima = $request->diterima;
                $penjualan->update();

                $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
                foreach ($detail as $item) {
                    $item->diskon = $request->diskon;
                    $item->update();

                    $produk = Produk::find($item->id_produk);
                    $produk->stok -= $item->jumlah;
                    $produk->update();
                }
                return redirect()->route('transaksi.selesai');
            }
            return redirect()->route('transaksi.index');
            // return response()->json('Data berhasil disimpan', 200);
        } else {
            return redirect()->route('transaksi.index');
            // return response()->json('Data berhasil disimpan', 200);
        }
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
           
            ->addColumn('nama_produk', function ($detail) {
            $jenis = $detail->jenis;
            if ($jenis == "grosir") {
                $label = "label label-success";
            } else {
                $label = "label label-warning";
            }
                return '<span class="'. $label.'">' . strtoupper($detail->jenis) . '</span> ' . $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_beli);
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah/ $detail->jml_kemasan);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->addColumn('profit', function ($detail) {
               
                $profit = ($detail->subtotal-($detail->jumlah/ $detail->jml_kemasan* $detail->harga_beli));
                return 'Rp. ' . format_uang($profit);
            })
            ->rawColumns(['nama_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::latest('id_penjualan')->first();
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $penjualan->id_penjualan)->get();
        return view('penjualan.selesai', compact('setting', 'penjualan', 'detail'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();

        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaKecil2($id)
    {
        $setting = Setting::first();

        $penjualan = Penjualan::find($id);
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
        ->where('id_penjualan', $id)
        ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-' . date('Y-m-d-his') . '.pdf');
    }
}
