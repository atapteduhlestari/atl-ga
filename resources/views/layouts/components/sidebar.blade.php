        <!-- Sidebar -->
        <ul class="navbar-nav bg-page sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    General Affair
                </div>
            </a>

            <!-- Sidebar Message -->
            <div class="d-none d-lg-flex mx-auto my-3">
                <img height="100" class="mb-2" src="/assets/template/img/undraw_team_collaboration_re_ow29.svg"
                    alt="...">
            </div>
            {{-- <div class="sidebar-card d-none d-lg-flex">
                <img height="50" class="mb-2" src="/assets/app/images/logo/logo.png" alt="...">
            </div> --}}
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('/') || request()->is('/home') ? 'active' : '' }}">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Functional
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li
                class="nav-item tes {{ request()->is('asset-group*') || request()->is('asset-parent*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->is('asset-group*') || request()->is('asset-parent*') ? '' : 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapseAsset" aria-expanded="true"
                    aria-controls="collapseAsset">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Assets</span>
                </a>
                <div id="collapseAsset"
                    class="collapse  {{ request()->is('asset-group*') || request()->is('asset-parent*') ? 'show' : '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item  {{ request()->is('asset-parent*') ? 'active' : '' }}"
                            href="/asset-parent">Assets</a>
                        <a class="collapse-item  {{ request()->is('asset-group*') ? 'active' : '' }}"
                            href="/asset-group">Asset's Group</a>
                    </div>
                </div>
            </li>
            <li
                class="nav-item {{ request()->is('storage*') ||request()->is('renewal*') ||request()->is('maintenance*') ||request()->is('cycle*')? 'active': '' }}">
                <a class="nav-link {{ request()->is('storage*') ||request()->is('renewal*') ||request()->is('maintenance*') ||request()->is('cycle*')? '': 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapseData" aria-expanded="true"
                    aria-controls="collapseData">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Data</span>
                </a>
                <div id="collapseData"
                    class="collapse {{ request()->is('storage*') ||request()->is('renewal*') ||request()->is('maintenance*') ||request()->is('cycle*')? 'show': '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('storage*') ? 'active' : '' }}"
                            href="/storage">Storage</a>
                        <a class="collapse-item {{ request()->is('renewal*') ? 'active' : '' }}"
                            href="/renewal">Renewal</a>
                        <a class="collapse-item {{ request()->is('maintenance*') ? 'active' : '' }}"
                            href="/maintenance">Maintenance</a>
                        <a class="collapse-item {{ request()->is('cycle*') ? 'active' : '' }}"
                            href="/cycle">Cycle</a>
                    </div>
                </div>
            </li>
            <li
                class="nav-item {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('trn-storage*')? 'active': '' }}">
                <a class="nav-link {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('trn-storage*')? '': 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapseTransaction" aria-expanded="true"
                    aria-controls="collapseTransaction">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Transaction</span>
                </a>
                <div id="collapseTransaction"
                    class="collapse {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('trn-storage*')? 'show': '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('trn-storage*') ? 'active' : '' }}"
                            href="/trn-storage">Storage</a>
                        <a class="collapse-item {{ request()->is('trn-renewal*') ? 'active' : '' }}"
                            href="/trn-renewal">Renewal</a>
                        <a class="collapse-item {{ request()->is('trn-maintenance*') ? 'active' : '' }}"
                            href="/trn-maintenance">Maintenance</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
