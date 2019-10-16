<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('id');
            //initial customer
            $table->string('supplier_name');
            $table->string('supplier_email');
            $table->unsignedInteger('source_id')->nullable();
            //supplier info   
            $table->string('supplier_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('invoice')->nullable();
            //important
            $table->float('amount', 15, 2)->default(0);
            //ordered_by
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //supplier_by
            $table->unsignedInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            //customer
            $table->string('deposit')->nullable();
            //admin
            $table->string('ssDeposit')->nullable();
            //essentials
            $table->timestamps();
            $table->enum('status', [1,2,3,4,5,6,7])->default(1);

            $table->float('rate', 5, 2);

            //optional
            $table->unsignedInteger('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
}
