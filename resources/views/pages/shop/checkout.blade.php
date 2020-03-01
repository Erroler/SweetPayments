@extends('layouts.panel')

@section('title', 'SweetPayments - '. $community->full_name)

@section('content')

<div class="full-height-screen" id="webshop-screen">
    <div class="container py-4">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-12 col-md-11 col-lg-10 col-xl-8">
                <div class="card rounded-0 shadow">
                    <div class="card-body p-3">
                        <div class="d-flex pl-0">
                            <div class="mr-4">
                                <img src="{{ $community->avatar }}" class="rounded img-fluid">
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <h1 class="card-title mb-1">{{ $community->full_name }}&nbsp;<span
                                        class="h3 @if(strlen($community->full_name) > 17) d-none @endif"
                                        style="position:relative;bottom:3px"><span
                                            class="badge badge-secondary">WebShop</span></span></h1>

                                <div class="d-flex justify-content-between flex-grow-1">
                                    <div class="pl-2 pt-1 d-flex flex-column justify-content-center">
                                        <dl class="mb-0">
                                            <dt>Members</dt>
                                            <dd>{{ $community->members }}</dd>
                                            <dt>Servers</dt>
                                            <dd>{{ $community->servers->count() }} servers</dd>
                                        </dl>
                                    </div>
                                    <div class="align-self-end flex-grow-1 text-right">
                                        <a class="mb-0 text-info small" href="https://sweetpayments.net"
                                            target="_blank">Powered by SweetPayments.net</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded-0 shadow mt-3">
                    <div class="card-body pt-3 pl-3 pr-3 pb-1 d-flex flex-column pb-2">
                        <h4 class="mb-0 text-center font-weight-bold">Choose Payment Method</h4>
                        <hr class="w-100">
                        <div class="p-1 mt-n2">
                            <div class="row justify-content-around">
                                @if(in_array('paysafecard', $subscription->payment_methods))
                                    <div class="col border border-info mx-2 d-flex flex-column justify-content-center">
                                        <a href="{{ route('webshop.buy.paysafecard', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                                            <img src="{{ asset('images/paysafecard.jpg') }}" class="img-fluid">
                                        </a>
                                    </div>
                                @endif
                                @if(in_array('paypal', $subscription->payment_methods))
                                    <div class="col border border-info mx-2 d-flex flex-column justify-content-center">
                                        <a href="{{ route('webshop.buy.paypal', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                                            <img src="{{ asset('images/paypal.png') }}" class="img-fluid">
                                        </a>
                                    </div>
                                @endif
                            </div>                                
                            <a class="small text-center d-block mt-2" href="{{ route('webshop.index', ['webshop_name' => $community->small_name ]) }}">Cancel Payment</a>
                        </div>
                        {{-- <hr class="w-100 mt-2">
                        <div class="p-1 mt-n2 mb-0">
                                <h4 class="mb-2 text-center font-weight-bold">Select Payment Method</h4>
                                @if(in_array('paypal', $subscription->payment_methods))
                                <div class="row justify-content-center mt-n4">
                                    <div class="col-6">
                                        <a href="{{ route('webshop.buy.paypal', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                                            <img src="https://sweetpayments.net/landing_page/paypal.33a6a6d5.png" class="img-fluid mt-n3">
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @if(in_array('paypal', $subscription->payment_methods) && in_array('paysafecard', $subscription->payment_methods))
                                <p class="text-center m-1 mt-n4">
                                        or
                                </p>
                                @endif
                                @if(in_array('paysafecard', $subscription->payment_methods))
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        <a href="{{ route('webshop.buy.paysafecard', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                                            <img src="https://sweetpayments.net/landing_page/paysafecard.32b09e1c.jpg" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                @endif
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection