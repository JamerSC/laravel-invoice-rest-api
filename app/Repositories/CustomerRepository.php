<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerRepository
{
    protected Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function findAll(array $filters, int $perPage)
    {
        //query user in the db
        $query = Customer::query();

        // filtering
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // sorting
        $sort = $filters['sort'];
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        $query->orderBy($column, $direction);
        
        if ($filters['include_invoices']) {
            $query->with('invoices'); // include relations
        }

        // searching
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findById($id)
    {   
        // Find a model by its primary key or throw an exception
        // if not found throw ModelNotFoundException
        return Customer::findOrFail($id);
    }

    public function create(array $customerData)
    {
        return $this->customer->create($customerData); // Save a new model and return the instance
    }

    public function update(array $custmerData, Customer $customer)
    {
        $customer->update($custmerData);
        
        return $customer->fresh(); // Reload a fresh model instance from the database.
    }


    public function delete(Customer $customer)
    {
        return $customer->delete();
    }
}