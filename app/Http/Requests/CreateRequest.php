<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateRequest extends FormRequest
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
        return [
            'companyName' => 'required|unique:'.Company::DB.'|max:255',
            'companyRegistrationNumber' => 'required|unique:'.Company::DB.'|max:255',
            'companyFoundationDate' => 'required',
            'country' => 'required',
            'zipCode' => 'required',
            'city' => 'required',
            'streetAddress' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'companyOwner' => 'required',
            'employees' => 'required',
            'activity' => 'required',
            'active' => 'required',
            'email' => 'required|email|unique:'.Company::DB,
            'password' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
