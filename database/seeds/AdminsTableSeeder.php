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
        
        $r_keys = [
             'name', 
             'guard_name',
        ];


        $r_values = [
            ['Admin', 'admins'],
            ['Staff', 'admins'],
        ];

        $r_insert_array = [];
        foreach ($r_values as $r_value) {
            $insert_array[] = array_combine($r_keys, $r_value);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->insert($r_insert_array);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    }
}
