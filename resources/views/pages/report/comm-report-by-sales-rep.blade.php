@extends("layouts.main")

@section('title', 'Commission Report By Sales Rep')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Commission Report By Sales Rep') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Commission Report By Sales Rep') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="bg-primary p-2 card-header">Selected Rep for Report</div>
        <form class="small-box p-3" method="post" action="{{url('report/commRptBySalesRep')}}">
            @csrf
            <div class="form-group">
                <label for="repId">Rep ID</label>
                <select name="repId" id="repId" class="form-control">
                    <option value="ALL">ALL (All Sales Reps)</option>
                    <option value="EXCLUDE HOUS">All Sales Reps Except HOUS</option>
                    @foreach($ardSalesMasterFile as $arSalesMaster)
                        <option value="{{$arSalesMaster->salespersonNumber}}">{{$arSalesMaster->salespersonNumber}} ({{$arSalesMaster->name}})</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-1">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="startRepOnNewPage" name="startRepOnNewPage" checked/>
                    <label for="startRepOnNewPage" class="custom-control-label">Start each Rep on a new page</label>
                </div>
            </div>
            <div class="mt-2 d-flex">
                <button class="btn btn-success ml-auto mr-2">Write PDFs</button>
                <button class="btn btn-primary mr-2">Preview Report</button>
                <button class="btn btn-danger">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection