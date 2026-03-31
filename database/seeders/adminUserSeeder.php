<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Dhruv Patel',   'email' => 'dhruvideas@gmail.com'],
            ['name' => 'Hitesh Patel',   'email' => 'hiteshpatel2073@gmail.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make('Admin@911'),
                'is_admin' => true,
            ]);
        }
    }
}
