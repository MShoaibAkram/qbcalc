@extends("layouts.main")

@section('title', 'History Reports')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('History Reports') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('History Reports') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="historyWithDetail" type="checkbox" class="custom-control-input">
                <label for="historyWithDetail" class="custom-control-label">History with detail lines</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="invSummaryByInvNumDate" type="checkbox" class="custom-control-input">
                <label for="invSummaryByInvNumDate" class="custom-control-label">Invoice summary by Inv, Num, Date</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="InvSummaryByRepId" type="checkbox" class="custom-control-input">
                <label for="InvSummaryByRepId" class="custom-control-label">Invoice Summary by Rep ID</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="InvSummaryByRepName" type="checkbox" class="custom-control-input">
                <label for="InvSummaryByRepName" class="custom-control-label">Invoice Summary by Rep Name</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="repByCustomerDetail" type="checkbox" class="custom-control-input">
                <label for="repByCustomerDetail" class="custom-control-label">Rep by Customer Detail</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="grossProfitBySalesRep" type="checkbox" class="custom-control-input">
                <label for="grossProfitBySalesRep" class="custom-control-label">Gross Profit by Sales Rep</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="poMatchExceptionReport" type="checkbox" class="custom-control-input">
                <label for="poMatchExceptionReport" class="custom-control-label">PO Matching Exception Report</label>
            </div>
        </div>
    </div>
</div>
@endsection