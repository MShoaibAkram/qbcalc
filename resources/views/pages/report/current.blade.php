@extends("layouts.main")

@section('title', 'Current Reports')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Current Reports') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Current Reports') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="statementRepCustomer" type="checkbox" class="custom-control-input">
                <label for="statementRepCustomer" class="custom-control-label">Detail Statement Rep / Customer</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="statementByInvoiceNum" type="checkbox" class="custom-control-input">
                <label for="statementByInvoiceNum" class="custom-control-label">Detail Statement by Invoice Number</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="commStatementSummary" type="checkbox" class="custom-control-input">
                <label for="commStatementSummary" class="custom-control-label">Commission Statement Summary</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="totalCommByRepId" type="checkbox" class="custom-control-input">
                <label for="totalCommByRepId" class="custom-control-label">Total Commission by Rep Id</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="repByCustomerDetail" type="checkbox" class="custom-control-input">
                <label for="repByCustomerDetail" class="custom-control-label">Rep by Customer Detail</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="invListByInvNum" type="checkbox" class="custom-control-input">
                <label for="invListByInvNum" class="custom-control-label">Invoice list by invoice number</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="paidInvReport" type="checkbox" class="custom-control-input">
                <label for="paidInvReport" class="custom-control-label">Paid Invoice Report</label>
            </div>
        </div>
    </div>
</div>
@endsection