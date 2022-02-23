<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_product', function (Blueprint $table) {
            $table->id('ip_id');

            $table->integer('line_no')->nullable();

            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoice');

            $table->unsignedBigInteger('pro_id')->nullable();
            $table->foreign('pro_id')->references('pro_id')->on('product');

            $table->decimal('ip_buying_price',20,2)->nullable();
            $table->decimal('ip_price',20,2)->nullable();
            $table->decimal('ip_qty',20,2)->nullable();
            $table->decimal('ip_line_amt',20,2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_product');
    }
}
