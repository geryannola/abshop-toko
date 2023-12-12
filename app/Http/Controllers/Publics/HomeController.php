<?php

namespace App\Http\Controllers\Publics;

use Illuminate\Http\Request;
use App\Models\Publics\ProductsModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $productsModel = new ProductsModel();
        $promoProducts = $productsModel->getProductsWithTag('promo');
        return view('publics.home', [
            'promoProducts' => $promoProducts,
        ]);
    }
}
