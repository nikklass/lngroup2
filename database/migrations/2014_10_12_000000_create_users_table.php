<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->enum('gender', ['m', 'f'])->default('m');
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('password')->nullable();
            $table->integer('preferred_amount')->unsigned()->nullable();
            $table->char('phone_country', 2);
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('constituency_id')->unsigned()->nullable();
            $table->timestamp('ward_id')->nullable();
            $table->timestamp('dob')->nullable();
            $table->enum('dob_updated', ['1', '0'])->default('0');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->string('confirm_code', 5)->nullable();
            $table->string('src_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->integer('status_id')->unsigned()->default(99);
            $table->boolean('active')->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->uuid('uuid');
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->enum('gender', ['m', 'f'])->default('m');
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('password')->nullable();
            $table->integer('preferred_amount')->unsigned()->nullable();
            $table->char('phone_country', 2);
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('constituency_id')->unsigned()->nullable();
            $table->integer('ward_id')->unsigned()->nullable();
            $table->timestamp('dob')->nullable();
            $table->enum('dob_updated', ['1', '0'])->default('0');
            $table->string('confirm_code', 5)->nullable();
            $table->string('src_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->integer('status_id')->nullable();
            $table->boolean('active')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /*create country table*/
        /*Schema::defaultStringLength(191);
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sortname', 3);
            $table->string('name');
            $table->integer('phonecode');
            $table->enum('status_id', ['1', '0'])->default('1');
            $table->timestamps();
        });*/

        /*create states table*/
        /*Schema::defaultStringLength(191);
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('country_id');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*create cities table*/
        /*Schema::defaultStringLength(191);
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('state_id');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*create constituencies table*/
        /*Schema::defaultStringLength(191);
        Schema::create('constituencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('state_id');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*create wards table*/
        /*Schema::defaultStringLength(191);
        Schema::create('wards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('constituency_id');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*sms outbox table*/
        /*Schema::defaultStringLength(191);
        Schema::create('sms_outboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->string('src_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->default(8);
            $table->integer('sms_type_id')->unsigned()->default(6);
            $table->integer('schedule_sms_outbox_id')->unsigned()->nullable();
            $table->string('phone', 20);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*schedule sms outbox table*/
        /*Schema::defaultStringLength(191);
        Schema::create('schedule_sms_outboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->string('src_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->default(5);
            $table->integer('sms_type_id')->unsigned()->default(6);
            $table->string('phone', 20);
            $table->string('schedule_date')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });*/

        /*sms types table*/
        /*Schema::defaultStringLength(191);
        Schema::create('sms_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('description', 255);
            $table->timestamps();
        });*/

        /*sms types table*/
        /*Schema::defaultStringLength(191);
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('section');
            $table->timestamps();
        });*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('users');
        Schema::dropIfExists('user_archives');
        
        /*Schema::dropIfExists('countries');
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('constituencies');
        Schema::dropIfExists('wards');*/

        /*Schema::dropIfExists('sms_outboxes');
        Schema::dropIfExists('schedule_sms_outboxes');
        Schema::dropIfExists('sms_types');
        Schema::dropIfExists('statuses');*/

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
    
}
