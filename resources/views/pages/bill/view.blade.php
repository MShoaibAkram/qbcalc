@extends("layouts.main")

@section('title', 'Commission Request Processing Report')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Commission Request Processing Report') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Commission Request Processing Report') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        
    </div>
</div>
@endsection