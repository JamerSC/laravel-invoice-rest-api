<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $customerService; // inject customer service

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = $this->customerService->getAllCustomer($request);

        return response()->json([
            'message'   => 'List of all customers',
            'customers' => CustomerResource::collection($customers),
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $fields = $request->validated(); // validate customer form fields
        $fields['user_id'] = $request->user()->id; // attached login user

        $customer = $this->customerService->createCustomer($fields); // create the customer
        
        return response()->json(new CustomerResource($customer),201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = $this->customerService->getCustomerById($id); // get customers id using service
            
        return response()->json(new CustomerResource($customer),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->customerService->updateCustomer($request->validated(), $id);

        return response()->json([
            'message'  => 'Customer updated successfully',
            'customer' => new CustomerResource($customer),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->customerService->deleteCustomerById($id);

        return response()->json([
            'message' => 'Customer deleted successfully!',
        ], 204);
    }
}
