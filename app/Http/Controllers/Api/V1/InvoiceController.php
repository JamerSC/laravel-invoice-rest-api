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
    public function index(Request $request)
    {
        // query customers
        $query = Invoice::query();

        // pagination
        $perPage = min($request->get('per_page', 10), 100);
        
        // filtering
        if ($request->has('status'))
        {
            $query->where('status', $request->get('status'));
        }

        // sorting
        $sort = $request->get('sort', '-id');
        $direction = $request->get($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        $query->orderBy($column, $direction);

        // search
        // if ($search = $request->get('q'))
        // {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('amount', 'like', "%{$search}%");
        //     });
        // }

        $invoices = $query->paginate($perPage); // or $invoices = Invoice::all(); 

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
                ['message' => 'Invoice id not found!'],
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
                'message' => 'Invoice not found!'
            ],404);
        }

        // $invoice->update($request->only([
        //     'amount',
        //     'status',
        //     'billed_date',
        //     'paid_date',
        //     'customer_id',
        // ]));

        $data = $request->only([
            'amount',
            'status',
            'billed_date',
            'paid_date',
            'customer_id',
        ]);

        // if the status is paid set paid date now
        if ($data['status'] ?? null === 'paid' && !$invoice->paid_date)
        {
            $data['paid_date'] = now();
        }

        // if the status is paid set paid date now
        if ($data['status'] ?? null === 'void' && !$invoice->paid_date)
        {
            $data['paid_date'] = now();
        }

        $invoice->update($data);

        return response()->json([
            'message' => 'Invoice updated successfully!',
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
                'message' => 'Invoice not found!'
            ],404);
        }

        $invoice->delete();

        return response()->json([
            'message' => 'Deleted successfully!',
            'invoice' => $invoice
        ],204);
    }

    // custom function

    public function markAsPaid(string $id)
    {
        $invoice = Invoice::find($id);

        // check if not exist
        if (!$invoice) 
        {
            return response()->json([
                'message' => 'Invoice not found!'
            ], 404);
        }

        // check if status not billed
        if ($invoice->status !== 'billed') 
        {
            return response()->json([
                'message' => 'Only billed invoices can be marked as paid!',
            ], 422); // Http code 422 - Unprocessable Content
        }

        // check if already paid
        if ($invoice->status === 'paid')
        {
            return response()->json([
                'message' => 'Invoice already marked as paid!',
                'invoice' => new InvoiceResource($invoice),
            ],200);
        }

        // execute update for status as paid & date now
        $invoice->update([
            'status'    => 'paid',
            'paid_date' => now(),
        ]);

        return response()->json([
            'message' => 'Invoice marked as paid successfully!',
            'invoice' => new InvoiceResource($invoice)
        ],200);
    }

        public function markAsVoid(string $id)
    {
        $invoice = Invoice::find($id);

        // check if not exist
        if (!$invoice) 
        {
            return response()->json([
                'message' => 'Invoice not found!'
            ], 404);
        }

        // check if status not billed & paid
        if ($invoice->status !== 'billed' && $invoice->status !== 'paid') 
        {
            return response()->json([
                'message' => 'Only billed & paid invoices can be marked as void!',
            ], 422); // Http code 422 - Unprocessable Content
        }

        // check if already paid
        if ($invoice->status === 'void')
        {
            return response()->json([
                'message' => 'Invoice already marked as void!',
                'invoice' => new InvoiceResource($invoice),
            ],200);
        }

        // execute update for status as paid & date now
        $invoice->update([
            'status'    => 'void',
            'paid_date' => now(),
        ]);

        return response()->json([
            'message' => 'Invoice marked as void successfully!',
            'invoice' => new InvoiceResource($invoice)
        ],200);
    }
}
