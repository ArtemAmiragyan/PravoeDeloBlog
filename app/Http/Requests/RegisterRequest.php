<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique((new User())->getTable()),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],

            'password_confirmation' => [
                'required_with:password',
                'same:password'
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
