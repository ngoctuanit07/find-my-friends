<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('user', function($table)
        {
            $table->increments('id');
            $table->string('email')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('fb_token')->nullable();
            $table->bigInteger('facebook_uid')->nullable();
            $table->string('photo', 500)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_token')->nullable();
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
        Schema::drop('user');
	}

}
