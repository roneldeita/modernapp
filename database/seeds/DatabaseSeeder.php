<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ThemesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(MethodsTableSeeder::class);
        $this->call(MethodUserTableSeeder::class);
        $this->call(PostCategoryTableSeeder::class);

    }
}
