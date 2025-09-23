<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email'=>['required', 'email', 'exists:users'],
            'password' => ['required'],
        ];
    }
    public function messages():array
    {
        return[
            'email.exists' => 'ایمیل مورد نظر ثبت نشده است.',
            'email.email' => 'فرمت ایمیل نامعتبر است.',
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'password.required' => 'وارد کردن کالمه عبور الزامی است.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
