<?php

use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tag_categories')->delete();

        \App\Models\TagCategory::create([
            'name'    => 'PHP',
            'description' => 'PHP开发',
            'weight' => 0,
        ]);
    }
}
