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
        Schema::create('professionals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->bigInteger('cell_phone');
            $table->bigInteger('phone');
            $table->date('birth_date');
            $table->string('sex');
            $table->bigInteger('cpf');
            $table->bigInteger('cnpj')->nullable();
            $table->string('cover')->nullable();
            $table->string('rules');
            $table->boolean('is_active');
            $table->uuid('studio_id');
            $table->timestamps();

            $table->foreign('studio_id')
                ->references('uuid')
                ->on('studios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
};
