<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //modify the  products array to the format I want
        $data = parent::toArray($request);
        $products = [];
        // dd(($data['products']));
        for ($i = 0;$i < count($data['products']);$i++) {
            // dd($data['products'][$i]);
            $products[$i]['productId'] = $data['products'][$i]['id'];
            $products[$i]['title'] = $data['products'][$i]['title'];
            $products[$i]['price'] = $data['products'][$i]['price'];
            $products[$i]['image'] = $data['products'][$i]['image'];
            $products[$i]['quantity'] = $data['products'][$i]['pivot']['quantity'];
        }
        // dd($products);
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'date' => $this->date,
            'products' => $products
            ];
    }
}
