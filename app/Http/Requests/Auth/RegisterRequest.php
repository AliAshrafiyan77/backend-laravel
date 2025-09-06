<?php

namespace App\Http\Requests\Auth;

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
    public function rules(): array
    {
        return [
            'name' =>['required' , 'string' , 'max:255'],
            'email' =>['required' , 'email' , 'max:255' , 'unique:users'],
            'password' =>['required' , 'min:8' , 'max:255' , 'confirmed'],
        ];
    }
    public function messages():array
    {
        return[
            'name.required' => 'وارد کردن نام اجباری است.',
            'name.max' => 'نام حداکثر باید ۲۵۵ کاراکتر باشد.',
            'email.required' => 'وارد کردن ایمیل اجباری است.',
            'email.email' => 'ایمیل صحیح نمی باشد.',
            'email.unique' => 'ایمیل وارد شدخ قبلا ثبت نام شده است.',
            'password.required' => 'وارد کردن گذرواژه اجباری است.',
            'password.min' => 'گذرواژه حداقل باید ۸ کاراکتر باشد.',
            'password.max' => 'گذرواژه وارد حداکثر باید ۲۵۵ کاراکتر باشد.',
            'password.confirmed' => 'تاییدیه گذرواژه صحیح نمی باشد.',
        ];
    }
}
