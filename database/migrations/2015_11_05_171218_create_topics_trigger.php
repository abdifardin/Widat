<?php

use Illuminate\Database\Migrations\Migration;

class CreateTopicsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		DB::unprepared('
			USE `widatdb`;
			DELIMITER $$
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
        DB::unprepared('DROP TRIGGER IF EXISTS `topics_AUPD`');
    }
}
