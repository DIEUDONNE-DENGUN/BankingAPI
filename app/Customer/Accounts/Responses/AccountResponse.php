<?php


namespace App\Customer\Accounts\Responses;


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
                'accountHolderName' => $this->account_name,
                'accountType' => $this->account_type,
                'accountBalance' => $this->account_balance,
                'accountCountry' => $this->country,
                'accountCurrency' => $this->currency,
                'accountOwner' => (array)$this->customer,
                'dateOfCreation' => $this->created_at,
            ]
        ];
    }
}
