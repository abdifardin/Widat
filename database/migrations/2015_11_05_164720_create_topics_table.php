<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
			$table->string('topic', 255)->unique();
			$table->mediumText('abstract')->nullable();
			$table->integer('user_id')->nullable();
			$table->boolean('got_updated')->default(0);
		});

		DB::getPdo()->exec('
			CREATE TRIGGER `topics_AUPD` BEFORE UPDATE ON `topics` FOR EACH ROW
			BEGIN
				IF (NEW.abstract <> OLD.abstract)
				THEN
					SET NEW.got_updated = 1;
				END IF;
			END
		');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('topics');
    }
}
