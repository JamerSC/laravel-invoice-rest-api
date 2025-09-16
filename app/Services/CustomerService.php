<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomer(Request $request)
    {
        $perPage = min($request->get('per_page', 10), 100);
        
        $filters = [
            'type'             => $request->get('type'),
            'include_invoices' => $request->boolean('include_invoices', false),
            'search'           => $request->get('q'),
            'sort'             => $request->get('sort', '-id'),
        ];

        $customers = $this->customerRepository->findAll($filters, $perPage);

        return $customers;
    }

    public function getCustomerById($id)
    {
  
        try 
        {
            $customer = $this->customerRepository->findById($id);

            Log::info('Customer fetched', [
                'customer' => $customer,
            ]);

            return $customer;
        }
        catch (ModelNotFoundException $e)
        {
            Log::error('Customer not found', [
                'id'      => $id,
                'message' => $e->getMessage(),
                'model'   => $e->getModel(),
            ]);
            throw $e; // rethrow for controller or handler
        }    
    }

    public function createCustomer(array $customerData, int $userId = null)
    {
        if ($userId) {
            $customerData['user_id'] = $userId;
        }

        $customer = $this->customerRepository->create($customerData);

        Log::info('Customer Created', [
            'id'         => $customer->id,
            'customer'   => $customer,
        ]);
        
        return $customer;
    }

    public function updateCustomer(array $data, $id)
    {
        try
        {
            $customer = $this->customerRepository->findById($id);

            $updateCustomer = $this->customerRepository->update($data, $customer);

            Log::info('Customer updated successfully!', [
                'id'       => $updateCustomer->id,
                'customer' => $customer,
            ]);

            return $updateCustomer;
        }
        catch (ModelNotFoundException $e)
        {
            Log::error('Customer not found', [
                'id'      => $id,
                'message' => $e->getMessage(),
                'model'   => $e->getModel(),
            ]);
            throw $e;
        }
    }

    public function deleteCustomerById($id)
    {
        try
        {
            $customer = $this->customerRepository->findById($id);

            $this->customerRepository->delete($customer);

            Log::info('Customer deleted successfully!', [
                'id'=> $customer->id,
                'customer'=> $customer,
            ]);

            return true;
        }
        catch (ModelNotFoundException $e)
        {
            Log::error('Customer not found for deletion', [
                'id'      => $id,
                'message' => $e->getMessage(),
                'model'   => $e->getModel(),
            ]);
            throw $e;
        }
    }
}