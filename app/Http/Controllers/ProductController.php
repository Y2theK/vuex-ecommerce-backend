<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
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

    
    public function store(ProductRequest $request)
    {
        $validated_data = $request->validated();
        // return $validated_data;
        $filename = uniqid()."-".$request->file('image')->getClientOriginalName();
        // return $filename;
        // $path = $request->file('image')->storeAs('products', $filename, 'public');
        $path = Storage::putFileAs("products", $request->file('image'), $filename, 'public');
        // return $path;
        $validated_data['image'] = "storage/$path";
        // dd($validated_data);
        $product =  Product::create($validated_data);
        return new ProductResource($product);
        // return $product;
    }

    
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    
    public function update(ProductRequest $request, Product $product)
    {
        // return $request->all();
        $validated_data = $request->validated();
        $validated_data['image'] = $product->image;
        if ($request->hasFile('image')) {
            Storage::delete($product->image);

            $filename = uniqid()."-".$request->file('image')->getClientOriginalName();
            $path = Storage::putFileAs('products', $request->file('image'), $filename, 'public');
            $validated_data['image'] = "storage/$path";
        }
        // return $validated_data;
        $product->update($validated_data);
        return new ProductResource($product);
    }

    
    public function destroy(Product $product)
    {
        //delete image
        Storage::delete($product->image);

        //delete in carts_product table
        // $product->carts()->detach($product->id);

        $product =  $product->delete();  //onDeleteCascade
        return response()->json(["message" => "Product Successfully Deleted"], 200);
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
