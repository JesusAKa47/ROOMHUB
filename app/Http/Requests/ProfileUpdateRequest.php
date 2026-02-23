<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        if ($this->user()->client_id) {
            $rules['phone'] = ['nullable', 'string', 'min:7', 'max:20', 'regex:/^[0-9\s\+\-]+$/'];
            $rules['gender'] = ['nullable', 'string', 'in:hombre,mujer,otro'];
        }

        $rules['postal_code'] = ['nullable', 'string', 'max:10'];
        $rules['state'] = ['nullable', 'string', 'max:100'];
        $rules['city'] = ['nullable', 'string', 'max:100'];
        $rules['municipality'] = ['nullable', 'string', 'max:100'];
        $rules['locality'] = ['nullable', 'string', 'max:150'];

        $rules['privacy_show_name_public'] = ['nullable', 'boolean'];
        $rules['privacy_show_location_public'] = ['nullable', 'boolean'];
        $rules['privacy_show_last_login'] = ['nullable', 'boolean'];
        $rules['locale'] = ['nullable', 'string', 'in:es,en'];
        $rules['timezone'] = ['nullable', 'string', 'max:50', 'timezone'];

        return $rules;
    }
}
