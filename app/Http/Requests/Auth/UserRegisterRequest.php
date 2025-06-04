<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma texto.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de e-mail válido.',
            'email.unique' => 'O email já está em uso.',
            'email.max' => 'O email não pode ter mais que 255 caracteres.',
            'password.required' => 'A senha é obrigatório.',
            'password.string' => 'A senha deve ser um texto com letras e numero.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.regex' => 'A senha deve conter ao menos uma letra maiúscula, uma minúscula, um número e um símbolo.'
        ];
    }

    /**
     * Define the request body parameters for Scribe.
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The name of the user.',
                'example' => 'John Doe',
            ],
            'email' => [
                'description' => 'The email of the user.',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'The password of the user.',
                'example' => 'Secret123**',
            ],
            'password_confirmation' => [
                'description' => 'The password confirmation.',
                'example' => 'Secret123**',
            ],
        ];
    }
}
