<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceOtherFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_other_fees', function (Blueprint $table) {
            $table->id('iof_id');

            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoice');

            $table->unsignedBigInteger('fee_id')->nullable();
            $table->foreign('fee_id')->references('fee_id')->on('other_fees');

            $table->decimal('other_price',10,2)->nullable();
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
        Schema::dropIfExists('invoice_other_fees');
    }
}
