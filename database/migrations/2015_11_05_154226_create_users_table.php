<?php

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
			$table->string('email')->unique();
			$table->string('name');
			$table->string('surname');
			$table->enum('user_type', ['admin', 'translator'])->default('translator');
			$table->string('password', 60);
			$table->integer('score')->default(0);
			$table->string('default_lang', 3)->default('ku');
			$table->rememberToken();
            $table->timestamps();
			$table->timestamp('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
