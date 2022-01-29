<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('short_code', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('class', 255)->nullable();
            $table->string('font_icon_class', 255)->nullable();
            $table->text('language_key')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
