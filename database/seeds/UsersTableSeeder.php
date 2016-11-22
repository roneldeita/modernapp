<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'id'=>1,
            'theme_id'=>1,
        	'name'=>'Administrator',
        	'email'=>'admin@email.com',
        	'password'=>bcrypt('admin'),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
