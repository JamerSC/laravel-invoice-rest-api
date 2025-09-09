<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
                    'amount'      => ['required', 'decimal:0,2'],
                    'status'      => ['in:billed,paid,void'],
                    'billed_date' => ['required', 'date_format:Y-m-d H:i:s'],
                    'paid_date'   => ['nullable', 'date_format:Y-m-d H:i:s'],
                    'customer_id' => ['required', 'exists:customers,id'],
                ];

            case 'PATCH':
                return
                [
                    'amount'      => ['sometimes', 'required', 'decimal:0,2'],
                    'status'      => ['sometimes', 'in:billed, paid, void'],
                    'billed_date' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
                    'paid_date'   => ['sometimes', 'nullable', 'date_format:Y-m-d H:i:s'],
                    'customer_id' => ['sometimes', 'required', 'exists:customers,id'],
                ];

            default:
                return [];
        }


    }

    protected function prepareForValidation()
    {
        if ($this->has('billedDate') && $this->has('paidDate') && $this->has('customerId'))
        {
            $this->merge([
                'billed_date' => $this->billedDate,
                'paid_date'   => $this->paidDate,
                'customer_id' => $this->customerId,
            ]);
        }     

    }
}
