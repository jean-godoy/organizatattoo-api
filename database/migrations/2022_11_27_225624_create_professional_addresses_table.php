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
        Schema::create('professional_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('address');
            $table->bigInteger('number');
            $table->string('district');
            $table->string('city');
            $table->bigInteger('cep');
            $table->string('uf');
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
        Schema::dropIfExists('professional_addresses');
    }
};
