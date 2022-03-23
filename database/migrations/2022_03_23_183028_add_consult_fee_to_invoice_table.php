<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsultFeeToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->decimal('doc_consult_fee',10,2)->nullable()->after('payment_type');
            $table->decimal('invoice_other_amt',10,2)->nullable()->after('doc_consult_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn(['doc_consult_fee',['invoice_other_amt']]);
        });
    }
}
