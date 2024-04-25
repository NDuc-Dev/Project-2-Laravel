<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">NDC<small>Coffee</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu">
            </span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('menu') }}" class="nav-link">Menu</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('about') }}" class="nav-link">About Us</a>
                </li>
                <li class="nav-item cart">
                    <a href="{{ route('cart') }}" class="nav-link">
                        <span class="icon icon-shopping_cart"></span>
                        <span class="bag d-flex justify-content-center align-items-center" id="cartItemCount">
                            <small>
                                @php
                                    if (Auth::check()) {
                                        $userId = Auth::id();
                                        $cartItems = session("user_cart_$userId");
                                        $totalQuantity = 0;
                                        foreach ($cartItems as $item) {
                                            $totalQuantity += 1;
                                        }
                                        echo $totalQuantity;
                                    } else {
                                        $totalQuantity = 0;
                                        $cartItems = session('guest_cart');
                                        if ($cartItems) {
                                            foreach ($cartItems as $item) {
                                                $totalQuantity += 1;
                                            }
                                        }
                                        echo $totalQuantity;
                                    }
                                @endphp</small>
                        </span>
                    </a>
                </li>
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">{{ __('Sign In') }}</a>
                        </li>
                    @endif
                @endguest
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{route('setting')}}">
                                Settings
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log out
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
