<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class UpdateProductRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:20000',
            'price' => 'required',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'sizes' => 'required',
            'sizes.*' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name product is required',
            'price.required' => 'Price product is required',
            'quantity.required' => 'The quantity field is required.',
            'quantity.array' => 'The quantity must be an array.',
            'quantity.min' => 'The quantity must have at least 1 element.',
            'quantity.*.required' => 'Each quantity element is required.',
            'quantity.*.integer' => 'Each quantity element must be an integer.',
            'quantity.*.min' => 'Each quantity element must have a minimum value of 1.',
            'sizes.required' => 'Size product is required',
            'sizes.*.required' => 'Each size element is required.',
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
