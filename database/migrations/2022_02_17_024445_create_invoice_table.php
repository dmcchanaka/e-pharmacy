<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id('invoice_id');

            $table->string('invoice_no')->unique();
            $table->dateTime('invoice_date')->nullable();
            $table->decimal('invoice_gross_amt',20,2)->nullable();
            $table->decimal('invoice_discount_per',20,2)->nullable();
            $table->decimal('invoice_discount',20,2)->nullable();
            $table->decimal('invoice_net_amt',20,2)->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('doctor_id')->on('doctors');

            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('u_id')->on('users');

            $table->tinyInteger('payment_type')->default(0)->comment('1-cash, 2-credit/debit');

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
        Schema::dropIfExists('invoice');
    }
}
