<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('pro_id');
            $table->string('pro_code')->nullable();
            $table->string('pro_name')->nullable();
            $table->integer('measure_of_units')->nullable();
            $table->decimal('buying_price',20,2)->nullable();
            $table->decimal('retailer_price',20,2)->nullable();
            $table->timestamp('deactivated_at')->nullable();

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
        Schema::dropIfExists('product');
    }
}
