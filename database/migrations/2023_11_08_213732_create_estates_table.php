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
        Schema::create('estates', function (Blueprint $table) {
            $table->id();
            $table->string('province');
            $table->string('district');
            $table->string('divisional_secretariat');
            $table->string('grama_niladari_division');
            $table->string('land_situated_village');
            $table->string('acquired_land_name');
            $table->string('acquired_land_extent');
            $table->string('total_extent_allotment_included');
            $table->string('claimant_name_and_address');
            $table->string('office_file_recorded');
            $table->string('land_acquired_purpose');
            $table->mediumText('land_acquisition_certificate')->nullable();
            $table->string('plan_availability');
            $table->string('plan_no_and_lot_no');
            $table->mediumText('plan_image')->nullable();
            $table->string('boundaries_of_land');
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
        Schema::dropIfExists('estates');
    }
};
