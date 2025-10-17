<?php

namespace App\Http\Requests\UserGroup;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()?->hasPermissionTo('update-user-group');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Unique rules ignore the current model id (supports route model binding or id param).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // get current id from route (supports model binding or plain id)
        $id = $this->route('userGroup')?->id;

        return [
            'name' => [
                'required',
                'min:3',
                'max:225',
                'string',
                'unique:user_groups,name,'.$id,            ],
            'display_name' => [
                'required',
                'min:3',
                'max:225',
                'string',
                'unique:user_groups,display_name,'.$id,
            ],
        ];
    }

    /**
     * Custom Persian validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'فیلد نام گروه الزامی است.',
            'name.min' => 'طول نام گروه باید حداقل :min کاراکتر باشد.',
            'name.max' => 'طول نام گروه نباید بیشتر از :max کاراکتر باشد.',
            'name.string' => 'نام گروه باید یک رشته متنی باشد.',
            'name.unique' => 'این نام گروه قبلاً ثبت شده است.',

            'display_name.required' => 'فیلد نام نمایشی الزامی است.',
            'display_name.min' => 'طول نام نمایشی باید حداقل :min کاراکتر باشد.',
            'display_name.max' => 'طول نام نمایشی نباید بیشتر از :max کاراکتر باشد.',
            'display_name.string' => 'نام نمایشی باید یک رشته متنی باشد.',
            'display_name.unique' => 'این نام نمایشی قبلاً ثبت شده است.',
        ];
    }

    /**
     * Human-friendly attribute names (Persian).
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام گروه',
            'display_name' => 'نام نمایشی',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 400)
        );
    }
}
