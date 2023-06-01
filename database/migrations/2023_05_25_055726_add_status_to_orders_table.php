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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'cancel', 'paid'])->default('pending');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });}
    }
};
