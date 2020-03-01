@extends('layouts.panel')

@section('title', 'SweetPayments - Subscriptions')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">
                    @if(isset($admin_view)) <a href="{{ route('panel.admin.users') }}" id="go_back_btn"><i class="fas fa-backward"></i></a> Subscriptions - {{ $community->full_name }} &nbsp;
                    @else Subscriptions
                    @endif
                </h2>
                @if(!isset($admin_view))
                <button class="btn btn-primary rounded-0 mr-3 add-subscription" type="button">
                    <i class="fas fa-plus"></i> &nbsp;<span style="position:relative; bottom:1px">Create
                        Subscription</span>
                </button>
                @endif
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
            <div class="alert alert-success alert-dismissible shadow-sm">
                <i class="fas fa-check"></i>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Payment Methods</th>
                                <th scope="col">Flags</th>
                                <th scope="col">Immunity</th>
                                <th scope="col"># Sales</th>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscriptions as $subscription)
                            <tr>
                                <th scope="row" data-id="{{ $subscription->getRouteKey() }}">{{$subscription->name}}</th>
                                <td>{{ number_format($subscription->pricing, 2) }} €</td>
                                <td data-duration="{{ $subscription->duration }}">{{ $subscription->durationFormatted() }}</td>
                                <td>{{ implode(', ', array_map('ucfirst', $subscription->payment_methods)) }}</td>
                                <td>{{ implode(', ', $subscription->flags) }}</td>
                                <td>{{ $subscription->immunity }}</td>
                                <td><a href="{{ route('panel.subscriptions.sales', $subscription->getRouteKey()) }}">{{ $subscription->sales }}</a></td>
                                <td style="white-space: nowrap">
                                    <a href="#" class="edit-subscription" title="Edit Subscription"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td></td>
                                <td>No subscriptions found.
                                    <button class="btn btn-link p-0 align-baseline add-subscription">
                                        Add one!
                                    </button>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <form action="{{ route('panel.subscriptions.create') }}" method="POST" id="create-subscription-form">
                @csrf
                <input name="_method" type="hidden" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle"> Create new Subscription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary rounded-0" role="alert">
                        <strong>Subscriptions</strong> are monthly (or permanent) membership services that the players
                        of your gaming community can buy.
                        <div class="mt-2 w-100"></div>
                        {{-- Your community's shop can be found at <a
                            href="https://{{ $community->small_name }}.sweetpayments.net" target="_blank"
                            rel="noopener">{{ $community->small_name }}.sweetpayments.net</a> --}}
                    </div>
                    <div class="form-group">
                        <label for="subname">Subscription name</label>
                        <input type="text" class="form-control rounded-0" id="subname" name="name" minlength="3"
                            maxlenght="20" required autocomplete="off">
                        <small class="form-text text-muted">This is the name your users will see when making purchasing the subscription.</small>
                    </div>
                    <div class="form-group">
                        <label for="subimmunity">Immunity</label>
                        <input type="number" class="form-control rounded-0" id="subimmunity" name="immunity" min="0"
                            max="100" required autocomplete="off">
                        <small class="form-text text-muted"><a
                                href="https://wiki.alliedmods.net/Adding_Admins_(SourceMod)#Immunity" target="_blank"
                                rel="noopener">Sourcemod immunity</a> for the subscription (0-100). Put 0 for no
                            immunity (default).</small>
                    </div>
                    <div class="form-group">
                        <label for="subduration">Subscription Duration (days)</label>
                        <input type="number" class="form-control rounded-0" id="subduration" name="duration" min="0"
                            max="1095" required autocomplete="off">
                        <small class="form-text text-muted">This is how long your players will have the subscription
                            after they buy it. Place 0 for a permanent subscription.</small>
                    </div>
                    <div class="form-group">
                        <label for="subpricing">Pricing (€)</label>
                        <input type="number" class="form-control rounded-0" id="subpricing" name="pricing" min="1"
                            max="50" required autocomplete="off" step="0.01">
                        <small class="form-text text-muted">How much the subscriptions costs (in euros).</small>
                    </div>
                    <div class="form-group">
                        <p class="mb-0">Payment Methods</p>
                        <small class="form-text text-muted mb-1">Choose which payment methods your players can
                            use.</small>
                        <div class="alert alert-danger d-none" id="error-select-payment-method">
                            <i class="fas fa-exclamation-triangle"></i>
                            Please select at least one payment method.
                        </div>
                        <div class="form-check-inline p-1">
                            <span class="custom-control custom-checkbox" data-toggle="tooltip" data-placement="top"
                                data-html="true" title="<strong>Fee</strong>: 25%">
                                <input type="checkbox" id="paysafecard" name="payment_methods[]"
                                    class="custom-control-input" value="paysafecard">
                                <label class="custom-control-label" for="paysafecard">Paysafecard</label>
                            </span>
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <span class="custom-control custom-checkbox" data-toggle="tooltip" data-placement="top"
                                data-html="true" title="<strong>Fee</strong>: 10% + 0.35€">
                                <input type="checkbox" id="paypal" name="payment_methods[]" class="custom-control-input"
                                    value="paypal">
                                <label class="custom-control-label" for="paypal">Paypal
                                </label>
                            </span>
                        </div>
                        <div class="form-check">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mb-0">Assign flags</label>
                        <small class="form-text text-muted mb-1">
                            <a href="https://wiki.alliedmods.net/Adding_Admins_(SourceMod)#Levels" target="_blank"
                                rel="noopener">Sourcemod flags</a>
                            for this subscription.
                        </small>
                        <div class="alert alert-danger d-none" id="error-select-flags">
                            <i class="fas fa-exclamation-triangle"></i>
                            Please select at least one flag.
                        </div>
                        <div class="form-check mt-2 pl-1">
                            @foreach($flags as $flag => $meaning)
                            <div class="form-check-inline mr-3" data-toggle="tooltip" data-placement="top"
                                title="{{ $meaning }}">
                                <span class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="flag_{{$flag}}"
                                        name="flags[]" class="form-check-input" value="{{$flag}}">
                                    <label class="custom-control-label" for="flag_{{$flag}}">
                                        {{ $flag }}
                                    </label>
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
                        &nbsp;Close</button>
                    <button type="submit" class="btn btn-primary rounded-0">
                        <i class="fas fa-paper-plane"></i> &nbsp;Create Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: "hover"
            });
        });

        ////////////////
        let paymentMethodsCheckboxes = document.querySelectorAll('[name="payment_methods[]"]');
        let paymentMethodsErrorMsg = document.querySelector('#error-select-payment-method');
        paymentMethodsCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('click', () => {
                paymentMethodsErrorMsg.classList.add('d-none');
            });
        })

        let flagsCheckboxes = document.querySelectorAll('[name="flags[]"]');
        let flagsCheckboxesErrorMsg = document.querySelector('#error-select-flags');
        flagsCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('click', () => {
                flagsCheckboxesErrorMsg.classList.add('d-none');
            });
        })
        document.querySelector('#create-subscription-form')
            .addEventListener('submit', function(e) {
                // Payment methods - select at least 1.
                let paymentMethodsSelected = 0;
                paymentMethodsCheckboxes.forEach(checkbox=> {
                    if(checkbox.checked)
                        paymentMethodsSelected++
                });
                if(paymentMethodsSelected === 0) {
                    paymentMethodsErrorMsg.scrollIntoView();
                    paymentMethodsErrorMsg.classList.remove('d-none');
                    e.preventDefault();
                };
                // Flags - select at least 1.
                let flagsSelected = 0;
                flagsCheckboxes.forEach(checkbox=> {
                    if(checkbox.checked)
                    flagsSelected++
                });
                if(flagsSelected === 0) {
                    flagsCheckboxesErrorMsg.scrollIntoView();
                    flagsCheckboxesErrorMsg.classList.remove('d-none');
                    e.preventDefault();
                };
            })
        //////////////////////////////
        let modal = $('#modal');
        let form = modal.find('form')[0];
        let submit_button = modal.find('[type="submit"]')[0];
        let method = modal.find('[name="_method"]')[0];
        let alert = modal.find('.alert')[0];

        modal.on('shown.bs.modal', function () {
            modal.find(".modal-body").scrollTop(0);
        });
        document.querySelectorAll('.add-subscription').forEach(element => {
            element.addEventListener('click', function(e) {
                console.log("wow");
                modal.modal('show');
                modal.find('.modal-title').text('Create new Subscription');
                    modal.find('.modal-body')[0].scrollIntoView();
                //
                form.action = '{{ route('panel.subscriptions.create') }}';
                form.reset();
                //
                submit_button.innerHTML = '<i class="fas fa-paper-plane"></i>&nbsp; Create Subscription';
                //
                alert.classList.remove('d-none');
                //
                flagsCheckboxesErrorMsg.classList.add('d-none');
                paymentMethodsErrorMsg.classList.add('d-none');
                //
                method.value = "POST";
                //
                e.preventDefault();
            });
        });
        let editButtons = document.querySelectorAll('.edit-subscription');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                modal.modal('show');
                modal.find('.modal-title').text('Edit Subscription');
                modal.find('.modal-body')[0].scrollIntoView();
                //
                //
                form.action = '{{ route('panel.subscriptions.create') }}/' + button.parentElement.parentElement.children[0].dataset.id;
                form.reset();
                //
                let name = button.parentElement.parentElement.children[0].textContent;
                let immunity = button.parentElement.parentElement.children[5].textContent;
                let duration = button.parentElement.parentElement.children[2].dataset.duration;
                let pricing = button.parentElement.parentElement.children[1].textContent;
                let paymentMethods = button.parentElement.parentElement.children[3].textContent;
                paymentMethods = paymentMethods.replace(/,/g, '').toLowerCase().split(' ');
                let flags = button.parentElement.parentElement.children[4].textContent;
                flags = flags.replace(/,/g, '').toLowerCase().split(' ');

                form.querySelector('[name=name]').value = name;
                form.querySelector('[name=immunity]').value = immunity;
                form.querySelector('[name=duration]').value = duration;
                form.querySelector('[name=pricing]').value = Number(pricing.split(' ')[0]);

                form.querySelectorAll('[name="payment_methods[]"]').forEach(checkbox => {
                    checkbox.checked = paymentMethods.includes(checkbox.value);
                })

                form.querySelectorAll('[name="flags[]"]').forEach(checkbox => {
                    checkbox.checked = flags.includes(checkbox.value);
                })
                //
                submit_button.innerHTML = '<i class="fas fa-pen"></i>&nbsp; Edit Subscription';
                //
                alert.classList.add('d-none');
                //
                flagsCheckboxesErrorMsg.classList.add('d-none');
                paymentMethodsErrorMsg.classList.add('d-none');
                //
                method.value = "PATCH";
                //
                e.preventDefault();
            })
        });
    });
</script>

@endsection