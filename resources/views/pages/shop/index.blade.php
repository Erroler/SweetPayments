@extends('layouts.panel')

@section('title', 'SweetPayments - '. $community->full_name)

@section('content')

<div class="full-height-screen" id="webshop-screen">
    <div class="container py-4">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-12 col-md-11 col-lg-10 col-xl-8">
                <div class="card rounded-0 shadow">
                    <div class="card-body p-3">
                        {{-- <h3 class="card-title">{{ $community->full_name }}</h3>
                        <div class="container-fluid">
                            <div class="row no-gutters">
                                <div class="col-8">
                                    <dl class="my-2 ml-1">
                                        <dt class="">Name</dt>
                                        <dd class="">{{ $community->full_name }}</dd>

                                        <dt class="">Members</dt>
                                        <dd class="">{{ $community->members }}</dd>

                                        <dt class="">Servers</dt>
                                        <dd class="">{{ $community->servers->count() }}</dd>
                                    </dl>
                                </div>
                                <div class="col-4 d-flex flex-row-reverse align-items-start">
                                    <img src="{{ $community->avatar }}" class="rounded img-fluid">
                                </div>
                            </div>
                        </div> --}}
                        <div class="d-flex pl-0">
                            <div class="mr-4">
                                <img src="{{ $community->avatar }}" class="rounded img-fluid">
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                    <h1 class="card-title mb-1">{{ $community->full_name }}&nbsp;<span class="h3 @if(strlen($community->full_name) > 17) d-none @endif"
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
                                        {{-- <a href="https://steamcommunity.com/gid/{{ $community->group_id }}" ref="noopener"
                                            target="_blank"><i class="fab fa-steam mt-n5"></i> Steam Group</a> --}}
                                        <a class="mb-0 text-info small" href="https://sweetpayments.net" target="_blank">Powered by SweetPayments.net</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded-0 shadow mt-3">
                    <div class="card-body pt-3 pl-3 pr-3 pb-1">
                        <h4 class="mb-3 text-center">Subscriptions</h4>
                        <hr>
                        @forelse($subscriptions as $subscription)
                            <div class="d-flex flex-column mb-3 pt-1">
                                <div class="d-flex">
                                    <img src="/images/server2.png" class="rounded img-fluid">
                                    <div class="d-flex flex-grow-1">
                                        <div class="ml-3 d-flex flex-column justify-content-center">
                                            <h4>{{ $subscription->name }}</h4>
                                            <p class="mb-0">
                                                <span data-toggle="tooltip" data-placement="top" title="How much the subscription lasts.">
                                                    <i class="far fa-calendar-alt"></i> {{ $subscription->durationFormatted() }}
                                                </span> 
                                                &nbsp;|&nbsp; 
                                                {{ number_format($subscription->pricing, 2) }}€
                                                &nbsp;|&nbsp; 
                                                {{ implode(', ', array_map(function($method) { return ucfirst($method); }, $subscription->payment_methods)) }}</p>
                                        </div>
                                        <div class="align-self-center flex-grow-1 d-flex flex-row-reverse">
                                            <a class="font-weight-bold text-white btn btn-primary btn-lg" href="{{ route('webshop.buy', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="w-100">
                            @endif
                        @empty
                            No subscriptions.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    });
</script>

@endsection