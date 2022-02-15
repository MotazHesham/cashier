<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_5945050')->references('id')->on('products')->onDelete('cascade'); 
            $table->string('variant');
            $table->decimal('price', 15, 2)->nullable();
            $table->timestamps();
        });
    }
}
