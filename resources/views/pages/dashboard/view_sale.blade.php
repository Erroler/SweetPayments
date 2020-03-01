@extends('layouts.panel')

@section('title', 'SweetPayments - Viewing Sale')

@section('additional_headers')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js" defer></script>
@endsection

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2"><a href="{{ url()->previous() }}" id="go_back_btn"><i class="fas fa-backward"></i></a>&nbsp; View Sale
                </h2>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="col-6 pr-md-0">
                            <dl class="row">
                                <dt class="col-sm-4">Date of Purchase</dt>
                                <dd class="col-sm-8">{{ $sale->date_formated(null, true) }}</dd>

                                <dt class="col-sm-4">Expires on</dt>
                                <dd class="col-sm-8">
                                    @if(\Carbon\Carbon::parse($sale->expires_on)->isPast())
                                        <span class="text-danger">{{  $sale->date_formated('expires_on', true) }}</span>
                                    @else
                                        {{ $sale->date_formated('expires_on', true) }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Subscription</dt>
                                <dd class="col-sm-8">{{ $sale->subscription->name }}</dd>

                                <dt class="col-sm-4">Payment Method</dt>
                                <dd class="col-sm-8">{{ ucfirst($sale->payment_method) }}</dd>

                                <dt class="col-sm-4">Payment Amount</dt>
                                <dd class="col-sm-8">{{ number_format($sale->revenue_before_tax, 2) }} €</dd>

                                <dt class="col-sm-4">Revenue (after fees)</dt>
                                <dd class="col-sm-8">{{ number_format($sale->revenue_after_tax, 2) }} €</dd>

                            </dl>
                        </div>
                        <div class="col-6 pr-md-0">
                            <dl class="row">
                                <dt class="col-sm-4">Player Name <span class="text-secondary" data-toggle="tooltip"
                                        data-placement="top" title=""
                                        data-original-title="This only reflects the player name at the time of the purchase. It might be outdated.">(?)</span>
                                </dt>
                                <dd class="col-sm-8">{{ $sale->player_name }}</dd>

                                <dt class="col-sm-4">SteamID64</dt>
                                <dd class="col-sm-8"><a href="http://steamcommunity.com/profiles/{{$sale->steamid64}}"
                                        rel="noopener" target="_blank">{{ $sale->steamid64 }}</a></dd>

                                <dt class="col-sm-4">IP address</dt>
                                <dd class="col-sm-8">{{ $sale->ip_address }}</dd>

                                <dt class="col-sm-4">Location</dt>
                                <dd class="col-sm-8">{{ $sale->getLocationFormated() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        document.querySelector('#go_back_btn').addEventListener('click', function(e) {
            if (window.history) {
                e.preventDefault();
                window.history.back();
            }
        }); 
    });
</script>

@endsection