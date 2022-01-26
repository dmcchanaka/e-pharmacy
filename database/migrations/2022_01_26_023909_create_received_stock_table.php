<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_stock', function (Blueprint $table) {
            $table->id('rs_id');

            $table->unsignedBigInteger('grn_id')->nullable();
            $table->foreign('grn_id')->references('grn_id')->on('good_received_note');

            $table->unsignedBigInteger('pro_id')->nullable();
            $table->foreign('pro_id')->references('pro_id')->on('product');

            $table->decimal('cost_price',20,2)->nullable();

            $table->decimal('rs_qty',20,3)->nullable();
            $table->decimal('rs_remaining_qty',20,3)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_stock');
    }
}
