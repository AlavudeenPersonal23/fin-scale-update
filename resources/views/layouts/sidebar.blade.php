<div class="sidebar-brand d-none d-md-flex">
    <div class="sidebar-brand-full p-2" width="118" height="46" alt=" Logo">
        <a href="{{url('/dashboard')}}"><img src="{{asset('/img/logo.png')}}" alt="logo"></a>
    </div>
    <div class="sidebar-brand-narrow" width="46" height="46" alt=" Logo">
        <a href="{{url('/dashboard')}}"><img src="{{asset('/img/mobile-logo.png')}}" alt="logo"></a>
    </div>
</div>
<ul class="sidebar-nav p-3" data-coreui="navigation" data-simplebar="">
    @can('dashboard-management')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }} {{ Request::segment(1) == 'home' ? 'active' : '' }}" href="{{url('/dashboard')}}">
            <span class="dashboard-icon"></span> Dashboard</a></li>
    @endcan
    @can('shed-management-view')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'sheds' ? 'active' : '' }}" href="{{url('/sheds')}}">
            <span class="shed-management-icon"></span> Route Management</a></li>
    @endcan
    @can('farmer-management-view')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'farmers' ? 'active' : '' }}" href="{{url('/farmers')}}">
            <span class="farmer-icon"></span> Employee Management</a></li>
    @endcan
    @can('user-management-view')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'users' ? 'active' : '' }}" href="{{url('/users')}}">
            <span class="user-icon"></span> Supervisor Management</a></li>
    @endcan
    @can('vehicle-management-view')
        <!-- <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'vehicles' ? 'active' : '' }}" href="{{url('/vehicles')}}">
            <span class="vehicle-icon"></span> Vehicle Management</a></li> -->
    @endcan
    @can('waste-management-view')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'waste-types' ? 'active' : '' }}" href="{{url('/waste-types')}}">
            <span class="wastage-icon"></span> Wastage Management</a></li>
    @endcan
    @can('weighment-management-view')
        <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'weignments' ? 'active' : '' }}" href="{{url('/weignments')}}">
            <span class="weighment-icon"></span> Weighment Management</a></li>
    @endcan
    @can(['shed-abstract-report','shed-detail-report','slip-report'])
	<li class="nav-item">
		<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link {{ Request::segment(1) == 'reports' ? 'active' : '' }}">
			<span class="report-icon"></span> Reports
		</a>
		<ul class="collapse list-unstyled {{ Request::segment(1) == 'reports' ? 'show' : '' }} {{ Request::segment(1) == 'report-two' ? 'show' : '' }}" id="pageSubmenu">
			<li class="nav-item">
				<a href="{{url('/reports')}}" class="nav-link {{ Request::segment(1) == 'reports' ? 'active' : '' }}">Report 1</a>
			</li>
			<li class="nav-item">
				<a href="{{url('/report-two')}}" class="nav-link {{ Request::segment(1) == 'report-two' ? 'active' : '' }}">Report 2</a>
			</li>
		</ul>
	</li>
	{{--<li class="nav-item">
		<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link {{ Request::segment(1) == 'reports' ? 'active' : '' }}">
			<span class="report-icon"></span> Reports
		</a>
		<ul class="collapse list-unstyled {{ Request::segment(1) == 'reports' ? 'show' : '' }}" id="pageSubmenu">
			<li class="nav-item">
				<a href="{{url('/reports/shed-abstract-report')}}" class="nav-link {{ Request::segment(2) == 'shed-abstract-report' ? 'active' : '' }}">Shed Abstract Report</a>
			</li>
			<li class="nav-item">
				<a href="{{url('/reports/shed-detail-report')}}" class="nav-link {{ Request::segment(2) == 'shed-detail-report' ? 'active' : '' }}">Shed Detail Report</a>
			</li>
			<li class="nav-item">
				<a href="{{url('/reports/slip-report')}}" class="nav-link {{ Request::segment(2) == 'slip-report' ? 'active' : '' }}">Slip Report</a>
			</li>
		</ul>
	</li>--}}
    @endcan
</ul>
<div class="profile-icon-setting">
	<div class="dropdown customs-dropdown">
		<button class="btn btn-bg-dark rounded p-0 dropdown-toggle text-white d-flex  align-items-center w-100"
		  type="button" data-toggle="dropdown">
		  <span class="profile-icon pr-2"><img src="{{asset('img/login.png')}}" alt="{{substr(Auth::user()->name,0,1)}}"></span>
		  <span class="pr-5 text-truncate">{{Auth::user()->name}}</span>
		  <span class="caret" style="margin-left: 90px !important;"></span></button>
		<ul class="dropdown-menu profile-menu ml-0">
		  <li><a href="{{url('/reset-password')}}">Reset Password</a></li>
		  <li>
			<a href="{{ route('logout') }}"
				onclick="event.preventDefault();
				document.getElementById('logout-form').submit();"
			>
				Logout
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
				@csrf
			</form>
		  </li>
		</ul>
	</div>
</div>
<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
