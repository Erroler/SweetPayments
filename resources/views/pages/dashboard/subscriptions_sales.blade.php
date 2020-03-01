@extends('layouts.panel')

@section('title', 'SweetPayments - '.$subscription->name)

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
                <h2 class="pb-2"><a href="{{ route('panel.subscriptions') }}">Subscriptions</a> / {{ $subscription->name }} / Sales</h2>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <table id="table-sales" class="table display">
                        <thead>
                            <tr>
                                <th>Purchase Date</th>
                                <th>Expires on</th>
                                <th>Player Name <span class="text-secondary" data-toggle="tooltip" data-placement="top" title="This column only reflects the player names at the time of the purchase. It is very possibly outdated.">(?)</span></th>
                                <th>SteamID64</th>
                                <th>Payment Method</th>
                                <th>Revenue</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            $(document).ready( function () {
                let now = new Date();
                $('#table-sales').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('panel.sales.datatables', $subscription->getRouteKey()) }}',
                        type: 'GET',
                        data: function (d) {
                            //;
                        }
                    },
                    "language": {
                        emptyTable: "No sales found."
                    },
                    colReorder: false,
                    ordering: false,
                    searching: false,
                    columns: [
                        { data: 'date', name: 'date' },
                        { 
                            data: 'expires_on', 
                            name: 'expires_on',
                            render: function(data, type, full, meta) {
                                if(type == "display"){
                                    let expire_date = new Date(full['expires_on_non_format']);
                                    if(expire_date < now)
                                        return `<span class="text-danger" title="Expired">${data}</span>`;
                                    //return timestamp;
                                    //return `<a href="http://steamcommunity.com/profiles/${full.steamid64}" rel="noopener" target="_blank">${data}</a>`;
                                }

                                return data;
                            }
                        },
                        { 
                            data: 'player_name', 
                            name: 'player_name',
                            render: function(data, type, full, meta){
                                if(type == "display"){
                                    return `<a href="http://steamcommunity.com/profiles/${full.steamid64}" rel="noopener" target="_blank">${data}</a>`;
                                }

                                return data;
                            }
                        },
                        { 
                            data: 'steamid64', 
                            name: 'steamid64',
                            render: function(data, type, full, meta){
                                if(type == "display"){
                                }

                                return data;
                            }
                        },
                        { 
                            data: 'payment_method', 
                            name: 'payment_method',
                            render: function(data, type, full, meta){
                                if(type == "display"){
                                    return data.charAt(0).toUpperCase() + data.slice(1);
                                }

                                return data;
                            }
                        },
                        { 
                            data: 'revenue_after_tax', 
                            name: 'revenue',
                            render: function(data, type, full, meta){
                                if(type == "display"){
                                    return `${Number(data).toFixed(2)} €`;
                                }

                                return data;
                            }
                        },
                        {
                            name: 'action_buttons',
                            render: function(data, type, full, meta){
                                if(type == "display"){
                                    let id = full['id'];
                                    return '<a href="{{ route('panel.sales') }}/' + id + '" title="View details"><i class="fas fa-file-invoice-dollar fa-lg"></i></a>';
                                }

                                return data;
                            }
                        }
                    ]
                });
            } );
        })
    });
</script>

@endsection