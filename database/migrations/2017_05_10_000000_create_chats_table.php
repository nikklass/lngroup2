<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::defaultStringLength(191);

        Schema::create('chat_channels', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 150);
            $table->integer('user_id');
            $table->integer('updated_by');
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('chat_threads', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('title', 150);
            $table->integer('chat_channel_id');
            $table->integer('user_id');
            $table->integer('updated_by');
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('chat_channel_id')->references('id')->on('chat_channels');

        });

        Schema::create('chat_thread_users', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';

            $table->integer('chat_thread_id');
            $table->integer('user_id');
            $table->enum('receive_notifications', ['1', '0'])->default('1');
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            //$table->foreign('chat_thread_id')->references('id')->on('chat_threads')->onDelete('cascade');

            $table->primary(['user_id', 'chat_thread_id']);

        });

        Schema::create('chat_messages', function (Blueprint $table) {
            
            $table->increments('id');
            $table->text('chat_text');
            $table->integer('chat_thread_id');
            $table->integer('user_id');
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('chat_thread_id')->references('id')->on('chat_threads');

            //$table->foreign('user_id')->references('id')->on('users');

        }); 

        Schema::create('chat_message_read_states', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('chat_message_id')->unsigned();
            $table->enum('message_keep', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('chat_channels');
        Schema::dropIfExists('chat_threads');
        Schema::dropIfExists('chat_thread_users');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_message_read_states');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
    
}
