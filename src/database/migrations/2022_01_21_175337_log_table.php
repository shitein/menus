<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_table', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('table_name', 255)->nullable();
            $table->string('column_name', 255)->nullable();
            $table->string('previous_value', 255)->nullable();
            $table->string('updated_value', 255)->nullable();
            $table->string('db_user', 255)->default('CURRENT_USER')->nullable();
            $table->string('ref_id', 255)->comment('store row id which is affected on the table')->nullable();
            $table->string('ip_address', 255)->nullable();
            $table->string('localhost_name', 255)->nullable();
            $table->string('device', 255)->default('W')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_table');
    }
}
