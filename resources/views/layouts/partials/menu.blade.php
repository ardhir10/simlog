

<div class="vertical-menu" style="background: #0BB97A">

    <!-- LOGO -->
    <div class="navbar-brand-box" style="   box-shadow: -2px 6px 3px rgb(52 58 64 / 8%);">
        <a href="{{route('dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('assets/images/simlog-icon.png')}}" alt="" height="35">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/simlog-icon.png')}}" alt="" height="35">
            </span>

        </a>

        {{-- <a href="index.html" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{asset('assets/')}}/images/logo-light.png" alt="" height="22">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/')}}/images/logo-sm-light.png" alt="" height="22">
        </span>
        </a> --}}
    </div>



    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">


                {{-- ALL USER --}}
                <li>
                    <a href="{{route('dashboard')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">Dashboard</span>
                    </a>
                </li>

                @if ((Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
                (Auth::user()->role->name ?? null) == 'Bendahara Materil' ||
                (Auth::user()->role->name ?? null) == 'Superadmin' ||
                (Auth::user()->role->name ?? null) == 'Pengelola Gudang'
                )
                    <li>
                        <a href="{{route('barang-persediaan.index')}}">
                            <i class="icon nav-icon" data-feather="trello"></i>
                            <span class="menu-item" data-key="t-sales">Barang Persediaan</span>
                        </a>
                    </li>

                @endif


                {{-- ALL USER --}}
                <li>
                    <a href="{{route('permintaan-barang.index')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">Permintaan barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('rencana-kebutuhan.index')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">Usulan Kebutuhan</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('rencana-kebutuhan-tahunan.index')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">R. Kebutuhan Tahunan</span>
                    </a>
                </li>

                {{-- ROLE TYPE MAP
                1 = A (User Approval) staff:staff
                2 = P  (User Peminta) nakhoda:nakhoda
                3 = A,P admin:admin


                --}}
                @if ((Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
                    (Auth::user()->role->name ?? null) == 'Bendahara Materil' ||
                    (Auth::user()->role->name ?? null) == 'Pengelola Gudang' ||
                    (Auth::user()->role->name ?? null) == 'Superadmin' ||
                    (Auth::user()->role->type ?? null) == 1 ||
                    (Auth::user()->role->type ?? null) == 3
                )
                    <li>
                        <a href="{{route('approval.index')}}">
                            <i class="icon nav-icon" data-feather="trello"></i>
                            <span class="menu-item" data-key="t-sales">Approval</span>
                        </a>
                    </li>
                @endif

                {{-- ALL USER --}}
                @if (
                    (Auth::user()->role->type ?? null) == 1 ||
                    (Auth::user()->role->type ?? null) == 3
                    )
                    <li>
                        <a href="{{route('rab.index')}}">
                            <i class="icon nav-icon" data-feather="trello"></i>
                            <span class="menu-item" data-key="t-sales">Rencana Angg. Biaya</span>
                        </a>
                    </li>
                @endif

                @if (
                    (Auth::user()->role->type ?? null) == 2 ||
                    (Auth::user()->role->name ?? null) == 'Kurir/Offsetter' ||
                    (Auth::user()->role->name ?? null) == 'Bendahara Materil' ||
                    (Auth::user()->role->name ?? null) == 'Pengelola Gudang'
                    )
                    <li>
                        <a href="{{route('retur-barang.index')}}">
                            <i class="icon nav-icon" data-feather="trello"></i>
                            <span class="menu-item" data-key="t-sales">Retur Barang</span>
                        </a>
                    </li>
                @endif


                @if ((Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
                    (Auth::user()->role->name ?? null) == 'Bendahara Materil' ||
                    (Auth::user()->role->name ?? null) == 'Pengelola Gudang' ||
                    (Auth::user()->role->name ?? null) == 'Superadmin' ||

                    (Auth::user()->role->type ?? null) == 1
                )
                <li>
                    <a href="{{route('laporan.index')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">Lap. Stock Opname</span>
                    </a>
                </li>
                @endif


                 @if ((Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
                    (Auth::user()->role->name ?? null) == 'Superadmin'
                 )


                <li>
                    <a href="{{route('master-data.index')}}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-sales">Master Data</span>
                    </a>
                </li>
                @endif


                {{-- ALL USER --}}
                <li>
                    <a href="{{route('user.setting')}}">
                        <i class="icon nav-icon" data-feather="user"></i>
                        <span class="menu-item" data-key="t-sales">User Setting</span>
                    </a>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
