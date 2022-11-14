<?php


namespace App\Customer\Profile\Requests;

use \Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
        return ['email' => 'required|email', 'password' => 'required|min:8'];
    }

    /**
     * Get the user login  model details to be mass persisted to the database from the request parameters.
     *
     * @return array
     */
    public function getUserLoginDTO()
    {
        return ['email' => $this->input('email'), 'password' => $this->input('password'),];
    }
}
