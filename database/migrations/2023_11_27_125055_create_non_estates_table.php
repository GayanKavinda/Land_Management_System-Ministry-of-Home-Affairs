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
        Schema::create('non_estates', function (Blueprint $table) {
            $table->id();
            $table->string('province');
            $table->string('district');
            $table->string('divisional_secretariat');
            $table->string('grama_niladari_division');
            $table->string('estate_name');
            $table->string('plan_no');
            $table->decimal('land_extent', 8, 2);
            $table->boolean('building_available')->default(false);
            $table->string('building_name')->nullable();
            $table->string('government_land');
            $table->string('reason')->nullable();

            // Add more fields as needed
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
        Schema::dropIfExists('non_estates');
    }
};
