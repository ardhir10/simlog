<header id="page-topbar">
        <div class="navbar-header">
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn-custom">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            <div class="d-flex">
                <!-- LOGOs -->
                <div class="navbar-brand-box">
                    ATONREP
                </div>



            </div>

            <div class="d-flex">

                <div class="dropdown d-inline-block">
                    <button type="button" class="d-none btn header-item noti-icon right-bar-toggle" id="right-bar-toggle">
                        <i class="icon-sm" data-feather="settings"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        @if (Auth::user()->fs_avatar)
                            <img class="rounded-circle header-profile-user" src="{{asset('images/avatar/'.Auth::user()->fs_avatar)}}" alt="Header Avatar">
                        @else
                        <img class="rounded-circle header-profile-user" src="{{asset('assets/images/icon/profile.png')}}" alt="Header Avatar">
                        @endif
                        <span class="ms-2 d-none d-sm-block user-item-desc">
                            <span class="user-name">{{Auth::user()->name  ?? ''}}</span>
                            <span class="user-sub-title">{{Auth::user()->role->name  ?? ''}}
                                @if (Auth::user()->role->type == 1)
                                (A)
                                @elseif (Auth::user()->role->type == 2)
                                (P)
                                @else
                                (A,P)
                                @endif
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                        <div class="p-3 bg-primary border-bottom">
                            <h6 class="mb-0 text-white">{{Auth::user()->name ?? ''}}</h6>
                            <h6 class="mb-0 text-white"></h6>
                            <p class="mb-0 font-size-11 text-white-50 fw-semibold">{{Auth::user()->email ?? ''}}</p>
                        </div>

                        {{-- <a class="dropdown-item" href=""><i class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Edit Profile</span></a> --}}
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('logout')}}"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
</header>
