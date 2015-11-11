<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBupdTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::getPdo()->exec('
			CREATE TRIGGER `users_BUPD` BEFORE UPDATE ON `users` FOR EACH ROW
			BEGIN
				IF (NEW.score <> OLD.score)
				THEN
					INSERT INTO `score_history` VALUES(NULL, OLD.id, OLD.score, CURRENT_TIMESTAMP);
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
        //
    }
}
