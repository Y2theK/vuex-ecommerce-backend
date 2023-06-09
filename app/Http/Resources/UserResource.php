<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'username' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'userDetail' => [
                'company' => $this->company,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->country,
                'zip_code' => $this->zip_code
            ]
        ];
    }
}
