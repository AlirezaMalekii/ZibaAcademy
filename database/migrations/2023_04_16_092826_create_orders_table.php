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
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('cascade');
                $table->string('discount_amount', 50)->default(0);
                $table->string('total_price', 50)->default(0);
                $table->string('type')->default('purchase');
                $table->string('payment_gate')->default('zarinpal');
                $table->boolean('is_paid')->default(false);
                $table->softDeletes();
                $table->timestamps();
                $table->enum('status', ['pending', 'cancel', 'paid'])->default('pending');
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
            Schema::dropIfExists('orders');
        }
    }
};
