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

            @can('superadmin')
                <li
                    class="nav-item {{ request()->is('asset-parent*') || request()->is('asset-child*') || request()->is('group*') || request()->is('asset-group*') || request()->is('document-group*') || request()->is('renewal*') || request()->is('maintenance*') || request()->is('cycle*') || request()->is('sbu*') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->is('asset-parent*') || request()->is('asset-child*') || request()->is('group*') || request()->is('asset-group*') || request()->is('document-group*') || request()->is('renewal*') || request()->is('maintenance*') || request()->is('cycle*') || request()->is('sbu*') ? '' : 'collapsed' }}"
                        href="#" data-toggle="collapse" data-target="#collapseData" aria-expanded="true"
                        aria-controls="collapseData">
                        <i class="fas fa-database"></i>
                        <span>Data</span>
                    </a>
                    <div id="collapseData"
                        class="collapse {{ request()->is('asset-parent*') || request()->is('asset-child*') || request()->is('group*') || request()->is('asset-group*') || request()->is('document-group*') || request()->is('renewal*') || request()->is('maintenance*') || request()->is('cycle*') || request()->is('sbu*') ? (detect() ?: 'show') : '' }}"
                        aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item  {{ request()->is('asset-parent*') ? 'active' : '' }}"
                                href="/asset-parent">
                                Asset
                            </a>
                            <a class="collapse-item  {{ request()->is('asset-child*') ? 'active' : '' }}"
                                href="/asset-child">
                                Document
                            </a>
                            {{-- <a class="collapse-item {{ request()->is('cycle*') ? 'active' : '' }}" href="/cycle">
                            Cycle
                                </a> --}}
                            <a class="collapse-item {{ request()->is('sbu*') ? 'active' : '' }}" href="/sbu">
                                SBU
                            </a>
                            <a class="collapse-item  {{ request()->is('group*') || request()->is('asset-group*') || request()->is('document-group*') || request()->is('renewal*') || request()->is('maintenance*') ? 'active' : '' }}"
                                href="/group">
                                Type
                            </a>
                            <a class="collapse-item {{ request()->is('sdb*') ? 'active' : '' }}" href="/sdb">
                                SDB
                            </a>
                        </div>
                    </div>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item  {{ request()->is('asset-parent*') ? 'active' : '' }}">
                    <a class="nav-link" href="/asset-parent">
                        <i class="fas fa-warehouse"></i>
                        <span>Asset</span>
                    </a>
                </li>
                <li class="nav-item  {{ request()->is('asset-child*') ? 'active' : '' }}">
                    <a class="nav-link" href="/asset-child">
                        <i class="fas fa-file-alt"></i>
                        <span>Document</span>
                    </a>
                </li>
            @endcan
            <li
                class="nav-item {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('loan*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('loan*') ? '' : 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapseTransaction" aria-expanded="true"
                    aria-controls="collapseTransaction">
                    <i class="fas fa-fw fa-hand-holding-usd"></i>
                    <span>Transaction</span>
                </a>
                <div id="collapseTransaction"
                    class="collapse {{ request()->is('trn-renewal*') || request()->is('trn-maintenance*') || request()->is('loan*') ? (detect() ?: 'show') : '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('trn-renewal*') ? 'active' : '' }}"
                            href="/trn-renewal">Renewal</a>
                        <a class="collapse-item {{ request()->is('trn-maintenance*') ? 'active' : '' }}"
                            href="/trn-maintenance">Maintenance</a>
                        {{-- <a class="collapse-item {{ request()->is('loan*') ? 'active' : '' }}" href="/loan">Loan</a> --}}
                    </div>
                </div>
            </li>
            <li class="nav-item  {{ request()->is('report*') ? 'active' : '' }}">
                <a class="nav-link" href="/report">
                    <i class="fas fa-fw fa-file-download"></i>
                    <span>Report</span>
                </a>
            </li>
            <li class="nav-item  {{ request()->is('form*') ? 'active' : '' }}">
                <a class="nav-link" href="/form">
                    <i class="fas fa-fw fa-folder-open"></i>
                    <span>Form Template</span>
                </a>
            </li>

            {{-- <li class="nav-item {{ request()->is('/export/asset') ? 'active' : '' }}">
                <a class="nav-link" href="/export/asset">
                    <i class="fas fa-database"></i>
                    <span>Report</span></a>
            </li> --}}

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0 bg-custom" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
