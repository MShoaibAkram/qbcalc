@extends("layouts.main")

@section('title', 'Custom')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Custom') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Custom') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="actualPercentage" type="checkbox" class="custom-control-input">
                <label for="actualPercentage" class="custom-control-label">Actual Percentage to Rep after split</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="detailStatement" type="checkbox" class="custom-control-input">
                <label for="detailStatement" class="custom-control-label">Detail Statement Rep/Customer W/Desc</label>
            </div>
        </div>
    </div>
</div>
@endsection