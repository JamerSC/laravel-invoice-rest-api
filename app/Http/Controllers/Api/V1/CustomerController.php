<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$customers = Customer::all();

        $customers = Customer::with('invoices')->get();

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        // validate customer form fields
        $fields = $request->validated();

        // create the customer
        $customer = $request->user()->customers()->create($fields);

        // created response
        return response()->json([
            new CustomerResource($customer),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // check customer by id
        $customer = Customer::find($id);
        
        // check if user is exist
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found!'
            ],404);
        }

        return response()->json([
            new CustomerResource($customer),
        ], 200 );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        // find customer by id
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found!'
            ],404);
        }

        // update customer
        $customer->update($request->only([
            'name',
            'type', // customer type
            'address',
            'city',
            'province',
            'postal_code',
            'user_id',
        ]));

        // update response
        return response()->json([
            new CustomerResource($customer),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find customer
        $customer = Customer::find($id);

        // check if not exist
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found'
            ],404);
        }

        // delete
        $customer->delete();

        // request response
        return response()->json([
            'message' => 'Task deleted!',
            new CustomerResource($customer),
        ],204); // Http 204 - No content 
    }
}
