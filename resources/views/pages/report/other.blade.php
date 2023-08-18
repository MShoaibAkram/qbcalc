@extends("layouts.main")

@section('title', 'Other Reports')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Other Reports') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Other Reports') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="totalByManagerRepId" type="checkbox" class="custom-control-input">
                <label for="totalByManagerRepId" class="custom-control-label">Totals By Manager Rep Id</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="commReportByGroup" type="checkbox" class="custom-control-input">
                <label for="commReportByGroup" class="custom-control-label">Commission Report by Group</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="salesVolumeRepRate" type="checkbox" class="custom-control-input">
                <label for="salesVolumeRepRate" class="custom-control-label">Sales Volume Rep Rates</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="ageReportDueDate" type="checkbox" class="custom-control-input">
                <label for="ageReportDueDate" class="custom-control-label">Ageing Report Due Date</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="salesRepReport" type="checkbox" class="custom-control-input">
                <label for="salesRepReport" class="custom-control-label">Sales Rep Report</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="bypassItemReport" type="checkbox" class="custom-control-input">
                <label for="bypassItemReport" class="custom-control-label">ByPass Item Report</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="customerItemSummary" type="checkbox" class="custom-control-input">
                <label for="customerItemSummary" class="custom-control-label">Customer Item Summary</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="snapShotReport" type="checkbox" class="custom-control-input">
                <label for="snapShotReport" class="custom-control-label">SnapShot Report</label>
            </div>
        </div>
    </div>
</div>
@endsection