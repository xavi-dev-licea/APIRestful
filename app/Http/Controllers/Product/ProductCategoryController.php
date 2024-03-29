<?php

namespace APIRestful\Http\Controllers\Product;

use APIRestful\Product;
use APIRestful\Category;
use Illuminate\Http\Request;
use APIRestful\Http\Controllers\APIController;

class ProductCategoryController extends APIController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }

    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }


    public function update(Request $request, Product $product, Category $category)
    {
        $product->categories()
            ->syncWithoutDetaching([ $category->id ]);

        return $this->showAll($product->categories);
    }


    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('Este producto no tiene asociada la categoría especificada', 404);
        }

        $product->categories()->detach([
            $category->id
        ]);

        return $this->showAll($product->categories);
    }
}
