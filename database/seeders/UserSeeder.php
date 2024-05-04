<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'superadmin@finscale.com', 'password' => bcrypt('password') ]);
        $admin->assignRole('Super Admin');
    }
}
