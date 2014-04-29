<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('friend', function($table)
		{
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('cascade');

            $table->integer('friend_user_id')->unsigned();
            $table->foreign('friend_user_id')
                ->references('id')->on('user')
                ->onDelete('cascade');

            $table->unique( array('user_id','friend_user_id') );

            $table->enum('status', array('pending', 'authorized',
                                        'not_authorized', 'blocked'))->default('pending');

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
        Schema::drop('friend');
    }

}
