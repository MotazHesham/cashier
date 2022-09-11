<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFathersTable extends Migration
{
    public function up()
    {
        Schema::create('fathers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
