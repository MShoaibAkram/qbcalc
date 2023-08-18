<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("payment_id")->nullable(true);
            $table->dateTimeTz("modified_at")->nullable(true);
            $table->string("txn_number")->nullable(true);
            $table->string("customer_name",)->nullable(true);
            $table->string("ar_account")->nullable(true);
            $table->date("txn_date")->nullable(true);
            $table->string("ref_number")->nullable(true);
            $table->double("amount")->nullable(true);
            $table->string("payment_method")->nullable(true);
            $table->double("unused_payment")->nullable(true);
            $table->double("unused_credits",)->nullable(true);
            $table->integer("exchange_rate")->nullable(true);
            $table->string("app_txn_id")->nullable(true);
            $table->string("app_txn_type")->nullable(true);
            $table->date("app_txn_date")->nullable(true);
            $table->string("app_refnumber")->nullable(true);
            $table->double("app_balance_remaining",)->nullable(true);
            $table->double("app_amount")->nullable(true);
            $table->double("app_discount_amount")->nullable(true);
            $table->boolean("processed")->nullable(true);
            $table->string("customer_list_id")->nullable(true);
            $table->dateTimeTz("processed_at",)->nullable(true);
            $table->integer("comm_track_type")->nullable(true);
            $table->string("txn_id")->nullable(true);
            $table->string("ar_account_qb_list_id")->nullable(true);
            $table->string("payment_method_qb_list_id",)->nullable(true);
            $table->string("deposit_to_account_qb_list_id")->nullable(true);
            $table->string("deposit_to_account")->nullable(true);
            $table->string("app_discount_account_qb_list_id")->nullable(true);
            $table->string("memo")->nullable(true);
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
        Schema::dropIfExists('receive_payment_requests');
    }
}
