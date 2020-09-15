<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-light-dodger-blue sidebar-left" data-perfect-scrollbar>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand ">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <img src="@if(!empty(config('sidebar_logo'))) 
                                {{ asset('storage/logos/'.config('sidebar_logo')) }}
                            @else 
                                {{ asset('assets/img/logo/tutebuddy-menu-logo.png') }}
                            @endif" alt="logo" class="img-fluid" />
                </span>
            </a>
            <!-- Sidebar Head -->
            <div class="sidebar-heading">{{ auth()->user()->roles->pluck('name')[0] }}</div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="sidebar-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">home</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                @can('category_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/categories*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.categories.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">Categories</span>
                    </a>
                </li>
                @endcan

                @can('level_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/levels*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.levels.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">near_me</span>
                        <span class="sidebar-menu-text">Levels</span>
                    </a>
                </li>
                @endcan

                @if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Instructor'))
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#courses_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        Teach
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="courses_menu" style="">

                        @can('type_access')
                        <!-- <li class="sidebar-menu-item {{ Request::is('dashboard/types*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.types.index') }}">
                                <span class="sidebar-menu-text">Types</span>
                            </a>
                        </li> -->
                        @endcan

                        @can('course_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/courses*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.courses.index') }}">
                                <span class="sidebar-menu-text">Courses</span>
                            </a>
                        </li>
                        @endcan

                        @can('bundle_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/bundle*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.bundles.index') }}">
                                <span class="sidebar-menu-text">Paths</span>
                            </a>
                        </li>
                        @endcan

                        @can('schedule_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/schedule*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.schedule') }}">
                                <span class="sidebar-menu-text">Schedule</span>
                            </a>
                        </li>
                        @endcan

                        @can('test_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/test*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.tests.index') }}">
                                <span class="sidebar-menu-text">Tests</span>
                            </a>
                        </li>
                        @endcan

                        @can('question_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/question*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.questions.index') }}">
                                <span class="sidebar-menu-text">Questions</span>
                            </a>
                        </li>
                        @endcan

                        @can('assignment_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/bundle*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.assignments.index') }}">
                                <span class="sidebar-menu-text">Assignments</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>

                <!-- reviews for course -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/review*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.reviews.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">star_half</span>
                        <span class="sidebar-menu-text">Reviews</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#report_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">event_note</span>
                        Reports
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="report_menu" style="">

                        @can('order_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Orders</span>
                            </a>
                        </li>
                        @endcan

                        <li class="sidebar-menu-item {{ Request::is('dashboard/sale*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Sales</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/student*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Students</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(auth()->user()->hasRole('Student'))
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#learn_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        Browser
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="learn_menu" style="">

                        <li class="sidebar-menu-item {{ Request::is('search/courses*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('courses.search') }}">
                                <span class="sidebar-menu-text">Courses</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('search/instructor*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('teachers.search') }}">
                                <span class="sidebar-menu-text">Instructors</span>
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Cert -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/certificate*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.certificates.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">new_releases</span>
                        <span class="sidebar-menu-text">My Certificates</span>
                    </a>
                </li>

                @endif

                <!-- Coupon -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/coupon*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">card_giftcard</span>
                        <span class="sidebar-menu-text">Coupons</span>
                    </a>
                </li>

                <!-- Messages -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/message*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">send</span>
                        <span class="sidebar-menu-text">Messages</span>
                    </a>
                </li>

                <!-- My Account -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/account*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.myaccount') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_circle</span>
                        <span class="sidebar-menu-text">My Account</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->hasRole('Administrator'))

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Sidebar Head -->
                <div class="sidebar-heading">System</div>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#access_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person</span>
                        Access
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="access_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/users*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.index') }}">
                                <span class="sidebar-menu-text">Users</span>
                            </a>
                        </li>

                        @can('role_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/roles*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.roles.index') }}">
                                <span class="sidebar-menu-text">Roles</span>
                            </a>
                        </li>
                        @endcan

                        <li class="sidebar-menu-item {{ Request::is('dashboard/institution*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Institutions</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/instructor*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Teachers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#setting_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                        Settings
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/general*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.general') }}">
                                <span class="sidebar-menu-text">General</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/lang*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Language</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/page*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Social</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif
            
        </div>
    </div>
</div>
<!-- // END drawer -->

@push('after-scripts')
<script>
    $(document).ready(function(){

        // Make parent menu active
        var active_menus = $('li.sidebar-menu-item.active');
        $.each(active_menus, function(idx, item){
            $(this).closest('ul.sidebar-submenu').parent().addClass('active open');
        });
    });
</script>
@endpush