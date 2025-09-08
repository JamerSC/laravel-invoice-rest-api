<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(), // automatically create a user if not provided
            'name'          => $this->faker->name(),
            'type'          => $this->faker->randomElement(['individual', 'business']),
            'address'       => $this->faker->address(),
            'city'          => $this->faker->city(),
            'province'      => $this->faker->state(),
            'postal_code'   => $this->faker->postcode(),
        ];
    }
}
