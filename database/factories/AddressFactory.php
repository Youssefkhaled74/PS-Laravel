<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Address;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'label' => fake()->randomElement(['Home','Work','Other']),
            'country' => 'Saudi Arabia',
            'city' => fake()->city(),
            'district' => fake()->word(),
            'street' => fake()->streetName(),
            'building_no' => (string) fake()->numberBetween(1,200),
            'apartment_no' => (string) fake()->numberBetween(1,50),
            'floor' => (string) fake()->numberBetween(1,20),
            'postal_code' => (string) fake()->numberBetween(10000,99999),
            'phone' => fake()->numerify('5########'),
            'notes' => fake()->sentence(),
            'lat' => null,
            'lng' => null,
            'is_default' => false,
        ];
    }
}
