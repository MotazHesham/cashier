<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');
            $table->string('measure');
            $table->Integer('current_stock')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
