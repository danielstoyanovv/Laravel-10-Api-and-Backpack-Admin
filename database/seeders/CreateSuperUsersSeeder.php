<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class  CreateSuperUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserFactory::new([
            'name' => 'Admin',
            'email' => 'superAdmin8674@gmail.com',
            'password' => Hash::make("12345678")
        ])->create();
    }
}
