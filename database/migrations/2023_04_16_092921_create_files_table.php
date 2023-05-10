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
            Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->morphs('fileable');
            $table->text('file')->nullable();
            $table->string('type')->nullable();
            $table->string('file_name')->nullable();
            $table->string('extension')->nullable();
            $table->string('storage')->default('local');
            $table->enum('accessibility', ['free', 'paid']);
            $table->boolean('downloadable')->default(0);
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
            Schema::dropIfExists('files');}
    }
};
