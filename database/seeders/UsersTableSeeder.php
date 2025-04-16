<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'John',
            'lastname' => 'Doe',
            'dateBirth' => '1990-01-01',
            'userType' => 1, // Normal user
            'stateBirth' => 1, // Example state ID
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'), // Encripta la contraseña
        ]);

        User::create([
            'name' => 'Jane',
            'lastname' => 'Doe',
            'dateBirth' => '1992-02-02',
            'userType' => 0, // Admin user
            'stateBirth' => 2, // Example state ID
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password123'), // Encripta la contraseña
        ]);
    }
}
