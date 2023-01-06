<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOperationsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->foreign('stock_id', 'item_fk_7466703')->references('id')->on('stock');
            $table->decimal('quantity', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
