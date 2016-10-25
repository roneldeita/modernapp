<?php

use Illuminate\Database\Seeder;

class MethodUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('method_user')->insert([
        	'id'=>1,
        	'user_id'=>1,
        	'method_id'=>1
        ]);

        DB::table('method_user')->insert([
        	'id'=>2,
        	'user_id'=>1,
        	'method_id'=>2
        ]);

        DB::table('method_user')->insert([
        	'id'=>3,
        	'user_id'=>1,
        	'method_id'=>3
        ]);
    }
}
