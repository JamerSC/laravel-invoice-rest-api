<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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

        switch ($method)
        {
            case 'PUT':
                return 
                [
                    'name'     => ['required', 'string', 'max:50'],
                    'email'    => ['required', 'email', 'max:50'],
                    'password' => ['required', 'string', 'min:8', 'max:15', 'confirmed'],
                ];
            
            case 'PATCH':
                return 
                [
                    'name'     => ['sometimes', 'required', 'string', 'max:50'],
                    'email'    => ['sometimes', 'required', 'email', 'max:50'],
                    'password' => ['sometimes', 'required', 'string', 'min:8', 'max:15', 'confirmed'],
                ];
            
            default:
                return []; // abort(405, 'Invalid HTTP method'); 
        }
    }
}
