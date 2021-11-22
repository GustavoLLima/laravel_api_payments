<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Gustavo',
                'cpf_cnpj' => '026.149.330-23',
                'email' => 'gustavolameirao@gmail.com',
                'password' => '123456',
                'type' => 'regular',
                'total_value' => 996.0,
                'created_at' => '2021-11-21 15:05:58',
                'updated_at' => '2021-11-21 22:09:24',
            ),
            1 => 
            array (
                'id' => 5,
                'name' => 'Caroline',
                'cpf_cnpj' => '000.000.000-00',
                'email' => 'caroline_garciarosa@hotmail.com',
                'password' => '123456',
                'type' => 'seller',
                'total_value' => 1004.0,
                'created_at' => '2021-11-21 15:06:57',
                'updated_at' => '2021-11-21 22:09:24',
            ),
        ));
        
        
    }
}