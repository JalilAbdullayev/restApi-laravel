<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array {
        $method = $this->method();
        if($method === 'PUT') {
            return [
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255', Rule::in('I', 'B', 'i', 'b')],
                'email' => ['required', 'string', 'email', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'postal_code' => ['required', 'string', 'max:255'],
            ];
        }

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'string', 'max:255', Rule::in('I', 'B', 'i', 'b')],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'city' => ['sometimes', 'required', 'string', 'max:255'],
            'state' => ['sometimes', 'required', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }

    public function prepareForValidation(): void {
        if($this->postalCode) {
            $this->merge([
                'type' => Str::upper($this->type)
            ]);
        }
        if($this->type) {
            $this->merge([
                'postal_code' => $this->postalCode
            ]);
        }
    }
}
