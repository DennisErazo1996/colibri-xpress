<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'derazo',
            'identity' => '0000000000000',
            'firstname' => 'Dennis',
            'lastname' => 'Erazo',
            'email' => 'derazo@colibrixpress.com',
            'password' => bcrypt('skillet1996')
        ]);
    }
}
