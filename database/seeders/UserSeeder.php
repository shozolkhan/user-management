<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $total = 500;  // total records
        $batch = 100;   // batch size

        for ($i = 0; $i < $total; $i += $batch) {
            $users = [];

            for ($j = 0; $j < $batch; $j++) {
                $users[] = [
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => bcrypt('password123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('users')->insert($users);
            $this->command->info("Inserted: " . ($i + $batch));
        }
    }
}
