<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return Inertia::render('Products/Index', [
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
