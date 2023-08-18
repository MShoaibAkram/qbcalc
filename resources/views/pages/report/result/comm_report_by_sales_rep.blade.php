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
        <div class="col-6">
            <h4>Sales Rep: @if($selectedRepId != 'ALL') {{$selectedRepId}} @endif</h4>
        </div>

        <div class="col-6">
            <span class="text-lg"><b>From:</b> {{$fromDate}}</span>
            <span class="text-lg"><b>To:</b> {{$toDate}}</span>
        </div>
        <div class="col-12 bg-dark">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer Name</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>---</td>
                        <td>{{date('Y-m-d H:i:s')}}</td>
                        <td>
                            @foreach($finalResults as $res)
                                {{$res->name}},
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Price</th>
                    <th scope="col">Amount</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($finalResults as $res)
                        <tr>
                            <td>{{$res->ref_number}}</td>
                            <td>{{$res->qty}}</td>
                            @if($res->item != null)
                                <td>{{$res->item->price}}</td>
                            @else
                                <td>--</td>
                            @endif
                            <td>{{$res->total}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection