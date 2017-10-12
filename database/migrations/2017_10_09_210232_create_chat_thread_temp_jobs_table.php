<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatThreadTempJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_thread_temp_jobs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('chat_thread_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->index(['chat_thread_id', 'user_id']);
        });

        Schema::create('chat_message_created_email_jobs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('chat_message_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->index(['chat_message_id', 'user_id']);
        });

        Schema::create('chat_message_emails', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('thread_title');
            $table->string('sender_full_name');
            $table->text('sender_message');
            $table->string('recipient_first_name');
            $table->string('recipient_email');
            $table->integer('status_id')->default(99);
            $table->timestamps();

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_thread_temp_jobs');
        Schema::dropIfExists('chat_message_created_email_jobs');
    }
}
