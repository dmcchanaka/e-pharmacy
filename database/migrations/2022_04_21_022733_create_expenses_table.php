<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('exp_id');
            $table->date('exp_date')->nullable();
            $table->string('exp_description')->nullable();
            $table->decimal('exp_amt', 10,2)->nullable();

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
        Schema::dropIfExists('expenses');
    }
}
