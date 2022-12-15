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
        Schema::create('budgets', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->uuid('user_id');
            $table->uuid('studio_id');
            $table->uuid('costumer_id');
            $table->string('costumer_name');
            $table->uuid('professional_id');
            $table->string('professional_name');
            $table->string('type_service');
            $table->string('style_service');
            $table->string('body_region');
            $table->string('url_image')->nullable();
            $table->string('sessions')->nullable();
            $table->integer('width')->nullable();
            $table->integer('heigth')->nullable();
            $table->integer('price');
            $table->date('validated_at');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('budgets');
    }
};
