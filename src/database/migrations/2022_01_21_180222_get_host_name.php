<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class GetHostName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER='root'@'localhost'
        FUNCTION GetHostName()
        RETURNS varchar(255) CHARSET
        latin1
        BEGIN
        DECLARE local_hostname VARCHAR(255);
        SELECT variable_value INTO local_hostname
        FROM information_schema.global_variables
        WHERE variable_name = 'hostname';
        RETURN local_hostname;
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
        DB::unprepared('DROP FUNCTION IF EXISTS GetHostName');
    }
}
