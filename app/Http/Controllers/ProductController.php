<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get products with their category
        // dd(request()->query('limit'));
        
        $products = Product::with('category');
        // querying with sorting
        if (request()->query('sort')) {
            $sort = strtolower(request()->query('sort'));
            $products = $products->orderBy('id', $sort);
        }
        //querying with limitation
        if (request()->query('limit')) {
            $limit = request()->query('limit');
            $products =$products->paginate($limit);
        } else {
            $products = $products->get();
        }
        // $products = $products->get();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getProductsByCategory(Request $request)
    {
        $categoryName = $request->category;
        $products = Product::with('category')->whereHas('category', function ($query) use ($categoryName) {
            $query->where('name', $categoryName);
        })->get();
        // dd($products);
        return ProductResource::collection($products);
    }
}
