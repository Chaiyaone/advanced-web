<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('Order_id');
            $table->string('Order_number', );
            $table->string('Customer_Name', );
            $table->string('Status');
            $table->timestamps();
            // foreign key
            $table->foreign('Order_number')->references('Detail_id')->on('order_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
