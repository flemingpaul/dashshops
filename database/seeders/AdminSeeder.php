<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'business_name' => 'ritsConsulting',
            'business_address' => 'vegas, Nevada',
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'phone_number' => '',
            'city' => 'vegas',
            'state' => 'Nevada',
            'zip_code' => '901210',
            'email_verified_at' => now(),
            'user_type' => 'Admin',
            'password' => bcrypt('verysafepassword'),
            'admin' => 1,
            'approved_at' => now(),
        ]);
    }
}
