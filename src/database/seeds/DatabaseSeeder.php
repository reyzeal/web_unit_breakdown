<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'CCR',
            'email' => 'CCR@system.com',
            'password' => \Hash::make('ccr_1029'),
            'level' => 1
        ]);
        User::create([
            'name' => 'base_control',
            'email' => 'base_control@system.com',
            'password' => \Hash::make('base_control_1123'),
            'level' => 2
        ]);
        User::create([
            'name' => 'produksi',
            'email' => 'produksi@system.com',
            'password' => \Hash::make('produksi_5573'),
            'level' => 3
        ]);
        // $this->call(UsersTableSeeder::class);
    }
}
