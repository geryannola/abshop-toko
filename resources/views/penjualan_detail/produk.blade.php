<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th>Nama</th>
                        <th>Harga Jual <br> Stok</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($produk as $key => $item)
                        <tr>
                            <!-- <td width="5%">{{ $key+1 }}</td> -->
                            <!-- <td><span class="label label-success">{{ $item->kode_produk }}</span></td> -->
                            <td>{{ $item->nama_produk }}<br>{{ format_uang($item->harga_beli) }} </td>
                            <td>{{ format_uang($item->harga_jual) }} / {{ format_uang($item->harga_ecer) }} <br> S : {{ format_uang($item->stok/$item->jml_kemasan) }} / {{ format_uang($item->stok) }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-xs btn-flat" onclick="pilihProduk('{{ $item->id_produk }}', '{{ $item->kode_produk }} - {{ $item->nama_produk }}')">
                                    <i class="fa fa-check-circle"></i>
                                    Grosir
                                </a>
                                <a href="#" class="btn btn-danger btn-xs btn-flat" onclick="pilihProdukEcer('{{ $item->id_produk }}', '{{ $item->kode_produk }} - {{ $item->nama_produk }}')">
                                    <i class="fa fa-check-circle"></i>
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