<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = [
        	 'name', 
        	 'email',
        	 'password',
        	 'role'
        ];


        $values = [
            ['Admin', 'admin@pmb.com', bcrypt(123456), 1]
        ];

        $insert_array = [];
        foreach ($values as $value) {
            $insert_array[] = array_combine($keys, $value);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->insert($insert_array);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
