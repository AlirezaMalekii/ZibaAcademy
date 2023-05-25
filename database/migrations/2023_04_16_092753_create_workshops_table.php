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
            Schema::create('workshops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->string('title');
                $table->string('description')->nullable();
                $table->text('body')->nullable();
                $table->string('slug')->nullable();
                $table->timestamp('event_time')->nullable();
                $table->string('period');
                $table->integer('price');
                $table->integer('registration_number')->default(0);
                $table->integer('capacity')->default(0);
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create('category_workshop', function (Blueprint $table) {
                $table->foreignId('workshop_id')->constrained('workshops')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->primary(['workshop_id', 'category_id']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('REFRESH_MIGRATION')) {
            Schema::dropIfExists('category_workshop');

        Schema::dropIfExists('workshops');}
    }
};
