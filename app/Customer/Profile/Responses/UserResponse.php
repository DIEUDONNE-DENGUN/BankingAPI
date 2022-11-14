<?php


namespace App\Customer\Profile\Responses;

use \Illuminate\Http\Resources\Json\JsonResource;

class UserResponse extends JsonResource
{
    /**
     * Transform the customer profile resource into an array as response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource' => 'profile',
            'resourceUrl' => $request->path(),
            'data' => [
                'customerId' => $this->id,
                'customerName' => $this->name,
                'customerEmail' => $this->email,
                'customerPhoneNumber' => $this->phone_number,
                'customerType' => $this->customer_type,
                'accountStatus' => $this->status,
            ]
        ];
    }
}
