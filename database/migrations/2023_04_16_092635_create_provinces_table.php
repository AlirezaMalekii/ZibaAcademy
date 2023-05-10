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
            Schema::create('provinces', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 10, 8)->nullable();
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
            Schema::dropIfExists('provinces');}
    }
};
