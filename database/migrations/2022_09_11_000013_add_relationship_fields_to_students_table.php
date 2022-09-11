<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7294631')->references('id')->on('users');
            $table->unsignedBigInteger('father_id')->nullable();
            $table->foreign('father_id', 'father_fk_7295072')->references('id')->on('fathers');
        });
    }
}
