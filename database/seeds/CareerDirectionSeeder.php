<?php

use Illuminate\Database\Seeder;

class CareerDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('career_directions')->delete();

        \App\Models\CareerDirection::create([
            'slug'   => 'PHP',
            'name'    => 'PHP开发工程师',
            'description' => '研发PHP',
            'status' => 1,
        ]);
        \App\Models\CareerDirection::create([
            'slug'   => 'Java',
            'name'    => 'Java开发工程师',
            'description' => '研发Java',
            'status' => 1,
        ]);
        \App\Models\CareerDirection::create([
            'slug'   => 'mobile',
            'name'    => '移动开发工程师',
            'description' => '研发移动端',
            'status' => 1,
        ]);

    }
}
