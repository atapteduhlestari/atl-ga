<div class="preloader">
    <div class="loading">
        <div class="spinner-border text-white" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-dark bg-header topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link btn-light d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    {{-- <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-light" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form> --}}

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">{{ $alerts['count'] > 0 ? $alerts['count'] : '' }}</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                @if (count($alerts['events']) > 0)
                    <h6 class="dropdown-header bg-danger border-0">
                        Assets need to Maintain / Renew
                    </h6>
                    @foreach ($alerts['events'] as $alert => $v)
                        <a class="dropdown-item d-flex align-items-center" href="{{ $v['link'] }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation text-white"></i>
                                </div>
                            </div>
                            <div>
                                <table class="table table-sm text-xs table-borderless">
                                    <tr>
                                        <td class="text-danger">
                                            {{ createDate($v['date'])->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $v['asset_name'] }} |
                                            <span class="text-gray-500">
                                                {{ $v['type'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $v['name'] }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </a>
                    @endforeach
                @else
                    <h6 class="dropdown-header bg-primary border-0">
                        No Reminder <i class="fas fa-thumbs-up"></i>
                    </h6>
                @endif
                <a href="/timeline" class="dropdown-item text-center small text-gray-500">Show Timelines</a>
            </div>
        </li>
        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline small">
                    {{ auth()->user()->name }}
                </span>
                <img class="img-profile rounded-circle" src="/assets/template/img/undraw_male_avatar_323b.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @can('superadmin')
                    <a class="dropdown-item" href="/user">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        User Management
                    </a>
                @endcan
                <a class="dropdown-item" href="/change-password">
                    <i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.preloader').fadeOut(500)
        });
    </script>
@endpush
