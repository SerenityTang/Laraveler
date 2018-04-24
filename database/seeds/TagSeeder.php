<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->delete();

        \App\Models\Tag::create([
            'tcategory_id'      => 1,
            'name'              => 'laravel',
            'description'       => 'laravel框架',
            'status'            => 1,
            'attention_count'   => 0,
        ]);
    }
}
