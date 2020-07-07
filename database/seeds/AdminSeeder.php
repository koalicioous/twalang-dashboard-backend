<?php

use Illuminate\Database\Seeder;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name'      => 'System Administrator',
            'email'     => 'admin@twalang.com',
            'password'  => Hash::make('123123123')
        ]);
    }
}
