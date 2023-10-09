<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:20000',
            'price' => 'required',
            'quantity' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name product is required',
            'price.required' => 'Price product is required',
            'price.required' => 'Quantity product is required',
            'image.required' => 'Image product is required',
            'image.mines' => 'Image format must be jpeg, jpg, png, gif',
            'image.max' => 'The maximum size of a photo is 20MB',
        ];
    }
    /**
     * [failedValidation [Overriding the event validator for custom error response]]
     * @param  Validator $validator [description]
     * @return [object][object of various validation errors]
     */
    public function failedValidation(Validator $validator)
    {
        //write your business logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST));
    }
}
