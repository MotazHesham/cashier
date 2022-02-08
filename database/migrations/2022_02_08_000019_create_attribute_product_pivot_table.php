<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_5945050')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id', 'attribute_id_fk_5945050')->references('id')->on('attributes')->onDelete('cascade');
        });
    }
}
