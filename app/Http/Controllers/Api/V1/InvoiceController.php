<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        
        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        // validate fields
        $fields = $request->validated();

        // create and add user id
        $invoice = $request->user()->invoices()->create($fields);

        // created response
        return response()->json([
            'message' => 'Create invoice successfully!',
            'invoice' => new InvoiceResource($invoice),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(
                ['message'=> 'Invoice id not found $id'],
                404);
        }

        return response()->json(
            new InvoiceResource($invoice),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, string $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'message'=> 'Invoice not found!'
            ],404);
        }

        $invoice->update($request->only([
            'amount',
            'billed_date',
            'paid_date',
            'customer_id',
        ]));

        return response()->json([
            'message'=> 'Invoice updated successfully!',
            'invoice' => new InvoiceResource($invoice),
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'message'=> 'Invoice not found!'
            ],404);
        }

        $invoice->delete();

        return response()->json([
            'message'=> 'Deleted successfully!',
            'invoice' => $invoice
        ],204);
    }
}
