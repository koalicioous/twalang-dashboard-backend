<?php

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
        // $this->call(UserSeeder::class);
        $users = factory(App\User::class,1000)->create();
        $activities = factory(App\Activity::class,250)->create();
        $purchase = factory(App\Purchase::class,10000)->create();
    }
}
