<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodReceivedNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_received_note', function (Blueprint $table) {
            $table->id('grn_id');
            $table->string('grn_no')->unique();
            $table->dateTime('grn_date')->nullable();
            $table->decimal('grn_amt',20,2)->nullable();

            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('u_id')->on('users');

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
        Schema::dropIfExists('good_received_note');
    }
}
