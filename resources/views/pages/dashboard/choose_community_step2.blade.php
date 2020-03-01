@extends('layouts.panel')

@section('title', 'SweetPayments - Register Community')

@section('content')
<div class="container-fluid h-100">
  <div class="row no-gutter">
    <div class="col-12">
      <div class="auth d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-7 mx-auto">
              <div class="card">
                {{-- <h5 class="card-header font-weight-bold text-primary">
                  <div class="float-left">Choose your gaming community</div>
                    <img src="{{ asset('images/logo.png') }}" alt="logo"
                    class="float-right img-fluid d-block w-25">
                </h5> --}}
                <div class="card-body">
                  <img src="{{ asset('images/logo.png') }}" alt="logo"
                  class="img-fluid mx-auto pr-md-4 d-block">
                  <div class="alert alert-primary rounded-0" role="alert">
                    <span class="">Confirm the details below about your community.
                  </div>
                  <form action="{{ route('community.select.step2') }}" method="POST">
                    @csrf
                    <a href="{{ 'https://steamcommunity.com/groups/'.$community['small_name'] }}" target="_blank">
                      <img class="img-fluid d-block mx-auto border border-secondary rounded w-25 mb-2 mt-3" src="{{ $community['avatar'] }}">
                    </a>
                    <div class="form-group">
                      <label>Group URL</label>
                      <input type="text" class="form-control" value="{{ 'https://steamcommunity.com/groups/'.$community['small_name'] }}" disabled>
                    </div>
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" class="form-control" value="{{ $community['full_name'] }}" disabled>
                    </div>
                    <div class="form-group">
                      <label>Member Count</label>
                      <input type="text" class="form-control" value="{{ $community['members'] }}" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-0 ml-auto float-right px-4"><i class="fas fa-paper-plane"></i> Register Community</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection