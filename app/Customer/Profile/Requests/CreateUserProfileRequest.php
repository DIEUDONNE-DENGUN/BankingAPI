<?php


namespace App\Customer\Profile\Requests;

use \Illuminate\Foundation\Http\FormRequest;

class CreateUserProfileRequest extends FormRequest
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
        return ['customerName' => 'required', 'customerPhoneNumber' => 'required|min:9', 'customerEmail' => 'required|email',
            'password' => 'required|min:8', 'customerType' => 'required',];
    }

    /**
     * Get the use profile model details to be mass persisted to the database from the request parameters.
     *
     * @return array
     */
    public function getUserProfileDTO()
    {
        return ['name' => $this->input('customerName'), 'phone_number' => $this->input('customerPhoneNumber'),
            'email' => $this->input('customerEmail'), 'password' => $this->input('password'),
            'customer_type' => $this->input('customerType')];
    }
}
