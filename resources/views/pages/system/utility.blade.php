@extends("layouts.main")

@section('title', 'System Utilities')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('System Utilities') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('System Utilities') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-10">
        <!-- small box -->
        <div class="small-box p-3">
            <div class="row list-container">
                <div class="col-4">
                    <div class="bg-primary p-2 card-header">System Utilities</div>
                    <div class="custom-control custom-checkbox mt-2">
                        <input id="changeDBLocation" type="checkbox" class="custom-control-input">
                        <label for="changeDBLocation" class="custom-control-label">Change Database Location</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input id="dbBackUp" type="checkbox" class="custom-control-input">
                        <label for="dbBackUp" class="custom-control-label">Make Database Backup</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input id="compactDB" type="checkbox" class="custom-control-input">
                        <label for="compactDB" class="custom-control-label">Compact Database</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-primary p-2 card-header">Delete Utilities</div>
                    <div class="custom-control custom-checkbox mt-2">
                        <input id="delCommHistory" type="checkbox" class="custom-control-input">
                        <label for="delCommHistory" class="custom-control-label">Delete Commission History</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input id="clearData" type="checkbox" class="custom-control-input">
                        <label for="clearData" class="custom-control-label">Clear Data</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-2">
                        <input id="clearSalesPeople" type="checkbox" class="custom-control-input" checked>
                        <label for="clearSalesPeople" class="custom-control-label">Clear Salespeople</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-2">
                        <input id="clearBypassItem" type="checkbox" class="custom-control-input" checked>
                        <label for="clearBypassItem" class="custom-control-label">Clear Bypass Items</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-2">
                        <input id="clearPayment" type="checkbox" class="custom-control-input" checked>
                        <label for="clearPayment" class="custom-control-label">Clear Payments</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-2">
                        <input id="clearCustomerItem" type="checkbox" class="custom-control-input" checked>
                        <label for="clearCustomerItem" class="custom-control-label">Clear Customers & Items</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-primary p-2 card-header">Other Utilities</div>
                    <div class="custom-control custom-checkbox mt-2">
                        <input id="exportData" type="checkbox" class="custom-control-input">
                        <label for="exportData" class="custom-control-label">Export Data</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input id="editProcessedItem" type="checkbox" class="custom-control-input">
                        <label for="editProcessedItem" class="custom-control-label">View/Edit Processed Items</label>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <label>The current Database Location is: </label>
                <label>The current Program Location is: </label>
            </div>
        </div>
    </div>
</div>
@endsection