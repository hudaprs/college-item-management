<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => \Hash::make('password'),
                'role' => 'Admin'
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => \Hash::make('password'),
                'role' => 'User'
            ]
        ]);
    }
}