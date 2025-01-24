<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'user_id' => User::factory(), // Create a related user
            'slug' => Str::slug($this->faker->company), // Generate a slug based on the company name
            'order' => $this->faker->numberBetween(1, 100), // Random order between 1 and 100
            'prioroty' => $this->faker->randomElement(['high', 'middle', 'low']), // Random priority
            'currency' => $this->faker->randomElement(['$', '€']), // Random currency (either $ or €)
            'hourly_rate' => $this->faker->numberBetween(20, 200), // Random hourly rate between 20 and 200
            'hourly_rate_two' => $this->faker->optional()->numberBetween(20, 200), // Random hourly rate two (nullable)
            'color' => $this->faker->hexColor, // Random color
            'period_from' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'), // Nullable period_from
            'active_projects' => $this->faker->optional()->numberBetween(1, 10), // Nullable active projects
            'earnings' => $this->faker->optional()->numberBetween(0, 10000), // Nullable earnings
            'total_time' => $this->faker->optional()->numberBetween(0, 10000), // Nullable total time
        ];
    }
}
