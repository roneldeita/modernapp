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

        $user_id =1;

        for($i=1 ; $i <= 17 ; $i++){

            DB::table('method_user')->insert([
                'id'=>$i,
                'user_id'=>$user_id,
                'method_id'=>$i
            ]);
        }

    }
}
