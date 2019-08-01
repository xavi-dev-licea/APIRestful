<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

class ProductCategoryController extends APIController
{

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


    public function destroy(Product $product)
    {
        //
    }
}
