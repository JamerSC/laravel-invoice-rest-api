<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name'        => ['required','string'],
            'type'        => ['in:individual,business'],
            'address'     => ['required','string'],
            'city'        => ['required','string'],
            'province'    => ['required','string'],
            'postal_code' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'postal_code' => $this->postalCode,
        ]);
    }
}
