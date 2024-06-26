<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="index.html"><img src="{{ asset('images/logo/img4.png') }}"
                alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="/admin/assets/images/logo-mini.svg"
                alt="logo" /></a>
    </div>
    <ul class="nav">
        @guest

        @endguest
        @if (Auth::check())
            <li class="nav-item profile">
                <div class="profile-desc">
                    <div class="profile-pic">
                        <div class="count-indicator">
                            <img class="img-xs rounded-circle " src="/admin/assets/images/faces/face15.jpg"
                                alt="">
                            <span class="count bg-success"></span>
                        </div>
                        <div class="profile-name">
                            <h5 class="mb-0 font-weight-normal">{{ Auth::user()->name }}</h5>
                            <span>{{ Auth::user()->role }}</span>
                        </div>
                    </div>
                    <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i
                            class="mdi mdi-dots-vertical"></i></a>
                    <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                        aria-labelledby="profile-dropdown">
                        <a href="#" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-settings text-primary"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-onepassword  text-info"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-calendar-today text-success"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                            </div>
                        </a>
                    </div>
                </div>
            </li>

            @if (Auth::user()->role == 'admin')
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-speedometer"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('admin.staffManagement') }}">
                        <span class="menu-icon">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <span class="menu-title">Users</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('admin.productManagement') }}">
                        <span class="menu-icon">
                            <i class="fa-solid fa-mug-hot"></i>
                        </span>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('admin.getCategories') }}">
                        <span class="menu-icon">
                            <i class="fa-solid fa-regular fa-list"></i>
                        </span>
                        <span class="menu-title">Category</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'seller')
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('seller.orderManage') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-laptop"></i>
                        </span>
                        <span class="menu-title">Orders</span>
                    </a>
                   
                </li>
            @endif

            @if (Auth::user()->role == 'bartender')
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('bartender.getReceiveOrder') }}">
                        <span class="menu-icon">
                            <i class="fa-solid fa-bars"></i>
                        </span>
                        <span class="menu-title">Receive Order</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('bartender.getProductStock') }}">
                        <span class="menu-icon">
                            <i class="fa-solid fa-mug-hot"></i>
                        </span>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</nav>
