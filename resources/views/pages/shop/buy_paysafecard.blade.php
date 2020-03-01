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
                        <h4 class="mb-0 text-center font-weight-bold">Pay with Paysafecard</h4>
                        <hr class="w-100">
                        <form class="p-1 mt-n2 px-3" method="POST" action="#">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label font-weight-bold pr-0">Subscription</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ $subscription->name }}  ({{ $subscription->durationFormatted() }})">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label font-weight-bold">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ number_format($subscription->pricing, 2) }}€">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label font-weight-bold">PIN</label>
                                <div class="col-auto">
                                    <input type="text" class="form-control rounded-0" style="width: 3.9em"
                                        placeholder="0000" minlength="4" maxlength="4" name="pin1" required>
                                </div>
                                <div style="position:relative; top: 6px" class="text-muted">
                                    —
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control rounded-0" style="width: 3.9em"
                                        placeholder="0000" minlength="4" maxlength="4" name="pin2" required>
                                </div>
                                <div style="position:relative; top: 6px" class="text-muted">
                                    —
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control rounded-0" style="width: 3.9em"
                                        placeholder="0000" minlength="4" maxlength="4" name="pin3" required>
                                </div>
                                <div style="position:relative; top: 6px" class="text-muted">
                                    —
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control rounded-0" name="pin4" style="width: 3.9em"
                                        placeholder="0000" minlength="4" maxlength="4" required>
                                </div>
                            </div>
                            <div class="text-center text-danger font-weight-bold mb-n2 d-none mt-2" id="bad-result">
                                <i class="fas fa-exclamation-circle"></i>&nbsp;<span></span>
                            </div>
                            <div class="text-center text-success font-weight-bold d-none my-2 pt-2" id="good-result">
                                <i class="fas fa-check-circle"></i>&nbsp;<span></span>
                            </div>
                            <button class="mt-4 mb-1 btn btn-primary rounded-0 shadow-sm d-block mx-auto px-3"
                                type="submit"><i class="fas fa-shopping-cart"></i>&nbsp;Make Payment</button>
                            {{-- <p class="mt-2 mb-0 text-primary">Please await upto 30 seconds for the payment to be complete.</p> --}}
                            <div id="payment_wait_message" class="alert alert-primary rounded-0 payment_wait_message flex-column align-items-center mt-4 font-weight-bold d-none"
                                role="alert">
                                <div class="spinner-border text-primary mb-2" role="status"></div>
                                Please wait upto 30 seconds for the payment to be completed.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Toggle visibility of submit button/ wait message.
        const submit_button = form.querySelector('button[type=submit]');
        const payment_wait_message = form.querySelector('#payment_wait_message');
        const good_result = form.querySelector('#good-result');
        const bad_result = form.querySelector('#bad-result');
        bad_result.classList.add('d-none');
        //
        submit_button.classList.add('d-none');
        submit_button.classList.remove('d-block');
        payment_wait_message.classList.remove('d-none');
        payment_wait_message.classList.add('d-flex');
        form.querySelectorAll('input').forEach(input => {
            input.readOnly = true;
        });
        // Submit form.
        const body = new URLSearchParams();
        for (const pair of new FormData(form)) {
            body.append(pair[0], pair[1]);
        }
        fetch('{{ route('webshop.buy.paysafecard', ['webshop_name' => $community->small_name, 'subscription' => $subscription->getRouteKey()]) }}', {
            credentials: 'same-origin',
            method: 'post',
            body
        })
        .then(response => response.json())
        .then( ({result}) => {
            if(result === 'done') {
                good_result.querySelector('span').innerHTML = 'Payment completed successfully!';
                good_result.classList.remove('d-none');
                payment_wait_message.classList.add('d-none');
                payment_wait_message.classList.remove('d-flex');
                window.location.replace('{{ route('webshop.success', ['webshop_name' => $community->small_name]) }}');
            } else {
                submit_button.classList.remove('d-none');
                submit_button.classList.add('d-block');
                payment_wait_message.classList.add('d-none');
                payment_wait_message.classList.remove('d-flex');
                bad_result.querySelector('span').innerHTML = 'Invalid PIN or insufficient balance.';
                bad_result.classList.remove('d-none');
                form.querySelectorAll('input').forEach(input => {
                    input.readOnly = false;
                });
            }
        })
        .catch(error => {
            submit_button.classList.remove('d-none');
            submit_button.classList.add('d-block');
            payment_wait_message.classList.add('d-none');
            payment_wait_message.classList.remove('d-flex');
            bad_result.querySelector('span').innerHTML = 'Some error ocurred. Try again.';
            bad_result.classList.remove('d-none');
            form.querySelectorAll('input').forEach(input => {
                input.readOnly = false;
            });
        });

    });
});
</script>

@endsection