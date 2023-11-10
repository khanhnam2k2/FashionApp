<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required',
            'contentPost' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:20000',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề cho bài viết',
            'contentPost.required' => 'Vui lòng nhập nội dung bài viết',
            'image.image' => 'Ảnh đại diện phải có định dạng là ảnh',
            'image.mines' => 'Ảnh đại diện phải thuộc các định dạng sau: jpeg, jpg, png, gif',
            'image.max' => 'Ảnh đại diện không được vượt quá 20MB',
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
