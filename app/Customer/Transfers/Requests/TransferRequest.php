<?php


namespace App\Customer\Transfers\Requests;

use \Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['receiverAccountNumber' => 'required|min:16', 'amount' => 'required|integer', 'paymentReason' => 'required|max:100',];
    }

    /**
     * Get the transfer model details to be mass persisted to the database from the request parameters.
     *
     * @return array
     */
    public function getTransferDTO()
    {
        return ['receiver_id' => $this->input('receiverAccountNumber'), 'amount' => $this->input('amount'), 'description' => $this->input('paymentReason')];
    }
}
