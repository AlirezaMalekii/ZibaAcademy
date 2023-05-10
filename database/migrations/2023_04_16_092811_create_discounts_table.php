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
            Schema::create('discounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
                $table->string('code');
                $table->string('type')->default('public');
                $table->integer('percent')->nullable();
                $table->integer('amount')->nullable();
                $table->integer('use_limit')->nullable();
                $table->date('expire_date')->nullable();
                $table->boolean('active')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create('discount_item', function (Blueprint $table) {
                $table->morphs('discountable');
                $table->foreignId('discount_id')->constrained('discounts')->onDelete('cascade');
                $table->primary(['discount_id', 'discountable_id']);
                $table->timestamps();
            });

            Schema::create('discount_user', function (Blueprint $table) {
                $table->foreignId('discount_id')->constrained('discounts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamp('used_at')->nullable();
                $table->primary(['discount_id', 'user_id']);
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
            Schema::dropIfExists('discount_user');
            Schema::dropIfExists('discount_item');
            Schema::dropIfExists('discounts');
        }
    }
};
