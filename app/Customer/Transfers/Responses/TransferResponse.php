<?php


namespace App\Customer\Transfers\Responses;

use \Illuminate\Http\Resources\Json\JsonResource;

class TransferResponse extends JsonResource
{
    /**
     * Transform the customer account transfers resource into an array as response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource' => 'transfer',
            'resourceUrl' => $request->path(),
            'data' => [
                'id' => $this->id,
                'sendingAccountNumber' => $this->account->account_number,
                'sendingAccountName' => $this->account->account_name,
                'amount' => number_format($this->amount, 2),
                'transactionNumber' => $this->transaction_id,
                'datePayment' => date('Y-m-d', strtotime($this->created_at)),
                'transactionType' => $this->transfer_type,
                'paymentMotive' => $this->description,
                'receiverAccount' => [
                    'accountName' => $this->receiver->account_name,
                    'accountNumber' => $this->receiver->account_number
                ],
            ]
        ];
    }
}
