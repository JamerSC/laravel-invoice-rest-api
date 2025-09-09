<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $method = $this->method();

        switch ($method) {
            case 'PUT':
                return 
                [
                   'name'        => ['required', 'string'],
                   'type'        => ['in:individual, business'],
                   'address'     => ['required', 'string'],
                   'city'        => ['required', 'string'],
                   'province'    => ['required', 'string'],
                   'postal_code' => ['required', 'string'],
                ];

            case 'PATCH':
                return 
                [
                   'name'        => ['sometimes', 'required', 'string'],
                   'type'        => ['sometimes', 'in:individual, business'],
                   'address'     => ['sometimes', 'required', 'string'],
                   'city'        => ['sometimes', 'required', 'string'],
                   'province'    => ['sometimes', 'required', 'string'],
                   'postal_code' => ['sometimes', 'required', 'string'],
                ];
            
            default:
                return []; //abort(405, 'Invalid HTTP method');
        }

    }

    protected function prepareForValidation()
    {
        // $this->merge([
        //     'postal_code' => $this->postalCode,
        // ]);

        // or
        if($this->has('postalCode'))
        {
            $this->merge([
                'postal_code' => $this->postalCode,
            ]);
        }
    }
}
