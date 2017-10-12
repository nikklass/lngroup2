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
        /*$this->call(RoleTableSeeder::class);
        */

        //$this->call(BulkSmsSeeder::class);
        //$this->call(SqlDataSeeder::class);

        $this->call(UsersDataSeeder::class);
        $this->call(ChatSeeder::class);
        
    }
}
