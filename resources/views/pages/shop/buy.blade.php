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
                    <div class="card-body pt-3 pl-3 pr-3 pb-1 d-flex flex-column">
                        @if($steam === null)
                        <span class="mb-3 text-center h5">
                            Please login to continue
                        </span>
                        <a class="mb-1 text-center"
                            href="{{ route('webshop.auth', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                            <img src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png"
                                class="mx-auto mb-3 mt-1 pr-2">
                        </a>
                        @else
                        <h4 class="mb-0 text-center font-weight-bold">Buy Subscription</h4>
                        <hr class="w-100">
                        <div class="p-1 mt-n2">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="personaName">Player Name</label>
                                    <input type="text" class="form-control rounded-0" id="personaName"
                                        value="{{ $steam->personaname }}" disabled>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="lastName">Profile URL</label>
                                    <input type="text" class="form-control rounded-0" id="lastName"
                                        value="{{ $steam->profileurl}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="personaName">Subscription Name</label>
                                    <input type="text" class="form-control rounded-0" id="personaName"
                                        value="{{ $subscription->name }}" disabled>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lastName">Price</label>
                                    <input type="text" class="form-control rounded-0" id="lastName"
                                        value="{{ number_format($subscription->pricing, 2) }}€" disabled>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lastName">Duration</label>
                                    <input type="text" class="form-control rounded-0" id="lastName"
                                        value="{{ $subscription->durationFormatted() }}" disabled>
                                </div>
                            </div>
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
                        <a href="{{ route('webshop.checkout', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}" class="btn btn-primary d-inline-block mx-auto rounded-0 mb-2 mt-1 px-4"><i class="fas fa-shopping-cart"></i>&nbsp;Proceed to Checkout</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection