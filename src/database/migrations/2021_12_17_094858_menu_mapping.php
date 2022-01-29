<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MenuMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_mapping', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->integer('menu_master_id')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('parent_id')->default('0')->nullable();
            $table->integer('reference_id')->default('0')->nullable();
            $table->string('short_code', 255)->nullable();
            $table->integer('sequence')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('class', 255)->nullable();
            $table->string('font_icon_class', 255)->nullable();
            $table->text('styles')->comment('for custom image style')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('is_deleted')->default('0')->nullable();
            $table->tinyInteger('is_completed')->default('0')->nullable();
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
        Schema::dropIfExists('menu_mapping');
    }
}
