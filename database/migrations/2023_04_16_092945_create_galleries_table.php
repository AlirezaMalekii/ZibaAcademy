<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('REFRESH_MIGRATION')) {
            Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->morphs('galleryable');
            $table->softDeletes();
            $table->timestamps();
        });}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('REFRESH_MIGRATION')) {
            Schema::dropIfExists('galleries');}
    }
};
