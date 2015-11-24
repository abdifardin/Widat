<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastKeystrokeAtFieldToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //ALTER TABLE  `users` ADD  `last_keystroke_at` TIMESTAMP NULL DEFAULT NULL ;
		\Illuminate\Support\Facades\Schema::table('users', function($table) {
			$table->timestamp('last_keystroke_at')->default(NULL);
		});
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
