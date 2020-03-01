@extends('layouts.panel')

@section('title', 'SweetPayments - Servers')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">
                    @if(isset($admin_view)) <a href="{{ route('panel.admin.users') }}" id="go_back_btn"><i class="fas fa-backward"></i></a> Servers - {{ $community->full_name }} &nbsp;
                    @else Servers
                    @endif
                </h2>
                @if(!isset($admin_view))
                <button class="btn btn-primary rounded-0 mr-3" type="button" data-toggle="modal"
                    data-target="#addServerModal">
                    <i class="fas fa-plus"></i> &nbsp;<span style="position:relative; bottom:1px">Add Server</span>
                </button>
                @endif
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                                <th scope="col">Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Map</th>
                                <th scope="col">Players</th>
                                <th scope="col" style="width:1px; white-space: nowrap;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servers as $server)
                            <tr>
                                <td>
                                    <img class="server-icon" src="{{ asset('images/server.png') }}">
                                </td>
                                <th scope="row">{{$server->name}}</th>
                                <td>{{$server->address}}</td>
                                <td>{{$server->map}}</td>
                                <td>{{$server->players}}</td>
                                <td>
                                    <a href="steam://connect/{{$server->address}}" class="badge badge-success">Join
                                        Server</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td></td>
                                <td>No servers found.
                                    <button class="btn btn-link p-0 align-baseline" type="button" data-toggle="modal"
                                        data-target="#addServerModal">
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
                    @if($servers->isNotEmpty() || config('app.debug'))
                    <small class="text-muted">Servers that have been offline for longer than 72 hours will not be
                        shown.</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addServerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Server</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                In order to add a new server you only need to install our plugin. <br>It will magically be added to your
                account!
                <div class="text-muted small mt-2">The plugin requires <a href="https://www.sourcemod.net/"
                        rel="noopener">Sourcemod</a> in order to run.</div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('panel.servers.plugin') }}" class="btn btn-primary rounded-0" data-toggle="tooltip"
                    data-placement="top" data-html="true"
                    title="Inside the zip file you will find a pre-built <em>.cfg</em> file custom made for your servers. Do not share it with anyone!">
                    <i class="fas fa-download"></i> &nbsp;Download Plugin
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    });
</script>

@endsection