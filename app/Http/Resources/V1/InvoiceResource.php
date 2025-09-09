<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'amount'      => $this->amount,
            'status'      => $this->status,
            'billedDate'  => $this->billed_date,
            'paidDate'    => $this->paid_date,
            'customerId'  => $this->customer_id,
            'createdById' => $this->user_id,
            'createdDate' => $this->created_at->toDateTimeString(),
            'updatedDate' => $this->updated_at->toDateTimeString(),
        ];
    }
}
