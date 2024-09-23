<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'librarian', 'user'];

        foreach ($roles as $role) {
            DB::table('Role')->updateOrInsert(
                ['role_type' => $role],
                ['role_type' => $role]
            );
        }
    }
}
