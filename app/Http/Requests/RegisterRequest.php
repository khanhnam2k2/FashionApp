<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . \App\Models\User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên của bạn',
            'name.max' => 'Tên không thể dài hơn 255 ký tự',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không thể dài hơn 255 ký tự',
            'email.unique' => 'Email này đã tồn tại',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];
    }
}
