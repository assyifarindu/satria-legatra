<div class="left-side-menu sidebar_content">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">

                @if (PermissionActionMenu('license') == true ||
                        PermissionActionMenu('contract') == true ||
                        PermissionActionMenu('haki') == true)
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fe-home"></i>
                            <span>Dashboard </span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fe-home"></i>
                            <span>Dashboard </span>
                        </a>
                    </li>
                @endif

                @if (PermissionActionMenu('license') == true ||
                        PermissionActionMenu('contract') == true ||
                        PermissionActionMenu('haki') == true)
                    <li>
                        <a href="{{ route('tsp.request-document.index') }}">
                            <i class="fe-file-plus"></i>
                            <span>Request Document </span>
                        </a>
                    </li>
                @endif



                @if (PermissionActionMenu('tracking') == true)
                    <li>
                        {{-- <a href="{{ route('tracking-tsp.index') }}"> --}}
                        <a href="#">
                            <i class="fe-navigation"></i> Drafting/Feedback
                            Document
                            @if (countContract() > 0)
                                <span class="badge bg-danger rounded-pill float-end">{{ countContract() }}</span>
                            @endif
                        </a>
                    </li>
                @endif

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
