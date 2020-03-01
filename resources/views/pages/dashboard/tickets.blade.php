@extends('layouts.panel')

@section('title', 'SweetPayments - Dashboard')

@section('content')
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <div class="content p-3 p-lg-4">
        {{-- <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="pb-2">Support Tickets (NOT FINISHED YET)</h2>
                <button class="btn btn-primary rounded-0 mr-3">
                    <i class="fas fa-envelope-open-text"></i> &nbsp;<span style="position:relative; bottom:1px">Contact Support</span>
                </button>
            </div>
            <div class="card shadow-sm rounded-0">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Ticket</th>
                                <th scope="col">Status</th>
                                <th scope="col">Last Updated</th>
                                <th scope="col">Creation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                    <td><a href="{{ route('panel.support.show', $ticket->id) }}">{{ $ticket->title }}</a></td>
                                        <td>
                                            @if($ticket->status === 'closed')
                                                <span class="text-danger">Closed</span>
                                            @elseif($ticket->status === 'awaiting_client_response')
                                                <span class="text-warning">Awaiting Your Response</span>
                                            @else
                                                <span class="text-success">Open</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->updated_ago() }}</td>
                                        <td>{{ $ticket->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>No support tickets to display.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex flex-row-reverse pr-3">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div> --}}
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