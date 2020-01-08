<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_uri')->nullable();
            $table->unsignedInteger('order')->default(999);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('page_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('links');
    }
}
