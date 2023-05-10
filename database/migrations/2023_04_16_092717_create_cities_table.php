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
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 10, 8)->nullable();
            });
        }
//        DB::table('users')->insert(
//            array(
//                'email' => 'name@domain.example',
//                'verified' => true
//            )
//        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('REFRESH_MIGRATION')) {
            Schema::dropIfExists('cities');
        }
    }
};
