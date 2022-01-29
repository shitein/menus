<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoleManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_management', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->integer('company_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('object_id')->nullable();
            $table->integer('object_item_id')->nullable();
            $table->tinyInteger('can_view')->default('0')->nullable();
            $table->tinyInteger('can_add')->default('0')->nullable();
            $table->tinyInteger('can_edit')->default('0')->nullable();
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
        Schema::dropIfExists('role_management');
    }
}
