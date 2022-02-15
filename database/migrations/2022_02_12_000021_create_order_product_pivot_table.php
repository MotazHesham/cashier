<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductPivotTable extends Migration
{
    public function up()
    {   
        Schema::create('order_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'order_id_fk_5945076')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_5945076')->references('id')->on('products')->onDelete('cascade');
            $table->longText('attributes')->default('[]');
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('extra_price', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->timestamps();
        });
    }
}
