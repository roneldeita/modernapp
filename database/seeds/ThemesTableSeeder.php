<?php

use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('themes')->insert([
        	'id'=>1,
        	'name'=>'Default',
        	'css_path'=>'theme/default'
        ]);

        DB::table('themes')->insert([
        	'id'=>2,
        	'name'=>'Dark',
        	'css_path'=>'theme/dark'

        ]);

        DB::table('themes')->insert([
            'id'=>3,
            'name'=>'Light',
            'css_path'=>'theme/light'

        ]);
    }
}
