<?php

use Illuminate\Database\Seeder;

class PostCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('postcategories')->insert([
        	'id'=>1,
        	'name'=>'LARAVEL'
        ]);
    }
}
