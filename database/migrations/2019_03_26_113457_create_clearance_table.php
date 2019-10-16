<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearance', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', [1,2,3,4,5,6,7])->default(1);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('fullname');
            $table->string('email');           // important
            $table->string('mobile_number');
            $table->string('delivery_address');
            
            $table->string('invoice');
            $table->string('waybill');
            $table->string('shipping_company');
            
            $table->string('supplier_name');
            $table->string('supplier_email');   // important

            // admin 3 = customsclearance  admin 1 = sir david
            $table->float('admin3_price', 15, 2)->nullable();
            $table->float('admin1_price', 15, 2)->nullable();
            $table->string('customer_deposit')->nullable();
            $table->string('admin1_deposit')->nullable();
            $table->string('tracking_no')->nullable();
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
        Schema::dropIfExists('clearance');
    }
}
