<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) ($this->user()?->is_admin ?? false);
    }

    public function rules(): array
    {
        $userId = (int) $this->route('user')?->id;
        $isSuperAdmin = (bool) ($this->user()?->is_super_admin ?? false);

		$rules = [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
			'phone' => ['nullable', 'string', 'max:64'],
			'password' => ['nullable', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => ['required_with:password'],
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

