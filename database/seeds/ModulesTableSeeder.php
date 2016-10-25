<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
        	'id'=>1,
        	'name'=>'Administrator',
        	'description'=>'can be able to view the admin panel'
        ]);

        DB::table('modules')->insert([
        	'id'=>2,
        	'name'=>'Access',
        	'description'=>'Access management'
        ]);

        DB::table('modules')->insert([
        	'id'=>3,
        	'name'=>'Users',
        	'description'=>'User management'
        ]);

    }
}
