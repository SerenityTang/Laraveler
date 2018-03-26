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
        $this->call(Career_directionSeeder::class);
        $this->call(TagCategorySeeder::class);
        $this->call(TagSeeder::class);
    }
}
