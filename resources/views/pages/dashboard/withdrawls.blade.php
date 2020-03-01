@extends('layouts.panel')

@section('title', 'SweetPayments - Dashboard')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <h2 class="pb-2">Withdraw Money</h2>
            <div class="alert alert-primary shadow-sm">
                Your current account balance is <strong>{{ number_format(Auth::user()->balance, 2) }}€</strong>.
            </div>
            @if($last_action !== NULL && $last_action->action === 'WITHDRAWL_REQUEST')
            <div class="alert alert-warning shadow-sm">
                Pending approval for withdrawl of <strong>{{ number_format($last_action->value, 2) }}€</strong>
                requested in {{ $last_action->date_formated }}.
            </div>
            @elseif($last_action !== NULL && $last_action->action === 'WITHDRAWL_APPROVAL')
            <div class="alert alert-success shadow-sm">
                Withdrawl of <strong>{{ number_format($last_action->value, 2) }}€</strong> has been approved in
                {{ $last_action->date_formated }}. Please wait 1-4 working days to receive the money.
            </div>
            @endif
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



            <div class="card shadow-sm rounded-0 px-4 py-3">
                @if($cannot_withdraw_reason)

                    @if ($cannot_withdraw_reason === 'pending_request')
                        <h5 class="mb-1">Cannot request a new withdrawl until the previous one is completed.</h5>
                    @elseif ($cannot_withdraw_reason === 'request_too_soon')
                        <h5 class="mb-1">Cannot request a new withdrawl until 10 days have passed since the last one.</h5>
                    @elseif ($cannot_withdraw_reason === 'insufficient_balance')
                        <h5 class="mb-1">Insufficient balance to request a withdrawl.</h5>
                        <small class="text-muted mb-2">The minimum withdrawl amount is 10€.</small>
                    @endif

                @else
                <h5 class="mb-1">Method of Payment</h5>
                <small class="text-muted mb-2">Select the payment method for the withdrawl.</small>
                <div>
                    <div class="btn-group mt-1" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-secondary collapse-button" data-collapse="#paypal"><i
                                class="fab fa-paypal"></i>&nbsp;Paypal</button>
                        <button type="button" class="btn btn-secondary pl-0 pr-0">
                            <span class="btn-separator"></span>
                        </button>
                        <button type="button" class="btn btn-secondary collapse-button"
                            data-collapse="#bank_transfer"><i class="fas fa-money-check"></i>&nbsp;Bank
                            Transfer</button>
                    </div>
                </div>
                <div class="mt-3">
                    <div id="paypal" class="collapse-section d-none">
                        <div class="alert alert-secondary">
                            Please mind that Paypal may charge extra fees. The withdrawl can take up to 24 hours.
                        </div>
                        <form action="{{ route('panel.withdrawls.paypal') }}" class="request_withdrawl_form" method="POST" autocomplete="off">                    
                            @csrf
                            <div class="form-group">
                                <label for="amount">Amount (€)</label>
                                <input type="number" class="form-control rounded-0" id="amount" name="amount" min="10"
                                    max="{{ Auth::user()->balance }}" required autocomplete="off" step="0.01">
                                <small class="form-text text-muted">How much money you want to withdraw. Minimum amount
                                    is 10€.</small>
                            </div>
                            <div class="form-group">
                                <label for="paypal_address">Paypal email address</label>
                                <input type="email" class="form-control rounded-0" id="paypal_address"
                                    name="paypal_address" autocomplete="off" required>
                                <small class="form-text text-muted">The email address of the paypal account.</small>
                            </div>
                            <div class="form-group">
                                <label for="paypal_address_confirmation">Confirm Paypal email address</label>
                                <input type="email" class="form-control rounded-0" id="paypal_address_confirmation"
                                    name="paypal_address_confirmation"  autocomplete="off" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fab fa-paypal"></i>&nbsp;Request
                                Withdraw</button>
                        </form>
                    </div>

                    <div id="bank_transfer" class="collapse-section d-none">
                        {{-- <div class="alert alert-secondary">
                            Bank transfers can take up to 4 working days to process and to be credited to your bank
                            account. Saturday and Sunday do not count as working days.
                        </div> --}}
                        <div class="alert alert-danger">
                            Not yet available! Use Paypal for now.
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <h2 class="pb-2 mt-4">Account History</h2>
            <div class="card shadow-sm rounded-0">
                <div class="card-body pb-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->date_formated(null, true) }}</td>
                                <td>{{ $log->action }}</td>
                                <td>
                                    @if($log->action === 'WITHDRAWL_APPROVAL')
                                    Your withdrawl of {{ number_format($log->value, 2) }}€ was approved.
                                    @elseif($log->action === 'WITHDRAWL_REQUEST')
                                    Requested withdrawl of {{ number_format($log->value, 2) }}€.
                                    @endif
                                </td>
                                <td>{{ ucfirst($log->payment_method) }}</td>
                            </tr>
                            @empty
                            <td>Nothing to display.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex flex-row-reverse pr-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('load', function() {
        let collapse_buttons = document.querySelectorAll('.collapse-button');
        collapse_buttons.forEach(button => {
            button.addEventListener('click', function() {
                let target = document.querySelector(this.dataset.collapse);
                let should_hide_self = target.classList.contains('d-none');
                document.querySelectorAll('.collapse-section').forEach(section => {
                    section.classList.add('d-none');
                    let form = section.querySelector('form')
                    if(form) form.reset();
                });
                if(should_hide_self)
                    target.classList.remove('d-none');
            });
        });
    });
</script>

@endsection