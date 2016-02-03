<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUserTypeFieldForUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		DB::statement("ALTER TABLE `users` CHANGE `user_type` `user_type` ENUM('admin','translator','inspector') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'translator';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
