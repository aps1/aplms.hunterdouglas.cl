<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header text-center">
                <div class="dropdown profile-element">
                    <img src="{{asset('img/logo-hunter.png')}}" class="w-100" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ Auth::user()->name }} <b class="caret"></b> </span>
                        <span class="text-muted text-xs block">{{ Auth::user()->type_description }} </span>
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
                    <img src="{{asset('img/isotipo_hunter.png')}}" class="w-50" />
                </div>
            </li>
            <li class="{{ Route::currentRouteName() == 'admin.leads' ? 'active' : ''}}">
                <a href="{{route('admin.leads')}}"><i class="fa fa-address-card"></i> <span class="nav-label">@lang('Prospectos')</span></a>
            </li>
            <li class="{{ Route::currentRouteName() == 'contacts' ? 'active' : ''}}">
                <a href="{{route('contacts')}}"><i class="fa fa-address-book"></i> <span class="nav-label">@lang('Contactos')</span></a>
            </li>
            <li class="{{ Route::currentRouteName() == 'admin.dealerships' ? 'active' : ''}}">
                <a href="{{route('admin.dealerships')}}"><i class="fa fa-map"></i> <span class="nav-label">Distribuidores</span></a>
            </li>
            <li class="{{ Route::currentRouteName() == 'users.index' ? 'active' : ''}}">
                <a href="{{route('users.index')}}"><i class="fa fa-users"></i> <span class="nav-label">Usuarios</span></a>
            </li>
            @can('manageKams')
                <li class="{{ Route::currentRouteName() == 'admin.kams' ? 'active' : ''}}">
                    <a href="{{route('admin.kams')}}"><i class="fa fa-bookmark"></i> <span class="nav-label">KAM</span></a>
                </li>
            @endcan
            @can('manageAdmins')
                <li class="{{ Route::currentRouteName() == 'admin.admins' ? 'active' : ''}}">
                    <a href="{{route('admin.admins')}}"><i class="fa fa-certificate"></i> <span class="nav-label">Administradores</span></a>
                </li>
            @endcan
        </ul>

    </div>
</nav>
@include('layouts._modal_edit_account')