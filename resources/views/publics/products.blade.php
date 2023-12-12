 @extends('layouts.public')
 @section('content')

 @section('content')

     <div class="px-lg-5 container mt-5 px-4">
         <h1>Products</h1>
         <div class="row gx-4 gx-lg-6 row-cols-2 row-cols-md-4 row-cols-xl-4 justify-content-center">
             @foreach ($products as $product)
                 <div class="col mb-5">
                     <div class="card h-100">
                         <!-- Product image-->
                         <img class="card-img-top" src="https://source.unsplash.com/200x200/?fast-food?{{ $product->merk }}"
                             alt="{{ $product->nama_produk }}" />
                         <!-- Product details-->
                         <div class="card-body p-4">
                             <!-- Product name-->
                             <h5 class="fw-bolder card-title text-primary">{{ $product->nama_produk }}</h5>
                             <!-- Product price-->
                             <h6 class="price text-primary">
                                 {{ format_uang($product->harga_jual) }}/{{ $product->jml_kemasan }}
                                 Buah</h6>
                             <h6 class="price text-primary">{{ format_uang($product->harga_ecer) }}/Buah</h6>
                         </div>
                         <div class="card-footer text-center">
                             <form action="{{ url('/cart') }}" method="POST" class="side-by-side">
                                 {!! csrf_field() !!}
                                 <input type="hidden" name="id" value="{{ $product->id_produk }}">
                                 <input type="hidden" name="name" value="{{ $product->nama_produk }}">
                                 <input type="hidden" name="price" value="{{ $product->harga_jual }}">
                                 <button type="submit" class="btn btn-secondary text-Light" value=''><i
                                         class="bi bi-plus-circle-fill icon-blue"></i> Keranjang
                                 </button>
                                 {{-- <button>
                                     class="btn btn-outline-secondary border-start-0 rounded-pill ms-n3 border bg-white"
                                     type="button">
                                     <i class="fa fa-search"></i>
                                 </button> --}}
                             </form>
                             {{-- <form action="{{ url('/cart') }}" method="POST" class="side-by-side">
                                 {!! csrf_field() !!}
                                 <input type="hidden" name="id" value="{{ $product->id_produk }}">
                                 <input type="hidden" name="name" value="{{ $product->nama_produk }}">
                                 <input type="hidden" name="price" value="{{ $product->harga_jual }}">
                                 <input type="submit" class="btn btn-success btn-lg" value="Add to Cart">
                             </form> --}}

                             {{-- <a href="#" class="btn btn-secondary display-3">Tambahkan Keranjang</a> --}}
                         </div>
                     </div>
                 </div>
             @endforeach
         </div>
         <nav aria-label="Page navigation example">
             <ul class="pagination justify-content-center">
                 {!! $products->links() !!}
             </ul>
         </nav>
     </div>
 @endsection
