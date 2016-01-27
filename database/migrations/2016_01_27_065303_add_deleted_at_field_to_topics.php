<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtFieldToTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //ALTER TABLE  `topics` ADD  `deleted_at` TIMESTAMP NULL DEFAULT NULL ;
		\Illuminate\Support\Facades\Schema::table('topics', function($table) {
			$table->timestamp('deleted_at')->default(NULL)->nullable();
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
