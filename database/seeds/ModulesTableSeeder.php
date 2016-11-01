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
        	'name'=>'Administration',
        	'description'=>'View admin area'
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

        DB::table('modules')->insert([
            'id'=>4,
            'name'=>'Appearance',
            'description'=>'Appearance settings'
        ]);

    }
}
