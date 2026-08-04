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
                        <a href="#filing" data-bs-toggle="collapse">
                            <i class="fe-file-text"></i>
                            <span> Filing </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="filing">
                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                @if (PermissionActionMenu('contract') == true)
                                    <li>
                                        <a href="{{ route('contract.index') }}">Contract/Letter</a>
                                    </li>
                                @endif

                                @if (PermissionActionMenu('license') == true)
                                    <li>
                                        <a href="{{ route('license.index') }}">Licences</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('haki') == true)
                                    <li>
                                        <a href="{{ route('haki.index') }}">HAKI </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('qr-document.index') }}">QR Document </a>
                                </li>
                                <li>
                                    <a href="{{ route('generate-number.index') }}">
                                        <span>Generate Number </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    {{-- <li>
                        <a href="{{ route('request-document.index')}}">
                            <i class="fe-clipboard"></i>
                            <span>Request Document </span>
                        </a>
                    </li> --}}

                    <li>
                        <a href="#requestuser" data-bs-toggle="collapse">
                            <i class="fe-file-plus"></i>
                            <span> Request </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="requestuser">
                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                <li>
                                    <a href="{{ route('request-document.index') }}">
                                        <span>Feedback/Drafting Document </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('request-extend.index') }}">
                                        <span>Extend Document </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('request-existing.index') }}">
                                        <span>Existing Document </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('request-qr-user') }}">
                                        <span>QR Document </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="#filing" data-bs-toggle="collapse">
                            <i class="fe-file-text"></i>
                            <span> Filing </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="filing">

                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                <li>
                                    <a href="{{ route('contract-user.index') }}">
                                        <span>Contract/Letter </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('license-user.index') }}">
                                        <span>Licences </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('haki-user.index') }}">
                                        <span>HAKI </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('alert-user.index') }}">
                            <i class="fe-flag"></i>
                            <span>Alert @if (countAlert() > 0)
                                    <span class="badge bg-danger rounded-pill float-end">{{ countAlert() }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                @endif

                {{-- @if (PermissionActionMenu('extended-contract') == true || PermissionActionMenu('extended-license') == true || PermissionActionMenu('extended-haki') == true)
                    <li>
                        <a href="#extend" data-bs-toggle="collapse">
                            <i class="fe-file-minus"></i>
                            <span> Extend </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="extend">
                            <ul class="nav-second-level">
                                @if (PermissionActionMenu('extended-contract') == true)
                                    <li>
                                        <a href="{{ route('extended-contract.index')}}">Extend Contract/Letter</a>
                                    </li>
                                @endif

                                @if (PermissionActionMenu('extended-license') == true)
                                    <li>
                                        <a href="{{ route('extended-license.index')}}">Extend Licences</a>
                                    </li>
                                @endif

                                @if (PermissionActionMenu('extended-haki') == true)
                                    <li>
                                        <a href="{{ route('extended-haki.index') }}">Extend HAKI </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @else
                    <li>
                        <a href="#extend" data-bs-toggle="collapse">
                            <i class="fe-file-minus"></i>
                            <span> Extend </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="extend">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('extend-contract-user.index') }}">
                                        <span>Extend Contract/Letter </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('extend-license-user.index')}}">
                                        <span>Extend Licences </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('extend-haki-user.index')}}">
                                        <span>Extend HAKI </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif --}}




                @if (PermissionActionMenu('tracking') == true ||
                        PermissionActionMenu('tracking-license') == true ||
                        PermissionActionMenu('tracking-haki') == true)
                    <li>
                        <a href="#request" data-bs-toggle="collapse">
                            <i class="fe-navigation"></i>
                            <span> Request
                                @if (countRequest() > 0)
                                    <span class="badge bg-danger rounded-pill ml-5 pl-5">{{ countRequest() }}</span>
                                @endif
                            </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="request">
                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                @if (PermissionActionMenu('tracking') == true)
                                    <li>
                                        <a href="{{ route('tracking.index') }}">Feedback/Drafting Document
                                            @if (countContract() > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill float-end">{{ countContract() }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif

                                @if (PermissionActionMenu('tracking-license') == true)
                                    <li>
                                        <a href="{{ route('tracking-license.index') }}">Licences
                                            @if (countLicense() > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill float-end">{{ countLicense() }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('tracking-haki') == true)
                                    <li>
                                        <a href="{{ route('tracking-haki.index') }}">HAKI
                                            @if (countHaki() > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill float-end">{{ countHaki() }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('tracking-existing') == true)
                                    <li>
                                        <a href="{{ route('tracking-existing.index') }}">Existing Document
                                            @if (countExisting() > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill float-end">{{ countExisting() }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('request-qr.index') }}">QR Document
                                        @if (countRequestQR() > 0)
                                            <span
                                                class="badge bg-danger rounded-pill float-end">{{ countRequestQR() }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (PermissionActionMenu('alert-contract') == true ||
                        PermissionActionMenu('alert-license') == true ||
                        PermissionActionMenu('alert-haki') == true)
                    <li>
                        <a href="#alert" data-bs-toggle="collapse">
                            <i class="fe-flag"></i>
                            <span> Alert </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="alert">
                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                @if (PermissionActionMenu('alert-contract') == true)
                                    <li>
                                        <a href="{{ route('contract-alert.index') }}">Contract / Letter</a>
                                    </li>
                                @endif

                                @if (PermissionActionMenu('alert-license') == true)
                                    <li>
                                        <a href="{{ route('license-alert.index') }}">Licences</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('alert-haki') == true)
                                    <li>
                                        <a href="{{ route('alert-haki.index') }}">HAKI </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if (PermissionActionMenu('master-company') == true ||
                        PermissionActionMenu('master-alert') == true ||
                        PermissionActionMenu('master-template') == true ||
                        PermissionActionMenu('master-pic') == true)
                    <li class="mt-2">
                        <a href="#master" data-bs-toggle="collapse">
                            <i class="fe-box"></i>
                            <span> Master </span>
                            <span class="menu-arrow"></span>

                        </a>
                        <div class="collapse" id="master">
                            <ul class="nav-second-level" style="background-color: #FFFFFF">
                                @if (PermissionActionMenu('contract-number') == true)
                                    <li>
                                        <a href="{{ route('contract-number.index') }}">Contract Number</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-company') == true)
                                    <li>
                                        <a href="{{ route('master-company.index') }}">Company</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-alert') == true)
                                    <li>
                                        <a href="{{ route('master-alert.index') }}">Alert</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-template') == true)
                                    <li>
                                        <a href="{{ route('master-template.index') }}">Template</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-pic') == true)
                                    <li>
                                        <a href="{{ route('master-pic.index') }}">People In Charge</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-title') == true)
                                    <li>
                                        <a href="{{ route('master-title.index') }}">Title</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-haki-type') == true)
                                    <li>
                                        <a href="{{ route('master-haki-type.index') }}">Haki Type</a>
                                    </li>
                                @endif
                                @if (PermissionActionMenu('master-duty') == true)
                                    <li>
                                        <a href="{{ route('master-duty.index') }}">Duty</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
