<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permission', function (Blueprint $table) {
            $table->bigIncrements('up_id');

            $table->unsignedBigInteger('per_gp_id')->nullable();
            $table->foreign('per_gp_id')->references('per_gp_id')->on('permission_group');

            $table->unsignedBigInteger('permission_id')->nullable();
            $table->foreign('permission_id')->references('permission_id')->on('permissions');

            $table->unsignedBigInteger('permission_parent_id')->comment('parent id')->nullable();
            $table->foreign('permission_parent_id')->references('permission_id')->on('permissions');

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
        Schema::dropIfExists('user_permission');
    }
}
