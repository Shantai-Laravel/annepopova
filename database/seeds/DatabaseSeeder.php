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
        $this->call(LangsTableSeeder::class);
        $this->call(AdminUsersTableSeed::class);
        $this->call(ModulesTableSeed::class);
        $this->call(UserFieldTableSeeder::class);
        $this->call(ContactsTablesSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(GeneralTableSeeder::class);
    }
}
