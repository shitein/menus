<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertGeneralMasterLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER='root'@'localhost'
        FUNCTION `insert_general_master_log`
         (`table_name` VARCHAR(255),
         `column_name` VARCHAR(255), `previous_value` VARCHAR(255),
         `updated_value` VARCHAR(255), `db_user` VARCHAR(255),
         `ref_id` VARCHAR(255), `ip_address` VARCHAR(50),
         `device` VARCHAR(255), `created_by` INT(11), `updated_by` INT(11))
          RETURNS VARCHAR(255) CHARSET latin1
          BEGIN
            DECLARE INSERT_STATUS VARCHAR(255);
                INSERT INTO `log_table`(
                        `table_name`,
                        `column_name`,
                        `previous_value`,
                        `updated_value`,
                        `db_user`,
                        `ref_id`,
                        `ip_address`,
                        `localhost_name`,
                        `device`,
                        `created_by`,
                        `updated_by`,
                        `created_at`,
                        `updated_at`
                    )
                    VALUES(
                        table_name
                        ,column_name
                        ,previous_value
                        ,updated_value
                        ,CURRENT_USER()
                        ,ref_id
                        ,ip_address
                        ,GetHostName()
                        ,device
                        ,created_by
                        ,updated_by
                        ,CURRENT_TIMESTAMP
                        ,CURRENT_TIMESTAMP
                    );
                SET INSERT_STATUS = 'Log Created';
                RETURN (INSERT_STATUS);
         END
        ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS insert_general_master_log');
    }
}
