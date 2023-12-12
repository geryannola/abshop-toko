<?php

namespace App\Models\Publics;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{

    public function getProducts($request)
    {
        $search = $request->input('cari');
        $order = $this->orderValidate($request);
        $products = DB::table('produk')
            ->select(DB::raw('produk.*'))
            ->orderBy($order['column'], $order['type'])
            ->where('hidden', '=', 'N')
            ->where('stok', '>', 0)
            ->when(
                $search,
                function ($query) use ($search) {
                    return $query->where('nama_produk', 'LIKE', "%$search%");
                }
            )
            ->paginate(16);
        return $products;
    }

    private function orderValidate($request)
    {
        $supportedColumns = [
            'created_at'
        ];
        $order_by = $request->input('order_by');
        $order_type = $request->input('type');
        if (in_array($order_by, $supportedColumns) && (strtolower($order_type) == 'asc' || strtolower($order_type) == 'desc')) {
            $order = [
                'column' => $order_by,
                'type' => $order_type
            ];
        } else {
            $order = [
                'column' => 'id_produk',
                'type' => 'asc'
            ];
        }
        return $order;
    }

    // public function getProduct($id)
    // {
    //     $product = DB::table('products')
    //         ->select(DB::raw('products.*, products_translations.name, products_translations.description, products_translations.price'
    //             . ', (SELECT name FROM categories_translations WHERE for_id = products.category_id AND locale= "' . app()->getLocale() . '") as category_name'
    //             . ', (SELECT for_id FROM categories_translations WHERE for_id = products.category_id AND locale= "' . app()->getLocale() . '") as category_id'
    //             . ', (SELECT url FROM categories WHERE id = products.category_id) as category_url'))
    //         ->where('products.id', '=', $id)
    //         ->where('products_translations.locale', '=', app()->getLocale())
    //         ->join('products_translations', 'products_translations.for_id', '=', 'products.id')
    //         ->first();
    //     return $product;
    // }

    public function getProductsWithTag($tag)
    {
        $products = DB::table('produk')
            ->select(DB::raw('produk.*'))
            ->where('tags', 'LIKE', '%' . $tag . '%')
            ->where('hidden', '=', 'N')
            ->limit(8)
            ->get();
        return $products;
    }

    // public function getMostSelledProducts()
    // {
    //     $products = DB::table('products')
    //         ->select(DB::raw('products.*, products_translations.name, products_translations.description, products_translations.price'))
    //         ->where('hidden', '=', 0)
    //         ->where('locale', '=', app()->getLocale())
    //         ->join('products_translations', 'products_translations.for_id', '=', 'products.id')
    //         ->orderBy('procurements', 'desc')
    //         ->limit(8)
    //         ->get();
    //     return $products;
    // }

    // public function getProductsWithIds($ids)
    // {
    //     $products = DB::table('products')
    //         ->select(DB::raw('products.*, products_translations.name, products_translations.description, products_translations.price'))
    //         ->where('hidden', '=', 0)
    //         ->whereIn('products.id', $ids)
    //         ->where('locale', '=', app()->getLocale())
    //         ->join('products_translations', 'products_translations.for_id', '=', 'products.id')
    //         ->get()->toArray();
    //     return $products;
    // }

    // public function getCategories()
    // {
    //     $categories = DB::table('categories')
    //         ->select(DB::raw('categories.*, categories_translations.name'))
    //         ->where('locale', '=', app()->getLocale())
    //         ->orderBy('position', 'desc')
    //         ->join('categories_translations', 'categories_translations.for_id', '=', 'categories.id')
    //         ->get()->toArray();
    //     return $categories;
    // }

    // public function getCategoryName($url)
    // {
    //     return DB::table('categories')
    //         ->where('locale', '=', app()->getLocale())
    //         ->join('categories_translations', 'categories_translations.for_id', '=', 'categories.id')
    //         ->where('url', $url)
    //         ->pluck('name')->toArray();
    // }
}
