<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfirmCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('confirm_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->char('phone_country', 2)->nullable();
            $table->string('confirm_code', 5);
            $table->integer('status_id')->unsigned()->default(1);
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('confirm_codes');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
    
}
