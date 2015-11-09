<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('ku_translations', function (Blueprint $table) {
			$table->integer('topic_id');
			$table->string('topic', 500);
			$table->mediumText('abstract');

			$table->primary('topic_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('ku_translations');
    }
}
