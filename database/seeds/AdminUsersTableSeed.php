<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user')->truncate();

        DB::table('admin_user')->insert([
            'name' => 'admin',
            'login' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('q1w2e3r4'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
