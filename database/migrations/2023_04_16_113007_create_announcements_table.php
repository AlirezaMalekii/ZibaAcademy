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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')->nullable()->constrained('workshops')->onDelete('cascade');
//            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('cascade');
            $table->json('users')->nullable();
            $table->string('title');
            $table->text('message');
            $table->json('kavenegar_data')->nullable();
            $table->json('drivers')->nullable();
            $table->timestamp('send_at')->nullable();
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
