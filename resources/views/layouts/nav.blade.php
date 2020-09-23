<!-- Header -->
@if(\Request::route()->getName() == 'homepage')
<div id="header" class="mdk-header mdk-header--bg-dark bg-dark js-mdk-header mb-0"
    data-effects="parallax-background waterfall" data-fixed data-condenses>
@else
<div id="header" class="mdk-header js-mdk-header mb-0" data-fixed data-effects="">
@endif

    @if(\Request::route()->getName() == 'homepage')
    <div class="mdk-header__bg">
        <div class="mdk-header__bg-front"
            style="background-image: url({{ asset('assets/img/hero-background-elearning.jpg') }});">
        </div>
    </div>
    <div class="mdk-header__content justify-content-center">
        @else
        <div class="mdk-header__content">
            @endif

            <?php
        $nav_class = (\Request::route()->getName() == 'homepage') ? 'navbar-dark navbar-dark-dodger-blue bg-transparent will-fade-background' : 'navbar-light navbar-light-dodger-blue navbar-shadow';
    ?>

        <div class="navbar navbar-expand {{ $nav_class }}" id="default-navbar" data-primary>

            @if(auth()->check())
            <!-- Navbar toggler -->
            <button class="navbar-toggler w-auto mr-16pt d-block rounded-0" type="button" data-toggle="sidebar">
                <span class="material-icons">short_text</span>
            </button>
            @endif

            <!-- Navbar Brand -->
            <a href="{{ config('app.url') }}" class="navbar-brand mr-16pt">
                <!-- <img class="navbar-brand-icon" src="assets/images/logo/white-100@2x.png" width="30" alt="Luma"> -->

                <?php
                    $nav_logo = asset('assets/img/logo/tutebuddy-logo-full.png');
                    if(\Request::route()->getName() == 'homepage' && !empty(config('nav_logo_dark'))) {
                        $nav_logo = asset('storage/logos/' . config('nav_logo_dark'));
                    }

                    if(\Request::route()->getName() != 'homepage' && !empty(config('nav_logo'))) {
                        $nav_logo = asset('storage/logos/'.config('nav_logo'));
                    }
                ?>

                <span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">
                    <img src="{{ $nav_logo }}" alt="logo" class="img-fluid" />
                </span>
            </a>

            @if(!auth()->check())

            <ul class="nav navbar-nav ml-auto mr-0 desktop-only">
                <li class="nav-item">
                    <a href="{{ route('register') }}?r=t" class="btn btn-outline-nav" >Join As Teacher</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}?r=s" class="btn btn-outline-nav">Join As Student</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="btn btn-outline-nav">Login To Account</a>
                </li>
            </ul>

            <div class="nav-item dropdown ml-auto mr-0 mobile-only">
                <a href="#" class="btn btn-outline-nav dropdown-toggle"
                    data-toggle="dropdown" data-caret="false">Account
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('register') }}?r=t">Join As Teacher</a>
                    <a class="dropdown-item" href="{{ route('register') }}?r=s">Join As Student</a>
                    <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                </div>
            </div>

            @else

            <div class="flex"></div>

            <div class="nav navbar-nav flex-nowrap d-flex mr-16pt">

                

                <!-- Mini card -->

                <div class="nav-item ml-16pt nav-cart">
                    <a href="{{ route('cart.index') }}" class="nav-link btn-flush" type="button">
                        <i class="material-icons">shopping_basket</i>
                        @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                            <span class="badge badge-notifications badge-accent">
                                {{Cart::session(auth()->user()->id)->getTotalQuantity()}}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Notifications dropdown -->
                <!-- <div class="nav-item ml-16pt dropdown dropdown-notifications dropdown-xs-down-full"
                    data-toggle="tooltip" data-title="Notifications" data-placement="bottom"
                    data-boundary="window">
                    <button class="nav-link btn-flush dropdown-toggle" type="button" data-toggle="dropdown"
                        data-caret="false">
                        <i class="material-icons">notifications_none</i>
                        <span class="badge badge-notifications badge-accent">2</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div data-perfect-scrollbar class="position-relative">
                            <div class="dropdown-header"><strong>System notifications</strong></div>
                            <div class="list-group list-group-flush mb-0">

                                <a href="javascript:void(0);"
                                    class="list-group-item list-group-item-action unread">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">3 minutes ago</small>

                                        <span class="ml-auto unread-indicator bg-accent"></span>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-accent">account_circle</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">

                                            <span class="text-black-70">Your profile information has not been
                                                synced correctly.</span>
                                        </span>
                                    </span>
                                </a>

                                <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">5 hours ago</small>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-primary">group_add</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">
                                            <strong class="text-black-100">Adrian. D</strong>
                                            <span class="text-black-70">Wants to join your private group.</span>
                                        </span>
                                    </span>
                                </a>

                                <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                                    <span class="d-flex align-items-center mb-1">
                                        <small class="text-black-50">1 day ago</small>

                                    </span>
                                    <span class="d-flex">
                                        <span class="avatar avatar-xs mr-2">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <i
                                                    class="material-icons font-size-16pt text-warning">storage</i>
                                            </span>
                                        </span>
                                        <span class="flex d-flex flex-column">

                                            <span class="text-black-70">Your deploy was successful.</span>
                                        </span>
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- // END Notifications dropdown -->

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center dropdown-toggle"
                        data-toggle="dropdown" data-caret="false">

                        <span class="avatar avatar-sm mr-8pt2">

                        @if(!empty(auth()->user()->avatar))
                        <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar) }}" alt="people" class="avatar-img rounded-circle">
                        @else
                        <span class="avatar-title rounded-circle">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        @endif

                        </span>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header"><strong>Account</strong></div>
                        <a class="dropdown-item" href="{{ route('admin.myaccount') }}?active=account">Edit Account</a>
                        <a class="dropdown-item" href="{{ route('admin.myaccount') }}?active=billing">Billing</a>
                        <a class="dropdown-item" href="{{ route('admin.myaccount') }}?active=payment">Payments</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if(\Request::route()->getName() == 'homepage')
        <div class="hero container page__container text-center text-md-left py-112pt" style="min-height: 540px;">
            <div class="col-lg-10 mx-auto">
                <h1 class="text-white text-shadow py-16pt text-center">Learn anything online.</h1>
                <div class="form-group" style="position: relative;">
                    <div class="search-form input-group-lg">
                        <input type="text" class="form-control" placeholder="What do you want to learn today?" id="search_homepage">
                        <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                    </div>
                </div>
            </div>
            </p>
        </div>
        @endif
    </div>
</div>