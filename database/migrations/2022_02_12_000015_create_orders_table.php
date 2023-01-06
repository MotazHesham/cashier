<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('order_from')->default('cashier');
            $table->string('description')->nullable();
            $table->tinyInteger('viewed')->default(1);
            $table->string('payment_type')->default('cash');
            $table->date('entry_date')->nullable();
            $table->decimal('paid_up', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();// the qr code user
            $table->foreign('user_id', 'user_fk_7697503')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
