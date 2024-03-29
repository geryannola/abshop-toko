<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table-striped table-bordered table-produk table">
                    <thead>
                        <th>Batch</th>
                        <th>Nama</th>
                        <th>Harga Jual <br> Stok</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                            <tr>

                                <td> {{ $item->batch }}
                                </td>
                                <td> {{ $item->nama_produk }} ({{ $item->jml_kemasan }})<br>Rp.
                                    {{ format_uang($item->harga_beli) }}
                                </td>
                                <td><span class="label label-success">{{ format_uang($item->harga_jual) }}</span> /
                                    <span class="label label-warning">{{ format_uang($item->harga_ecer) }}</span><br>
                                    Stok : {{ format_uang(floor($item->stok / $item->jml_kemasan)) }} /
                                    {{ format_uang($item->stok - floor($item->stok / $item->jml_kemasan) * $item->jml_kemasan) }}
                                </td>
                                <td>
                                    @if ($item->stok / $item->jml_kemasan >= 1)
                                        <a href="#" class="btn btn-success btn-xs btn-flat"
                                            onclick="pilihProduk('{{ $item->id_produk }}', '')">
                                            <i class="fa fa-check-circle"></i>
                                            Grosir
                                        </a>
                                    @endif
                                    <a href="#" class="btn btn-warning btn-xs btn-flat"
                                        onclick="pilihProdukEcer('{{ $item->id_produk }}', '')">
                                        Eceran
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
