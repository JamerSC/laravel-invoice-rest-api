<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class PayfusionPaymentRequest extends FormRequest
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
            'reference_id'       => 'required|string',
            'channel_code'       => 'required|string',
            'first_name'         => 'required|string',
            'last_name'          => 'required|string',
            'email'              => 'required|email',
            'payment_token_id'   => 'required|string',
            'amount'             => 'required|numeric|min:1',
            'success_return_url' => 'required|url',
            'failure_return_url' => 'required|url',
            'cancel_return_url'  => 'required|url',
            'description'        => 'nullable|string',
        ];
    }
}
