<?php


namespace App\Customer\Accounts\Requests;


class UpdateAccountRequest
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
        return ['accountName' => 'required', 'initialDeposit' => 'required|digits'];
    }

    /**
     * Get the account model details to be mass persisted to the database from the request parameters.
     *
     * @return array
     */
    public function getAccountDTO()
    {
        return ['account_name' => $this->input('accountName'), 'account_balance' => $this->input('initialDeposit')];
    }

}
