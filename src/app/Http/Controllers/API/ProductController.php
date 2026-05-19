<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/products - список товаров
     */
    public function index(): ProductCollection
    {
        $products = Product::with('category')->paginate(10);

        return new ProductCollection($products);
    }

    /**
     * GET /api/products/{id} - просмотр одного товара
     */
    public function show(Product $product): ProductResource|JsonResponse
    {
        return new ProductResource($product->load('category'));
    }

    /**
     * POST /api/products - создание товара
     */
    public function store(StoreProductRequest $request): ProductResource|JsonResponse
    {
        $product = Product::create($request->validated());

        return new ProductResource($product->load('category'));
    }

    /**
     * PUT/PATCH /api/products/{id} - обновление товара
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource|JsonResponse
    {
        $product->update($request->validated());

        return new ProductResource($product->load('category'));
    }

    /**
     * DELETE /api/products/{id} - удаление товара
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->success(null, 'Product deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
