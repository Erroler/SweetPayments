@extends('layouts.panel')

@section('title', 'SweetPayments - Authenticate')

@section('content')
<div class="container-fluid h-100">
  <div class="row no-gutter">
    <div class="col-12">
      <div class="auth d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-6 mx-auto">
              <div class="card px-3 py-2">
                <img src="{{ asset('images/logo.png') }}" alt="lactogal logo"
                  class="img-fluid mx-auto pr-md-4 d-block">
                @if(!isset($error))
                  <a class="text-white btn btn-block btn-primary d-block text-uppercase font-weight-bold" href="{{ route('auth.steam') }}">
                  <i class="fab fa-steam font-weight-normal"></i> Sign In trough Steam
                </a>
                @else
                <div class="alert alert-danger rounded-0 font-weight-bold">{{ $error }}</div>
                @endif
                <a class="text-white btn btn-block btn-dark d-block text-uppercase font-weight-bold mb-2" href="{{ route('landing_page') }}">
                  <i class="fas fa-angle-double-left"></i> Go back
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection