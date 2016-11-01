<?php

use Illuminate\Database\Seeder;

class MethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('methods')->insert([
        	'id'=>1,
        	'module_id'=>1,
        	'name'=>'View admin page'
        ]);

        DB::table('methods')->insert([
        	'id'=>2,
        	'module_id'=>2,
        	'name'=>'View access'
        ]);

        DB::table('methods')->insert([
        	'id'=>3,
        	'module_id'=>2,
        	'name'=>'Edit access'
        ]);

        DB::table('methods')->insert([
        	'id'=>4,
        	'module_id'=>3,
        	'name'=>'View User'
        ]);

        DB::table('methods')->insert([
        	'id'=>5,
        	'module_id'=>3,
        	'name'=>'Add User'
        ]);

        DB::table('methods')->insert([
        	'id'=>6,
        	'module_id'=>3,
        	'name'=>'Edit User'
        ]);

        DB::table('methods')->insert([
        	'id'=>7,
        	'module_id'=>3,
        	'name'=>'Delete User'
        ]);

        DB::table('methods')->insert([
            'id'=>8,
            'module_id'=>4,
            'name'=>'View Appearance'
        ]);

        DB::table('methods')->insert([
            'id'=>9,
            'module_id'=>4,
            'name'=>'Change Theme'
        ]);
    }
}
