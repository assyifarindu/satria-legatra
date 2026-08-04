<div class="navbar-custom" style="background-color: #AF1B3F;">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">
            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    @if (countNotification(Auth::user()->id) > 0)
                        <span class="badge bg-danger rounded-circle noti-icon-badge">{{ countNotification(Auth::user()->id) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            </span>Notification
                        </h5>
                    </div>

                    <div class="noti-scroll" data-simplebar>

                        <!-- item-->
                        @foreach (getNotification(Auth::user()->id) as $item)
                            <a href="{{ route($item->url, Hashids::encode($item->id_feature)) }}" class="dropdown-item notify-item">
                                <div class="notify-icon bg-primary">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details">{{ $item->feature }}
                                    <small class="text-muted">{{ formatDate($item->created_at) }}</small>
                                </p>
                            </a>
                        @endforeach
                    </div>

                    <!-- All-->
                    <a href="{{ route('notification.index') }}" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all
                        <i class="fe-arrow-right"></i>
                    </a>

                </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="@if (Auth::user()->photo != '') {{ '/satria/public/profile/' . Auth::user()->photo }} @else {{ asset('assets/images/man_asset.png') }} @endif" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ms-1">
                        {{ ucwords(Auth::user()->name); }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('profile') }}" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('welcome') }}" class="dropdown-item notify-item">
                        <i class="fe-align-justify"></i>
                        <span>Go To Web Portal Satria</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="return false" id="btn-logout" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="{{ route('home') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" height="35">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="35">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>

            <a href="{{ route('home') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" height="35">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
