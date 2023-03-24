<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;

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
        $validated_data['transaction_id'] = Str::random(15);
       
        $cart = Cart::create($validated_data);
        
        $products = $validated_data['products'];
        $cart->products()->attach(
            $products
        );
        // return $cart;

        //attach products
        // for ($i = 0; $i < count($validated_data['products']);$i++) {
        //     $cart->products()->attach(
        //         $validated_data['products'][$i]['productId'],
        //         ['quantity' => $validated_data['products'][$i]['quantity']]
        //     );
        // }
        
        //attach products
       

        $cart = Cart::with('products')->findOrFail($cart->id);
        // dd($cart);
        return new CartResource($cart);
    }
    public function purchase(Request $request)
    {
        $user = $request->user();
        //user validation
        $validated_user = $request->validate([
            'name' => 'required|string',
            'email' => ['required',Rule::unique('users')->ignore($user)],
            'phone' => 'required|string',
            'company' => 'string|required',
            'address' => 'string | required',
            'city' => 'string | required',
            'state' => 'string | required',
            'zip_code' => 'numeric|required'
        ]);

        //update user
        $user->update($validated_user);

        $user->createAsStripeCustomer();

        $validated_cart = $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'total' => 'required|numeric',
            'payment_method_id' => 'required'
        ]);

        $payment = $user->charge(
            $request->input('total'),
            $request->input('payment_method_id')
        );

        $payment = $payment->asStripePaymentIntent();
        var_dump($payment);
        
        $validated_cart['user_id'] = $user->id;
        // $validated_cart['transaction_id'] = $payment->charges->data[0]->id;
        // $validated_cart['total'] = $payment->charges->data[0]->amount;
        $validated_cart['transaction_id'] = Str::random(25);
        //cart validation
        $cart = Cart::create($validated_cart);

        $products = $validated_cart['products'];
        $cart->products()->attach(
            $products
        );
        // var_dump($user);
        // var_dump($cart);
        $cart = Cart::with('products')->findOrFail($cart->id);
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
        
        $cart = Cart::with('products')->findOrFail($cart->id);
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
