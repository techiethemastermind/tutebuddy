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

                @if(auth()->user()->hasRole('Instructor'))
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#study_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                        Workspace
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="study_menu" style="">

                        <li class="sidebar-menu-item {{ Request::is('dashboard/live-sessions*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.liveSessions') }}">
                                <span class="sidebar-menu-text">Scheduled Lessons</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/enrolled-students*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.students') }}">
                                <span class="sidebar-menu-text">Enrolled Students</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-assignments*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.submitedAssignments') }}">
                                <span class="sidebar-menu-text">Submitted Assignments</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-tests*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.submitedTests') }}">
                                <span class="sidebar-menu-text">Submitted Tests</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/pre-enrolled*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.messages.preEnrolledStudents') }}">
                                <span class="sidebar-menu-text">Pre-enrolled Students</span>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

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
                        <li class="sidebar-menu-item {{ Request::is('dashboard/course*') ? 'active' : '' }}">
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
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#task_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                        Task
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="task_menu" style="">
                        @can('assignment_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/assignment*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.assignments.index') }}">
                                <span class="sidebar-menu-text">Assignments</span>
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

                        @can('quiz_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/quiz*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.quizs.index') }}">
                                <span class="sidebar-menu-text">Quizzes</span>
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
                    
                        <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.orders') }}">
                                <span class="sidebar-menu-text">Sales</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/transaction*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.transactions') }}">
                                <span class="sidebar-menu-text">Transactions</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/refund*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.refunds') }}">
                                <span class="sidebar-menu-text">Refunds</span>
                            </a>
                        </li>

                        @if(auth()->user()->hasRole('Administrator'))
                        <li class="sidebar-menu-item {{ Request::is('dashboard/contact*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.contacts.index') }}">
                                <span class="sidebar-menu-text">Contacts</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(auth()->user()->hasRole('Student'))
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#study_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                        My Study
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="study_menu" style="">

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/course*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.courses') }}">
                                <span class="sidebar-menu-text">Courses</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/live*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.liveSessions') }}">
                                <span class="sidebar-menu-text">Live Sessions</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/path*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.bundles') }}">
                                <span class="sidebar-menu-text">Paths</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/instructor*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.instructors') }}">
                                <span class="sidebar-menu-text">Instructors</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#my_task_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                        My Tasks
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="my_task_menu" style="">
                    
                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/assignment*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.assignments') }}">
                                <span class="sidebar-menu-text">Assignments</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/quiz*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.quizs') }}">
                                <span class="sidebar-menu-text">Quizzes</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/my/test*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.student.tests') }}">
                                <span class="sidebar-menu-text">Tests</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#browse_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        Browse
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="browse_menu" style="">

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

                        <li class="sidebar-menu-item {{ Request::is('dashboard/courses/get/favorite*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.courses.favorites') }}">
                                <span class="sidebar-menu-text">Favorites</span>
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Course Performance (Result) -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/result*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.results.student') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">poll</span>
                        <span class="sidebar-menu-text">Course Performance</span>
                    </a>
                </li>

                <!-- Cert -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/certificate*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.certificates.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">new_releases</span>
                        <span class="sidebar-menu-text">My Certificates</span>
                    </a>
                </li>

                <!-- Badges -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/badges*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.results.student.badges') }}">
                        <i class="material-icons sidebar-menu-icon sidebar-menu-icon--left fa fa-medal"></i>
                        <span class="sidebar-menu-text">My Badges</span>
                    </a>
                </li>

                @endif

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#community_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people_outline</span>
                        Discussion
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="community_menu">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/discussion*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.index') }}">
                                <span class="sidebar-menu-text">My Topics</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/topic*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.topics') }}">
                                <span class="sidebar-menu-text">Discussion Topics</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Coupon -->
                <!-- <li class="sidebar-menu-item {{ Request::is('dashboard/coupon*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">card_giftcard</span>
                        <span class="sidebar-menu-text">Coupons</span>
                    </a>
                </li> -->

                <!-- Messages -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/message*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.messages.index') }}">
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

                @if(auth()->user()->hasRole('Student'))
                <!-- My Payment History -->
                <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.orders') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">monetization_on</span>
                        <span class="sidebar-menu-text">My Payments</span>
                    </a>
                </li>
                @endif
            </ul>

            @if(auth()->user()->hasRole('Administrator'))

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Sidebar Head -->
                <div class="sidebar-heading">System</div>

                <!-- Pages -->
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#pages_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">book</span>
                        Pages
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="pages_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/page*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.pages.index') }}">
                                <span class="sidebar-menu-text">All Pages</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/faq*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.faqs.index') }}">
                                <span class="sidebar-menu-text">Faqs</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Access -->
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

                        <!-- <li class="sidebar-menu-item {{ Request::is('dashboard/institution*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Institutions</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/instructor*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">Teachers</span>
                            </a>
                        </li> -->
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

                        <li class="sidebar-menu-item {{ Request::is('dashboard/mailedits*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.mailedits.index') }}">
                                <span class="sidebar-menu-text">Email Template</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/tax*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.tax.index') }}">
                                <span class="sidebar-menu-text">Payment Taxes</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/translation-manager*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ asset('dashboard/translations') }}">
                                <span class="sidebar-menu-text">@lang('menus.backend.sidebar.translations.title')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/social*') ? 'active' : '' }}">
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