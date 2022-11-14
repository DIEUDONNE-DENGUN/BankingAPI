<?php


namespace App\Customer\Accounts\Responses;


use App\Customer\Profile\Responses\UserResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResponse extends JsonResource
{
    /**
     * Transform the customer account resource into an array as response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource' => 'account',
            'resourceUrl' => $request->path(),
            'data' => [
                'id' => $this->id,
                'accountHolderNumber' => $this->account_number,
                'accountHolderName' => ucwords($this->account_name),
                'accountType' => $this->account_type,
                'accountBalance' => number_format($this->account_balance,2),
                'accountCountry' => $this->country,
                'accountCurrency' => strtoupper($this->currency),
                'dateOfCreation' => date('Y-m-d', strtotime($this->created_at)),
                'accountOwner' => new UserResponse($this->customer),
            ]
        ];
    }
}
