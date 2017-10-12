<?php

use App\Entities\City;
use App\Entities\Country;
use App\Entities\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SqlDataSeeder extends Seeder
{

    public function run()
    {
        
        Eloquent::unguard();

        $this->command->info('Truncating Countries, States and Cities tables');
        $this->truncateSqlTables();

        //countries
        $path = 'app/RawSql/countries.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Countries table seeded!');

        //states
        $path = 'app/RawSql/states.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('States table seeded!');

        //cities
        $path = 'app/RawSql/cities.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Cities table seeded!');

    }

    public function truncateSqlTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('countries')->truncate();
        DB::table('states')->truncate();
        DB::table('cities')->truncate();
        Country::truncate();
        State::truncate();
        City::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}