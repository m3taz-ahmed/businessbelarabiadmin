<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (Moataz Ahmed)
        Admin::create([
            'name' => 'Moataz Ahmed',
            'email' => 'moataz@businessbelarabia.com',
            'password' => 'password', // Will be hashed by the model mutator
            'is_active' => true,
        ]);

        // Create another admin user
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@businessbelarabia.com',
            'password' => 'password', // Will be hashed by the model mutator
            'is_active' => true,
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: moataz@businessbelarabia.com | Password: password');
        $this->command->info('Email: admin@businessbelarabia.com | Password: password');
    }
}
