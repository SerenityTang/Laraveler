<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(CareerDirectionSeeder::class);
        $this->call(TagCategorySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(UserCreditConfigSeeder::class);
    }
}
