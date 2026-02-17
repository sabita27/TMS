<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;
use App\Models\TicketPriority;

class TicketStatusPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Ticket Statuses
        $statuses = [
            ['name' => 'Open', 'color' => '#3b82f6', 'status' => true],
            ['name' => 'In Progress', 'color' => '#f59e0b', 'status' => true],
            ['name' => 'On Hold', 'color' => '#8b5cf6', 'status' => true],
            ['name' => 'Resolved', 'color' => '#10b981', 'status' => true],
            ['name' => 'Closed', 'color' => '#6b7280', 'status' => true],
            ['name' => 'Cancelled', 'color' => '#ef4444', 'status' => true],
        ];

        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate(
                ['name' => $status['name']],
                $status
            );
        }

        // Default Ticket Priorities
        $priorities = [
            ['name' => 'Low', 'color' => '#22c55e', 'status' => true],
            ['name' => 'Medium', 'color' => '#eab308', 'status' => true],
            ['name' => 'High', 'color' => '#f97316', 'status' => true],
            ['name' => 'Critical', 'color' => '#dc2626', 'status' => true],
            ['name' => 'Urgent', 'color' => '#991b1b', 'status' => true],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::firstOrCreate(
                ['name' => $priority['name']],
                $priority
            );
        }
    }
}
