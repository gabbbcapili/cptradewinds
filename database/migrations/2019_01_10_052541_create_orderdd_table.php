<?php
// OrderDetails
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdd', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orderhh')->onDelete('cascade');
            $table->unsignedInteger('qty');
            $table->string('type')->nullable();
            $table->float('length', 8, 2)->nullable();
            $table->float('width', 8, 2)->nullable();
            $table->float('height', 8, 2)->nullable();
            $table->float('weight', 8, 2)->nullable();
            $table->string('measurement')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
        // validation
        // either qty + type
        // or qty length width height weight measurement
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderdd');
    }
}
