 @extends('layouts.public')
 @section('content')

 @section('content')

     <div class="px-lg-5 container mt-5 px-4">
         <h1>Promo products</h1>
         <div class="row gx-4 gx-lg-6 row-cols-2 row-cols-md-4 row-cols-xl-4 justify-content-center">
             @foreach ($promoProducts as $promoProduct)
                 <div class="col mb-5">
                     <div class="card h-100">
                         <!-- Product image-->
                         <img class="card-img-top"
                             src="https://source.unsplash.com/200x200/?fast-food?{{ $promoProduct->merk }}"
                             alt="{{ $promoProduct->nama_produk }}" />
                         <!-- Product details-->
                         <div class="card-body p-4">
                             <div class="text-center">
                                 <!-- Product name-->
                                 <h5 class="fw-bolder">{{ $promoProduct->nama_produk }}</h5>
                                 <!-- Product price-->
                                 <span
                                     class="price">{{ format_uang($promoProduct->harga_jual) }}/{{ $promoProduct->jml_kemasan }}
                                     Buah</span><br>
                                 <span class="price">{{ format_uang($promoProduct->harga_ecer) }}/Buah</span>
                             </div>
                         </div>
                         <!-- Product actions-->
                         <div class="card-footer border-top-0 bg-transparent p-4 pt-0">
                             <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View
                                     options</a></div>
                         </div>
                     </div>
                 </div>
             @endforeach
         </div>
     </div>
 @endsection
