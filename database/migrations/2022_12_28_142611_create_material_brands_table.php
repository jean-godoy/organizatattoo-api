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
        \Illuminate\Support\Facades\DB::statement('SET SESSION sql_require_primary_key=0');
        Schema::create('material_brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_brand');
            $table->string('product_measure');
            $table->integer('minimum_amount');
            $table->boolean('descartable');
            $table->boolean('sterilizable');
            $table->integer('total_amount')->nullable();
            $table->boolean('is_active')->default(true);

            $table->uuid('material_category_id');
            $table->timestamps();

            $table->foreign('material_category_id')
                ->references('id')
                ->on('material_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_brands');
    }
};
