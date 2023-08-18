<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_master', function (Blueprint $table) {
            $table->id();
            $table->string('Division')->nullable(true);
            $table->string('CustomerNumber')->nullable(true);
            $table->string('CustomerName')->nullable(true);
            $table->string('AddressLine1')->nullable(true);
            $table->string('AddressLine2')->nullable(true);
            $table->string('City')->nullable(true);
            $table->string('State')->nullable(true);
            $table->string('ZipCode')->nullable(true);
            $table->string('PhoneNumber')->nullable(true);
            $table->string('Extension')->nullable(true);
            $table->string('IT_Enabled')->nullable(true);
            $table->string('BatchFax')->nullable(true);
            $table->string('DefaultCreditCardPmtType')->nullable(true);
            $table->string('ContactCode')->nullable(true);
            $table->string('CountryCode')->nullable(true);
            $table->string('ConsumerUID')->nullable(true);
            $table->string('ShipMethod')->nullable(true);
            $table->string('TaxSchedule')->nullable(true);
            $table->string('TaxExemptNumber')->nullable(true);
            $table->string('TermsCode')->nullable(true);
            $table->string('SalesPersonCode')->nullable(true);
            $table->string('MasterFileComment')->nullable(true);
            $table->string('SortField')->nullable(true);
            $table->string('TemporaryCustomer')->nullable(true);
            $table->string('OpenItemCustomer')->nullable(true);
            $table->string('StatmentCycle')->nullable(true);
            $table->string('PrintDunningMessage')->nullable(true);
            $table->string('CustomerType')->nullable(true);
            $table->string('PriceLevel')->nullable(true);
            $table->string('DateLastActivity')->nullable(true);
            $table->string('DateLastPayment')->nullable(true);
            $table->string('DateLastStatement')->nullable(true);
            $table->string('DateLastFinanceChrg')->nullable(true);
            $table->string('DateLastAging')->nullable(true);
            $table->string('AveDaysPaymentInvoice')->nullable(true);
            $table->string('AveDaysOverDue')->nullable(true);
            $table->string('NoInvInDaysCalc')->nullable(true);
            $table->string('DefaultSalesCode')->nullable(true);
            $table->string('CreditHoldFlag')->nullable(true);
            $table->string('PrimaryShipToCode')->nullable(true);
            $table->string('DateEstablished')->nullable(true);
            $table->string('AddressLine3')->nullable(true);
            $table->string('FaxNumber')->nullable(true);
            $table->string('EmailAddress')->nullable(true);
            $table->string('URLAddress')->nullable(true);
            $table->string('EncryptedCreditCardNumber')->nullable(true);
            $table->string('CreditCardExpireYR')->nullable(true);
            $table->string('CreditCardExpireMO')->nullable(true);
            $table->string('CardholderName')->nullable(true);
            $table->string('DefaultPaymentType')->nullable(true);
            $table->string('CreditCardCVV2Number')->nullable(true);
            $table->string('Last4UnencryptedCreditCardNos')->nullable(true);
            $table->string('CustomerDiscountRate')->nullable(true);
            $table->string('ServiceChargeRate')->nullable(true);
            $table->string('CreditLimit')->nullable(true);
            $table->string('LastPaymentAmount')->nullable(true);
            $table->string('HighStmntBalance')->nullable(true);
            $table->string('UnpaidServiceChrgYTD')->nullable(true);
            $table->string('SalesPTD')->nullable(true);
            $table->string('SalesYTD')->nullable(true);
            $table->string('SalesPYR')->nullable(true);
            $table->string('COGSPTD')->nullable(true);
            $table->string('COGSYTD')->nullable(true);
            $table->string('COGSPYR')->nullable(true);
            $table->string('CashReceivedPTD')->nullable(true);
            $table->string('CashReceivedYTD')->nullable(true);
            $table->string('CashReceivedPYR')->nullable(true);
            $table->string('FinanceChrgPTD')->nullable(true);
            $table->string('FinanceChrgYTD')->nullable(true);
            $table->string('FinanceChrgPYR')->nullable(true);
            $table->string('NumberOfInvoicesPTD')->nullable(true);
            $table->string('NumberOfInvoicesYTD')->nullable(true);
            $table->string('NumberOfInvoicesPYR')->nullable(true);
            $table->string('NumberOfFinanceChargesPTD')->nullable(true);
            $table->string('NumberOfFinanceChargesYTD')->nullable(true);
            $table->string('NumberOfFinanceChargesPYR')->nullable(true);
            $table->string('BalanceForward')->nullable(true);
            $table->string('CurrentBalance')->nullable(true);
            $table->string('Over30Days')->nullable(true);
            $table->string('Over60Days')->nullable(true);
            $table->string('Over90Days')->nullable(true);
            $table->string('Over120Days')->nullable(true);
            $table->string('OpenOrderAmount')->nullable(true);
            $table->string('UnpaidServiceChrgNextYear')->nullable(true);
            $table->string('SalesNextPeriod')->nullable(true);
            $table->string('COGSNextPeriod')->nullable(true);
            $table->string('CashReceivedNextPeriod')->nullable(true);
            $table->string('FinanceChargesNextPeriod')->nullable(true);
            $table->string('NoInvoicesNextPeriod')->nullable(true);
            $table->string('NoFinanceChargesNextPeriod')->nullable(true);
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
        //
    }
}
