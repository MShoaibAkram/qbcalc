<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QbInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('qb_temp_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('txn_number')->nullable(true);
            $table->string('ref_number')->nullable(true);
            $table->string('customer_ref')->nullable(true);
            $table->string('customer_name')->nullable(true);
            $table->string('sub_total')->nullable(true);
            $table->string('memo')->nullable(true);
            $table->string('txn_date')->nullable(true);
            $table->string('line')->nullable(true);
            $table->string('description')->nullable(true);
            $table->string('amount')->nullable(true);
            $table->string('item_ref_name')->nullable(true);
            $table->string('item_list_id')->nullable(true);
            $table->string('item_type')->nullable(true);
            $table->string('quantity')->nullable(true);
            $table->string('rate')->nullable(true);
            $table->string('rate_percent')->nullable(true);
            $table->string('sales_rep_ref')->nullable(true);
            $table->string('group_name')->nullable(true);
            $table->string('total_amount')->nullable(true);
            $table->string('group_description')->nullable(true);
            $table->string('is_paid')->nullable(true);
            $table->string('po_number')->nullable(true);
            $table->string('commission')->nullable(true);
            $table->string('cost')->nullable(true);
            $table->string('bonus')->nullable(true);
            $table->string('invoice_type')->nullable(true);
            $table->string('gross_profit')->nullable(true);
            $table->string('profit')->nullable(true);
            $table->string('std_cost')->nullable(true);
            $table->string('txn_id')->nullable(true);
            $table->string('invoice_total')->nullable(true);
            $table->string('disc_amt')->nullable(true);
            $table->string('po_link')->nullable(true);
            $table->string('ship_to_city')->nullable(true);
            $table->string('ship_to_zip')->nullable(true);
            $table->string('bill_to_zip')->nullable(true);
            $table->string('currency_name')->nullable(true);
            $table->string('exchange_rate')->nullable(true);
            $table->string('ship_to_name')->nullable(true);
            $table->string('ship_to_adrr1')->nullable(true);
            $table->string('percent_paid')->nullable(true);

            $table->string('to_pct')->nullable(true);
            $table->string('from_pct')->nullable(true);


            $table->string('currency_list_id')->nullable(true);
            $table->string('custom_data')->nullable(true);
            $table->string('serial_number')->nullable(true);
            $table->string('due_date')->nullable(true);
            $table->string('paid_date')->nullable(true);
            $table->string('bill_link')->nullable(true);

            $table->timestamps();




        });

        Schema::create('qb_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('txn_number')->nullable(true);
            $table->string('ref_number')->nullable(true);
            $table->string('customer_ref')->nullable(true);
            $table->string('customer_name')->nullable(true);
            $table->string('sub_total')->nullable(true);
            $table->string('sales_rep_ref')->nullable(true);
            $table->string('po_number')->nullable(true);
            $table->string('memo')->nullable(true);
            $table->string('txn_date')->nullable(true);

            $table->string('to_pct')->nullable(true);
            $table->string('from_pct')->nullable(true);

            $table->string("invoice_type")->nullable(true);
            $table->string('gross_profit')->nullable(true);
            $table->string('profit')->nullable(true);
            $table->string('disc_amt')->nullable(true);
            $table->string('ship_to_city')->nullable(true);
            $table->string('bill_to_zip')->nullable(true);
            $table->string('ship_to_name')->nullable(true);
            $table->string('ship_to_adrr1')->nullable(true);
            $table->string('ship_to_zip')->nullable(true);
            $table->string('currency_name')->nullable(true);
            $table->string('exchange_rate')->nullable(true);
            $table->string('currency_list_id')->nullable(true);
            $table->string('custom_data')->nullable(true);
            $table->string('due_date')->nullable(true);
            $table->string('paid_date')->nullable(true);
            $table->string("cost")->nullable(true);
            $table->string("commission")->nullable(true);
            $table->string('last_processed_date')->nullable(true);
            $table->string('txn_id')->nullable(true);
            $table->string('percent_paid')->nullable(true);
            $table->string('invoice_total')->nullable(true);
            $table->boolean('is_paid')->nullable(true);
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
        Schema::dropIfExists('qb_invoices');
        Schema::dropIfExists('qb_temp_invoices');
    }
}
