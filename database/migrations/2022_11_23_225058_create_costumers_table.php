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
        Schema::create('costumers', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(md5(uniqid(rand() . "", true)));
            $table->string('name');
            $table->integer('phone');
            $table->string('email');
            $table->integer('cpf');
            $table->date('birth_date');
            $table->string('sex');
            $table->string('address');
            $table->string('district');
            $table->string('city');
            $table->integer('cep');
            $table->string('uf');
            $table->boolean('is_active');
            $table->uuid('studio_uuid');

            $table->timestamps();

            $table->foreign('studio_uuid')
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
        Schema::dropIfExists('costumers');
    }
};
