<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('source_id')->nullable();
            
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orderhh')->onDelete('cascade');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->float('amount', 15, 2);
            $table->float('fee', 15, 2);
            $table->enum('status', [1,2,3,4,5,6])->default(1);
            $table->string('deposit')->nullable();
            
            $table->string('invoice');
            $table->string('declaration');

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
        Schema::dropIfExists('insurance');
    }
}
