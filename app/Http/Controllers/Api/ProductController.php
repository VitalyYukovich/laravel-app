<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->paginate(10);
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }
}