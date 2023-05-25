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
//        if (env('REFRESH_MIGRATION')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
                $table->foreignId('workshop_id')->constrained('workshops')->onDelete('cascade');
                $table->text('token')->unique();
                $table->softDeletes();
                $table->timestamps();
            });
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        if (env('REFRESH_MIGRATION')) {
            Schema::dropIfExists('tickets');
//        }
    }
};
