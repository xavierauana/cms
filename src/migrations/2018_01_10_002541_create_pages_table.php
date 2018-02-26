<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uri')->unique();
            $table->text('template');
            $table->boolean('has_children')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_restricted')->default(false);
            $table->unsignedInteger('permission_id')->default(0);
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pages');
    }
}
