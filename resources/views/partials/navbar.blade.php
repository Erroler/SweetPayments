<nav class="navbar navbar-expand-md navbar-light navbar-dark p-0 pl-2 pr-3">
  <div>
    <a class="sidebar-toggle mr-3" href="#">
      <img class="dashboard-logo" src="{{ asset('images/logo.png') }}">
  </div>
  <div class="navbar-nav ml-auto mr-md-4 pt-2 pb-1">
    <span class="d-flex mb-1">
      <a href="{{ \Auth::user()->getProfileLink() }}" target="_blank" rel="noopener">
        <img class="avatar rounded"
          src="{{ Auth::user()->avatar }}">
      </a>
      <div class="d-flex flex-column pt-1 px-2 pb-1">
        <a href="{{ \Auth::user()->getProfileLink() }}" class="text-primary font-weight-bold text-decoration-none" target="_blank" rel="noopener">
          {{ \Auth::user()->username }}
        </a>
        <span class="small text-light">Balance: <span class="badge badge-secondary rounded-0 font-weight-normal" style="font-size: 90%">{{ number_format(Auth::user()->balance, 2) }}€</span></span>
      </div>
    </span>
  </div>
</nav>