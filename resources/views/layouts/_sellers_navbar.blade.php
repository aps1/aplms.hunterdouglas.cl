<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header text-center">
                <img src="{{asset('img/logo-hunter.png')}}" class="w-100" />
                <div class="dropdown profile-element">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ Auth::user()->name }} <b class="caret"></b> </span>
                        <span class="text-muted text-xs block">{{ Auth::user()->type_description }}</span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#373737">
                                <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-edit-account" style="color:#373737">
                                <i class="fa fa-user"></i> Editar datos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    DM
                </div>
            </li>
            <li class="{{ Route::currentRouteName() == 'seller.dashboard' ? 'active' : ''}}">
                <a href="{{route('seller.dashboard')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <li class="{{ Route::currentRouteName() == 'seller.leads' ? 'active' : ''}}">
                <a href="{{route('seller.leads')}}"><i class="fa fa-envelope-open"></i> <span class="nav-label">@lang('Prospectos')</span></a>
            </li>
            <li class="{{ Route::currentRouteName() == 'contacts' ? 'active' : ''}}">
                <a href="{{route('contacts')}}"><i class="fa fa-address-book"></i> <span class="nav-label">@lang('Contactos')</span></a>
            </li>
        </ul>

    </div>
</nav>
@include('layouts._modal_edit_account')