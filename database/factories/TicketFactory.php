<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'TCK-' . strtoupper($this->faker->bothify('??????##')),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TicketStatus::values()),
            'priority' => $this->faker->randomElement(TicketPriority::values()),
            'customer_id' => User::factory(),
            'meta' => [
                'channel' => 'web',
            ],
        ];
    }
}

