@extends("layouts.main")

@section('title', 'Commission Plans')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Commission Plans') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Commission Plans') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="grossSalesVolume" type="checkbox" class="custom-control-input">
                <label for="grossSalesVolume" class="custom-control-label">Gross Sales Volume Range Table</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="commRatesByClass" type="checkbox" class="custom-control-input">
                <label for="commRatesByClass" class="custom-control-label">Commission Rates By Class</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="ratesByDiscountPercent" type="checkbox" class="custom-control-input">
                <label for="ratesByDiscountPercent" class="custom-control-label">Rates by Discount Percent</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="agingRangeTable" type="checkbox" class="custom-control-input">
                <label for="agingRangeTable" class="custom-control-label">Aging Range Table</label>
            </div>
        </div>
    </div>
</div>
@endsection