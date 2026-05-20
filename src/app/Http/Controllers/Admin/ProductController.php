<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index()
    {
        $products = $this->productService->getAllPaginated();
        $categories = Category::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create($request->validated());

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated());

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products.index');
    }
}
