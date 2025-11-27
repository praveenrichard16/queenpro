<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) ($this->user()?->is_admin ?? false);
    }

    public function rules(): array
    {
		$isSuperAdmin = (bool) ($this->user()?->is_super_admin ?? false);

		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:users,email'],
			'phone' => ['nullable', 'string', 'max:64'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => ['required'],
			'avatar' => ['nullable', 'image', 'max:2048'],
		];

		if ($isSuperAdmin) {
			$rules['role'] = ['required', 'in:admin,staff,customer'];
			$rules['designation'] = ['nullable', 'string', 'max:255'];
		} else {
			$rules['role'] = ['prohibited'];
			$rules['designation'] = ['prohibited'];
		}

		return $rules;
    }
}

