<?php

use Illuminate\Database\Seeder;

class LangsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        DB::table('langs')->delete();

        DB::table('langs')->insert([
            'lang' => 'ro',
            'default' => 1,
            'description' => 'Română',
            'active' => 1,
            'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('langs')->insert([
            'lang' => 'en',
            'default' => 0,
            'description' => 'English',
            'active' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
