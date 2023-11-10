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
            'images' => 'nullable|array|min:1|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
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
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'quantity.required' => 'Vui lòng nhập số lượng sản phẩm',
            'quantity.min' => 'Số lượng sản phẩm không được bé hơn 1',
            'quantity.*.required' => 'Vui lòng nhập số lượng sản phẩm',
            'quantity.*.integer' => 'Vui lòng nhập đúng kiểu số',
            'quantity.*.min' => 'Số lượng sản phẩm không được bé hơn 1',
            'sizes.required' => 'Vui lòng nhập size quần áo',
            'sizes.*.required' => 'Vui lòng nhập size quần áo',
            'images.min' => 'Tối thiểu 1 ảnh cho 1 sản phẩm',
            'images.max' => 'Tối đa 4 ảnh cho 1 sản phẩm',
            'images.*.image' => 'Vui lòng chọn đúng định dạng ảnh.',
            'images.*.mimes' => 'Ảnh phải theo các định dạng sau: jpeg, png, jpg, gif.',
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
