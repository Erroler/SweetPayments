<div class="sidebar sidebar-dark bg-dark">
  <ul class="list-unstyled list-admin mb-0">
    <li class="@if(\Request::is('dashboard*')) active @endif">
      <a href="{{ route('panel.dashboard')}}"><i class="fas fa-home fa-fw mr-3"></i>Dashboard</a>
    </li>
    <li class="@if(\Request::is('servers*')) active @endif">
      <a href="{{ route('panel.servers') }}"><i class="fas fa-fighter-jet fa-fw mr-3"></i>Servers</a>
    </li>
    <li class="@if(\Request::is('subscriptions*')) active @endif">
      <a href="{{ route('panel.subscriptions') }}"><i class="fas fa-users-cog fa-fw mr-3"></i>Subscriptions</a>
    </li>
    <li class="@if(\Request::is('sales*')) active @endif">
      <a href="{{ route('panel.sales') }}"><i class="fas fa-shopping-cart fa-fw mr-3"></i>Sales</a>
    </li>
    <li class="@if(\Request::is('withdrawls*')) active @endif">
      <a href="{{ route('panel.withdrawls') }}"><i class="fas fa-hand-holding-usd fa-fw mr-3"></i>Withdrawls</a>
    </li>
    <li class="@if(\Request::is('settings*')) active @endif">
      <a href="{{ route('panel.settings') }}"><i class="fas fa-user-cog fa-fw mr-3"></i>Settings</a>
    </li>
    <hr>
    {{-- <li class="@if(\Request::is('support*')) active @endif">
      <a href="{{ route('panel.support') }}"><i class="fas fa-question-circle fa-fw mr-3"></i>Support</a>
    </li> --}}
    @if(\Auth::user()->isAdmin())
      <li class="@if(\Request::is('*users*') || \Request::is('*community*')) active @endif admin-li">
        <a href="{{ route('panel.admin.users') }}"><i class="fas fa-users fa-fw mr-3"></i>Users</a>
      </li>
    @endif
    <li>
      <a href="{{ route('auth.logout') }}"><i class="fas fa-sign-out-alt fa-fw mr-3"></i>Logout</a>
    </li>
  </ul>
</div>