<?php


namespace App\Customer\Accounts\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
        return ['accountName' => 'required', 'accountCurrency' => 'required', 'accountCountry' => 'required',
            'customerId' => 'required', 'accountType' => 'required', 'initialDeposit' => 'required|digits'];
    }

    public function accountDTO()
    {
        return ['account_name' => $this->input('accountName'), 'currency' => $this->input('accountCurrency'),
            'country' => $this->input('accountCountry'), 'customer_id' => $this->input('customerId'),
            'account_type' => $this->input('accountType'), 'account_balance' => $this->input('initialDeposit')];
    }
}
