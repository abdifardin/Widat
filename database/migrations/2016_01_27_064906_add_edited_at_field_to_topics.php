<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditedAtFieldToTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		//ALTER TABLE  `topics` ADD  `edited_at` varchar(10) NULL DEFAULT NULL ;
		\Illuminate\Support\Facades\Schema::table('topics', function($table) {
			$table->string('edited_at', 10)->default(NULL)->nullable();
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
