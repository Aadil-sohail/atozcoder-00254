<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(PermissionSeeder::class);

        DB::table('users')->insert([
            // Admin
            [
                'name'     => 'Admin',
                'username'     => 'testsoftware',
                'email'    => 'testsoftware@gmail.com',
                'password' => Hash::make('Az12345'),
                'phone'=> '1234567890',
                // 'verification_code'=> '123456',
                'status'   => '1',
                'close'   => '1',
                'inserted_by'   => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        User::where('email', 'testsoftware@gmail.com')->first()->assignRole('Admin');
    }
}
