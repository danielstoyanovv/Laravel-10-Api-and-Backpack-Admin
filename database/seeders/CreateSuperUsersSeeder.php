<?php

namespace Database\Seeders;

use App\Helpers\TokenGenerator;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class CreateSuperUsersSeeder extends Seeder
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
            'password' => Hash::make("12345678"),
            'roles' => json_encode(["Admin"], true),
            'token' => TokenGenerator::generate(),
            'refresh_token' => TokenGenerator::generate(),
            'expires_at' => Carbon::now()->addDay()
        ])->create();
    }
}
