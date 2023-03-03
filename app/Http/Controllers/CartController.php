<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function show(Cart $cart)
    {
        $cart = Cart::with('products')->findOrFail($cart->id);
        // dd($cart);
        return new CartResource($cart);
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
    public function getCartsByUser(Request $request)
    {
        $carts = Cart::with('products')->where('user_id', $request->userId)->get();
        // dd($carts);
        return CartResource::collection($carts);
    }
}
