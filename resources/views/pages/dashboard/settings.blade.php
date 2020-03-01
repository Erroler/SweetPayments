@extends('layouts.panel')

@section('title', 'SweetPayments - Settings')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">Settings</h2>
            </div>
            @if($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible shadow-sm">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endforeach
            @endif
            @if(session('status'))
            <div class="alert alert-primary alert-dismissible shadow-sm">
                <i class="fas fa-check"></i>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card shadow-sm rounded-0 mb-3">
                <div class="card-body pb-2">
                    <h6 class="mb-3">Community Details</h6>
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-sm-7 col-md-5 col-lg-3 col-xl-2">
                                <img src="{{ $community->avatar }}" class="rounded img-fluid">
                            </div>
                            <div class="col-sm-5 col-md-7 col-lg-9 col-xl-10">
                                <dl class="mb-2">
                                    <dt class="">Name <span class="text-secondary" data-toggle="tooltip" data-placement="top" title="Name of your community's steam group.">(?)</span></dt>
                                    <dd class="">{{ $community->full_name }}</dd>

                                    <dt class="">Members <span class="text-secondary" data-toggle="tooltip" data-placement="top" title="Number of members your community's steam group has.">(?)</span></dt>
                                    <dd class="">{{ $community->members }}</dd>

                                    <dt class="">Servers <span class="text-secondary" data-toggle="tooltip" data-placement="top" title="Number of servers linked on SweetPayments.">(?)</span></dt>
                                    <dd class="">{{ $community->servers->count() }}</dd>

                                    {{-- <dt class="">Registration Date</dt>
                                    <dd class="">{{ $community->date_formated }}</dd> --}}

                                    <small class="text-muted">These details will be displayed in your web shop. They are updated automatically every 24
                                        hours.</small>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm rounded-0 mb-3">
                <div class="card-body">
                    <form action="{{ route('panel.settings') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="webshop_name" class="font-weight-slightly-bold">Webshop URL</label>
                            <div class="input-group w-50">
                                <input type="text" class="form-control rounded-0" id="webshop_name" name="webshop_name"
                                    value="{{ $community->shop_address }}" minlength="3" maxlength="20">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0" id="basic-addon2">.sweetpayments.net</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">This is the URL your players can access in order to buy
                                subscriptions. <a href="http://{{ $community->small_name }}.sweetpayments.net/" target="_blank" rel="noopener">Visit Webshop</a></small>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Save</button>
                    </form>

                </div>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <form action="{{ route('panel.settings.callback') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="webshop_name" class="font-weight-slightly-bold">Callback URL</label>
                            <div class="input-group w-50">
                                <input type="url" class="form-control rounded-0" id="webshop_name" name="callback_url"
                                value="{{ $community->callback_url }}">
                            </div>
                            <small class="form-text text-muted">The URL you insert will be called everytime a sale is made on your webshop.</small>
                            <small class="form-text text-muted">The following POST parameters will be available:</small> 
                            <div class="pl-2 pt-1">
                                <small class="form-text text-muted">
                                    <dl class="ml-2">
                                        <dt>Secret_Key</dt>
                                        <dd>You should use this parameter to validate the request was made by SweetPayments.net.<br>It will always be <em>{{ md5($community->getRouteKey()) }}</em> for your account.<br>Do not share this key with anyone.</dd>    
                                        <dt>Player_SteamID64</dt>
                                        <dd>The SteamID64 of the player who made the purchase. E.g. {{ \Auth::user()->steamid }}</dd>
                                        <dt>Player_Name</dt>
                                        <dd>The name of the player who made the purchase. E.g. {{ \Auth::user()->username }}</dd> 
                                        <dt>Player_IP</dt>
                                        <dd>The IP address of the player who made the purchase. E.g. 165.68.73.214</dd> 
                                        <dt>Subscription_Name</dt>
                                        <dd>The name of the subscription the player bought. E.g. @if($community->subscriptions->count() > 0) {{ $community->subscriptions[0]->name }} @else "VIP" @endif</dd>
                                        <dt>Expiration_Date</dt>
                                        <dd>Expiration Date of the subscription in YYYY-MM-DD HH:MM format. E.g. 2019-12-05 17:45</dd>
                                        <dt>Payment_Method</dt>
                                        <dd>Payment method of the sale. Can be one of the following values: paypal, paysafecard.</dd>
                                        <dt>Revenue</dt>
                                        <dd>Revenue from the sale after fees are applied. E.g. 18.90</dd>
                                    </dl>
                                </small> 
                            </div>
                            <small class="form-text text-muted">Make sure your endpoint returns 200 status code otherwise up to 5 retries will be made.</small> 

                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Save</button>
                    </form>

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
    });

</script>

@endsection