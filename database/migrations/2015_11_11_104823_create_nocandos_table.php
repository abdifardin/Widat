<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateNocandosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('nocandos', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('topic_id');
			$table->string('reason', 255);

			$table->primary(['user_id', 'topic_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nocandos');
    }
}
