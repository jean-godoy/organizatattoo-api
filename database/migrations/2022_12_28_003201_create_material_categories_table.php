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
        Schema::create('material_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('material_category');
            $table->uuid('material_product_id');
            $table->timestamps();

            $table->foreign('material_product_id')
                ->references('id')
                ->on('material_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_categories');
    }
};
