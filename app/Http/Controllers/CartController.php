<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::with('products');
        if ($request->startdate && $request->enddate) {
            $startDate = date($request->startdate);
            $endDate = date($request->enddate);
            $carts = $carts->whereBetween('date', [$startDate,$endDate]);
            // dd($carts);
        }
        if ($request->query('sort')) {
            $sort = strtolower($request->query('sort'));
            $carts = $carts->orderBy('id', $sort);
        }
        if ($request->query('limit')) {
            $limit = request()->query('limit');
            $carts = $carts->paginate($limit);
        } else {
            $carts = $carts->get();
        }
        return CartResource::collection($carts);
    }

    
    public function store(CartRequest $request)
    {
        // return $request;
        $validated_data = $request->validated();
        // return $validated_data['products'][1]['productId'];
        $cart = Cart::create($validated_data);
        // return $cart;

        //attach products
        // for ($i = 0; $i < count($validated_data['products']);$i++) {
        //     $cart->products()->attach(
        //         $validated_data['products'][$i]['productId'],
        //         ['quantity' => $validated_data['products'][$i]['quantity']]
        //     );
        // }
        
        //attach products
        $cart->products()->attach(
            $validated_data['products']
        );


        // $cart = Cart::with('products')->findOrFail($cart->id);
        // dd($cart);
        return new CartResource($cart);
    }
    

    
    public function show(Cart $cart)
    {
        $cart = Cart::with('products')->findOrFail($cart->id);
        // dd($cart);
        return new CartResource($cart);
    }

   
    public function update(CartRequest $request, Cart $cart)
    {
        $validated_data = $request->validated();
       
        //delete old related products in this cart
        $cart->products()->detach($cart->products);

        $cart->update($validated_data);
        
        //insert new products attachment
        $cart->products()->attach(
            $validated_data['products']
        );
        
        // $cart = Cart::with('products')->findOrFail($cart->id);
        // dd($cart);
        return new CartResource($cart);
    }

   
    public function destroy(Cart $cart)
    {
        //delete related products in this cart
        // for ($i = 0; $i < count($cart->products);$i++) {
        //     $cart->products()->detach($cart->products[$i]);
        // }

        //delete related products in this cart
        $cart->products()->detach($cart->products);

        $cart->delete();
        return response()->json([
            "message" => "Cart deleted successfully"
        ], 200);
    }
    public function getCartsByUser(Request $request)
    {
        $carts = Cart::with('products')->where('user_id', $request->userId)->get();
        // dd($carts);
        return CartResource::collection($carts);
    }
}
