<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGeneralSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('income_orders_id')->nullable();
            $table->foreign('income_orders_id', 'income_orders_fk_5981050')->references('id')->on('income_categories');
        });
    }
}