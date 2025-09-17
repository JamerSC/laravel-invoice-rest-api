<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class PayfusionInvoiceRequest extends FormRequest
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
            'external_id'            => 'required|string',
            'amount'                 => 'required|numeric|min:1',
            'payer_email'            => 'required|email',
            'description'            => 'nullable|string',
            'mid_label'              => 'nullable|string',
            'expiry_date'            => 'required|date_format:Y-m-d\TH:i:s\Z', // ISO 8601
            'success_redirect_url'   => 'required|url',
            'failure_redirect_url'   => 'required|url',

            // Array validations
            'payment_methods'        => 'required|array|min:1',
            'payment_methods.*'      => 'string',

            'items'                  => 'required|array|min:1',
            'items.*.name'           => 'required|string',
            'items.*.quantity'       => 'required|integer|min:1',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.category'       => 'nullable|string',
            'items.*.url'            => 'nullable|url',

            'fees'                   => 'nullable|array',
            'fees.*.type'            => 'required_with:fees|string',
            'fees.*.value'           => 'required_with:fees|numeric',

            'metadata'               => 'nullable|array',
        ];
    }
}
