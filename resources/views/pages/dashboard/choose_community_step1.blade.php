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
                    <span class="font-weight-bold">Almost done!</span> We just need to ask you some details about your gaming community.
                  </div>
                  <form action="{{ route('community.select.step1') }}" method="POST">
                    @csrf
                    <div class="form-group">
                      <label for="exampleInputEmail1">Steam Community Group URL</label>
                      @error('community_url')
                       <div class="mt-n2 mb-2">
                          <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</small>
                        </div>
                      @enderror
                      <input type="text" class="form-control @if($errors->has('community_url')) is-invalid @endif" 
                        placeholder="E.g. https://steamcommunity.com/groups/Valve" name="community_url" value="{{old('community_url')}}"
                        autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                      <small id="emailHelp" class="form-text text-muted">You must be an administrator of the steam group.</small>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-0 ml-auto float-right px-4"><i class="fas fa-paper-plane"></i> Submit</button>
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