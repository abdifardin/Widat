<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_recommendations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('topic_id');
			$table->integer('user_id');
			$table->text('reason');
			$table->boolean('viewed')->default(0);
			$table->timestamps();
			$table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delete_recommendations');
    }
}
