<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Regular User',
                'cpf_cnpj' => '000.000.000-00',
                'email' => 'regular_user@gmail.com',
                'password' => Hash::make('123456'),
                'type' => 'regular',
                'total_value' => 1000.0,
                'created_at' => '2021-11-21 15:05:58',
                'updated_at' => '2021-11-21 22:09:24',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Seller User',
                'cpf_cnpj' => '111.111.111-11',
                'email' => 'regular_user@hotmail.com',
                'password' => Hash::make('123456'),
                'type' => 'seller',
                'total_value' => 1000.0,
                'created_at' => '2021-11-21 15:06:57',
                'updated_at' => '2021-11-21 22:09:24',
            ),
        ));
    }
}