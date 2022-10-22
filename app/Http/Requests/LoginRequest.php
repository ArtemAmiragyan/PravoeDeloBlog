<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Additional check for default option
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        if ($validator->errors()->count()) {
            return;
        }

        $validator->after(function ($validator) {
            $email = strtolower(request('email'));

            /** @var User $user */
            $user = User::whereRaw("lower(email) = '{$email}'")->first();

            if (Hash::check(request('password'), $user?->password)) {
                return;
            }

            $validator->errors()->add('email', __('auth.failed'));
        });
    }

    /**
     * @return User
     */
    public function getLoginUser(): User
    {
        return User::whereRaw("LOWER(email) = LOWER(?)", [$this->input('email')])->firstOrFail();
    }
}
