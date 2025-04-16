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
            'lastnames' => 'Doe',
            'dateBirth' => '1990-01-01',
            'areaId' => 1,
            'type' => 0,
            'CURP' => 'ABCD123456EFGH7893',
            'IMSS' => '1234567894',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'), // Encripta la contraseña
        ]);

        User::create([
            'name' => 'Jane',
            'lastnames' => 'Doe',
            'dateBirth' => '1992-02-02',
            'areaId' => 2,
            'type' => 1,
            'CURP' => 'EFGH123456IJKL7893',
            'IMSS' => '9876543210',
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password123'), // Encripta la contraseña
        ]);
    }
}
