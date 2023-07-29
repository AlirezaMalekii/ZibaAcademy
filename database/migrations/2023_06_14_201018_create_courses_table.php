<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('REFRESH_MIGRATION')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();//
                $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');//
                $table->string('title');//
                $table->string('slug')->nullable();//
                $table->text('description')->nullable();//
                $table->text('body');//
                $table->string('price', 50)->default(0);//
                $table->string('time', 15)->default('00:00:00');//
                $table->integer('viewCount')->default(0);//
                $table->integer('discount')->default(0);//
                $table->enum('status', ['active', 'is_draft', 'inactive'])->default('is_draft');//
                $table->text('prerequisite')->nullable();//
                $table->string('section_count')->default(0);
                $table->string('episode_count')->default(0);
                $table->string('support_way')->nullable();
                $table->string('delivery_way')->nullable();//
                $table->string('spotplayer_course_id')->nullable();
                $table->string('level')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            Schema::create('category_course', function (Blueprint $table) {
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->primary(['course_id', 'category_id']);
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
        Schema::dropIfExists('courses');}
    }
};
