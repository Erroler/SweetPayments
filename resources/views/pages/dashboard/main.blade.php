@extends('layouts.panel')

@section('title', 'SweetPayments - Dashboard')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content">
        <div class="header-content shadow-sm">
            <div class="d-flex justify-content-between">
                <div>
                    Your <span class="font-weight-bold">WebShop URL</span> is <a href="https://{{ $community->small_name }}.sweetpayments.net" target="_blank" rel="noopener">{{ $community->small_name }}.sweetpayments.net</a>
                </div>
                <div class="pr-4">
                    <a href="{{ route('panel.settings') }}">
                        <i class="fas fa-edit"></i> Change URL
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid p-3 p-lg-4">
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold color-1 text-uppercase mb-1">Revenue ({{ $month }})</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($revenue_month, 2) }}€</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-euro-sign fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold color-1 text-uppercase mb-1">Sales ({{ $month }})
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $number_sales_month }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold color-1 text-uppercase mb-1">Servers
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $servers }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-fighter-jet fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold color-1 text-uppercase mb-1">Current Balance
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($balance, 2) }}€</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-wallet fa-fw fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">Sales Statistics</h2>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body pb-3">
                    <div class="row">
                        <div class="col-sm-7 col-lg-8 col-xl-9 d-flex mr-n1">
                            <canvas id="sales_chart" class="flex-1"></canvas>
                        </div>
                        <div class="col-sm-5 col-lg-4 col-xl-3 ml-n2 mb-2">
                            <h5 class="mb-3">Options</h5>
                            <form class="pl-3" id="sales_chart_options">
                                <div class="form-group">
                                    <label>Timeframe</label>
                                    <select class="form-control form-control" name="timeframe">
                                        <option value="30">Last 30 days</option>
                                        <option value="90">Last 90 days</option>
                                        <option value="180">Last 6 months</option>
                                        <option value="365">Last 12 months</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Display</label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_type"
                                            id="RadioShowType1" value="revenue" checked>
                                        <label class="custom-control-label" for="RadioShowType1">
                                            Revenue € (after fees)
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_type"
                                            id="RadioShowType2" value="number_sales">
                                        <label class="custom-control-label" for="RadioShowType2">
                                            Number of Sales
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Show sales from</label>
                                    <select class="form-control form-control" name="subscription">
                                        <option value="all">All subscriptions</option>
                                        @foreach($subscriptions as $subscription)
                                        <option value="{{ $subscription->getRouteKey() }}">{{ $subscription->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-block btn-primary rounded-0 pointer"><i
                                        class="fas fa-sync-alt"></i>&nbsp;Load Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"
    integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
<script>
    Chart.defaults.global.legend.display = false;
</script>
@include('pages.dashboard.charts.sales')
@endsection