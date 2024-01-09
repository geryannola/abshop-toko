<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function store($product_id, $product_name, $product_price)
    {
        Cart::add($product_id, $product_name, $product_price)->associate('App/Model/Publics/ProductsModel');
        session()->flash('success_message', 'Item added in Cart');
        return redirect()->route('products.cart');
    }
}
