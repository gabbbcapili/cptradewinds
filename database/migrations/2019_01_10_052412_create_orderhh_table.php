<?php
// Order Header

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOrderhhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderhh', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipment_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('status')->default(1);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('source_id')->nullable();
            $table->string('token');
            // supplier
            $table->string('supplier')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('import_details')->nullable();
            // new
            $table->float('cbm', 15, 2)->nullable();
            $table->float('weight', 15, 2)->nullable();
            $table->string('boxes_received')->nullable();
            $table->string('warehouse')->nullable();
            $table->enum('quoteFor', [1,2])->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('withQuote')->default(0);
            $table->string('shipment_proof')->nullable();
            // invoice / payment images
            $table->string('invoice')->nullable();
            $table->string('boxes')->nullable();
            
            $table->float('price', 15, 2)->nullable();
            $table->string('price_date')->nullable();
            $table->string('payment')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('shipped_at')->nullable();
            $table->string('delivery_address')->nullable();
            $table->float('delivery_price', 15, 2)->nullable();
            $table->string('pickup_type')->nullable();
            // pickup deliver
            $table->float('extra_charges', 15, 2)->default(0);
            $table->string('delivery_receipt')->nullable();
            $table->string('deliver_company_name')->nullable();
            $table->string('pickup_person')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('notes')->nullable();
            $table->string('supplier_payment')->nullable();
            $table->string('delivery_contact_number')->nullable();
            $table->timestamps();
        });
        DB::update("ALTER TABLE orderhh AUTO_INCREMENT = 10000;");
    }
    // Status
    // 1 - Pending
    // 2 - Wating for Supplier
    // 3 -  Wating for Admin
    // 4 - Wating for Payment
    // 5 - Paid / Wating for Supplier
    // 6 - Ready for Shipment
    // 7 - Shipped
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderhh');
    }
}
