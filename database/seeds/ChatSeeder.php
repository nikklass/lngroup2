<?php

use App\ChatChannel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void 
     */
    public function run()
    {

        $this->command->info('Truncating chat channels, threads table');
        $this->truncateChatTables();

        factory(App\Entities\ChatChannel::class, 100)->create();

        factory(App\Entities\ChatThread::class, 300)->create();

        factory(App\Entities\ChatMessage::class, 500)->create();

    }


    public function truncateChatTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        DB::table('chat_channels')->truncate();
        
        \App\Entities\ChatChannel::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}