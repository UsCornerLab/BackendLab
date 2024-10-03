<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "first_name" => "Admin",
            "last_name" => "Test",
            "email" => "admin@example.com",
            "password"=> bcrypt("admin"),
            "birthDate" => "2000-03-02",
            "address" => "Dire Dawa",
            "id_photo_path" => "",
            "role_id" => 1,
            "created_at" => now(),
            "updated_at" => now()

        ];

        DB::table("User")->insert($data);
        
    }
}
