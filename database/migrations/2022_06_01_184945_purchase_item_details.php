<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseItemDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('txn_id')->nullable(true);
            $table->string('txn_number')->nullable(true);
            $table->string('vendor_list_id')->nullable(true);
            $table->string('vendor_name')->nullable(true);
            $table->string('class_list_id')->nullable(true);
            $table->string('class_name')->nullable(true);
            $table->string('template_list_id')->nullable(true);
            $table->string('template_name')->nullable(true);
            $table->string('txn_date')->nullable(true);
            $table->string('ref_number')->nullable(true);
            $table->string('vendor_addr1')->nullable(true);
            $table->string('vendor_addr2')->nullable(true);
            $table->string('vendor_addr3')->nullable(true);
            $table->string('vendor_addr4')->nullable(true);
            $table->string('vendor_addr5')->nullable(true);
            $table->string('vendor_city')->nullable(true);
            $table->string('vendor_state')->nullable(true);
            $table->string('vendor_postal_code')->nullable(true);
            $table->string('vendor_country')->nullable(true);
            $table->string('vendor_note')->nullable(true);
            $table->string('ship_addr1')->nullable(true);
            $table->string('ship_addr2')->nullable(true);
            $table->string('ship_addr3')->nullable(true);
            $table->string('ship_addr4')->nullable(true);
            $table->string('ship_addr5')->nullable(true);
            $table->string('ship_city')->nullable(true);
            $table->string('ship_state')->nullable(true);
            $table->string('ship_postal_code')->nullable(true);
            $table->string('ship_country')->nullable(true);
            $table->string('ship_note')->nullable(true);
            $table->string('due_date')->nullable(true);
            $table->string('expected_date')->nullable(true);
            $table->string('ship_method_list_id')->nullable(true);
            $table->string('ship_method_name')->nullable(true);
            $table->string('fob')->nullable(true);
            $table->string('total_amount')->nullable(true);
            $table->string('is_manually_closed')->nullable(true);
            $table->string('is_fully_received')->nullable(true);
            $table->string('vendor_msg')->nullable(true);
            $table->string('other1')->nullable(true);
            $table->string('other2')->nullable(true);
            $table->string('memo')->nullable(true);
            $table->string('sale_order_ref_number')->nullable(true);
            $table->string('custom_data')->nullable(true);
            $table->timestamp('modified_at');
            $table->timestamps();

        });


        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_id')->nullable(true);
            $table->string('txn_line_id')->nullable(true);
            $table->string('item_ref_list_id')->nullable(true);
            $table->string('item_ref_name')->nullable(true);
            $table->string('manufacture_part_number')->nullable(true);
            $table->string('desc')->nullable(true);
            $table->string('quantity')->nullable(true);
            $table->string('rate')->nullable(true);
            $table->string('class_list_id')->nullable(true);
            $table->string('class_name')->nullable(true);
            $table->string('service_date')->nullable(true);
            $table->string('sales_tax_list_id')->nullable(true);
            $table->string('sales_tax_name')->nullable(true);
            $table->string('receive_quantity')->nullable(true);
            $table->string('is_manually_closed')->nullable(true);
            $table->string('other1')->nullable(true);
            $table->string('other2')->nullable(true);
            $table->string('customer_list_id')->nullable(true);
            $table->string('customer_name')->nullable(true);
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
        Schema::dropIfExists('purchase_order_details');
        Schema::dropIfExists('purchase_orders');
    }
}
