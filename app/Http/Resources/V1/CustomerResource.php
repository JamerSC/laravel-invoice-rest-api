<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name'        => $this->name,
            'type'        => $this->type,
            'address'     => $this->address,
            'city'        => $this->city,
            'province'    => $this->province,
            'postalCode'  => $this->postal_code,
            'createdById' => $this->user_id,
            'createdDate' => $this->created_at->toDateTimeString(),
            'updatedDate' => $this->updated_at->toDateTimeString(),
            'invoices'    => InvoiceResource::collection($this->whenLoaded('invoices')),
        ];
    }
}
