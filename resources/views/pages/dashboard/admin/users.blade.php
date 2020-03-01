@extends('layouts.panel')

@section('title', 'SweetPayments - Servers')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">Users</h2>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Community</th>
                                <th scope="col">User</th>
                                <th scope="col">Balance</th>
                                <th scope="col">Servers</th>
                                <th scope="col">Revenue (30 days)</th>
                                <th scope="col">Sales (30 days)</th>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><a href="https://steamcommunity.com/gid/{{ $user->community->group_id }}"
                                        class="text-decoration-none" target="_blank" rel="noopener"><img style="position: relative;
                                        bottom: 2px;" height="24" width="24" src="{{ $user->community->avatar }}"
                                            class="rounded img-fluid"> {{ $user->community->full_name }}</a></td>
                                <td> <a href="{{ \Auth::user()->getProfileLink() }}" class="text-decoration-none"
                                        target="_blank" rel="noopener">
                                        {{ $user->username }}</a></td>
                                <td>{{ number_format($user->balance, 2) }} €</td>
                                <td>{{ $user->community->servers->count() }}</td>
                                <td>{{ number_format($user->getLast30DaysInfo()->revenue, 2) }} €</td>
                                <td>{{ $user->getLast30DaysInfo()->number_sales }}</td>
                                <td><a href="{{ route('panel.admin.servers', $user->community) }}">Servers</a></td>
                                <td><a href="{{ route('panel.admin.subscriptions', $user->community) }}">Subscriptions</a></td>
                                <td><a rel="noopener" href="https://{{ $user->community['small_name'] }}.sweetpayments.net" target="_blank">WebShop</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection