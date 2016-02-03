<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInspectionFileldsToKuTranslatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table('ku_translations', function (Blueprint $table) {
			$table->tinyInteger('finished', false)->default(0);
			$table->tinyInteger('inspection_result', false)->default(0);
			$table->unsignedInteger('inspector_id', false)->default(NULL)->nullable();
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
