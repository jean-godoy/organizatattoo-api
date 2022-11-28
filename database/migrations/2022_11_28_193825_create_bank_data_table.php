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
        Schema::create('bank_data', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('bank');
            $table->bigInteger('agency');
            $table->bigInteger('account');
            $table->string('pix');
            $table->uuid('professional_id');
            $table->timestamps();

            $table->foreign('professional_id')
                ->references('id')
                ->on('professionals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_data');
    }
};
