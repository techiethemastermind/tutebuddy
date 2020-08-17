<?php
    $active_navs = array();
    $active_navs[$active_nav] = 'active';
?>

<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-dark-dodger-blue sidebar-left" data-perfect-scrollbar>
            <div class="d-flex align-items-center navbar-height">
                <form action="fixed-index.html" class="search-form search-form--black mx-16pt pr-0 pl-16pt">
                    <input type="text" class="form-control pl-0" placeholder="Search">
                    <button class="btn" type="submit"><i class="material-icons">search</i></button>
                </form>
            </div>

            <a href="fixed-index.html" class="sidebar-brand ">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <span class="avatar-title rounded bg-primary"><img
                            src="{{ asset('assets/img/illustration/student/128/white.svg') }}" class="img-fluid" alt="logo" /></span>
                </span>

                <span>Luma</span>
            </a>
            <!-- Sidebar Head -->
            <div class="sidebar-heading">{{ auth()->user()->role->name }}</div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="sidebar-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">home</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-courses.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">local_library</span>
                        <span class="sidebar-menu-text">Browse Courses</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-paths.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">style</span>
                        <span class="sidebar-menu-text">Browse Paths</span>
                    </a>
                </li>
                <li class="sidebar-menu-item @if(isset($active_navs['student_dashboard'])) {{ $active_navs['student_dashboard'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_box</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-my-courses.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">search</span>
                        <span class="sidebar-menu-text">My Courses</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-paths.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">timeline</span>
                        <span class="sidebar-menu-text">My Paths</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-path.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">change_history</span>
                        <span class="sidebar-menu-text">Path Details</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-course.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">face</span>
                        <span class="sidebar-menu-text">Course Preview</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-lesson.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">panorama_fish_eye</span>
                        <span class="sidebar-menu-text">Lesson Preview</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-take-course.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">class</span>
                        <span class="sidebar-menu-text">Take Course</span>
                        <span class="sidebar-menu-badge badge badge-accent badge-notifications ml-auto">PRO</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-take-lesson.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        <span class="sidebar-menu-text">Take Lesson</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-take-quiz.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dvr</span>
                        <span class="sidebar-menu-text">Take Quiz</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-quiz-results.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">poll</span>
                        <span class="sidebar-menu-text">My Quizzes</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-quiz-result-details.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">live_help</span>
                        <span class="sidebar-menu-text">Quiz Result</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-path-assessment.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">layers</span>
                        <span class="sidebar-menu-text">Skill Assessment</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-student-path-assessment-result.html">
                        <span
                            class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment_turned_in</span>
                        <span class="sidebar-menu-text">Skill Result</span>
                    </a>
                </li>

            </ul>


            <!----------------------->

            @if(auth()->user()->role->slug == 'teacher')
            <div class="sidebar-heading">Instructor</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item  @if(isset($active_navs['teacher_dashboard'])) {{ $active_navs['teacher_dashboard'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">school</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item @if(isset($active_navs['teacher_courses'])) {{ $active_navs['teacher_courses'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.courses.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        <span class="sidebar-menu-text">Manage Courses</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-instructor-quizzes.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">help</span>
                        <span class="sidebar-menu-text">Manage Quizzes</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-instructor-earnings.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">trending_up</span>
                        <span class="sidebar-menu-text">Earnings</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-instructor-statement.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">receipt</span>
                        <span class="sidebar-menu-text">Statement</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-instructor-edit-course.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">post_add</span>
                        <span class="sidebar-menu-text">Edit Course</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fixed-instructor-edit-quiz.html">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">format_shapes</span>
                        <span class="sidebar-menu-text">Edit Quiz</span>
                    </a>
                </li>

            </ul>
            @endif

            @if(auth()->user()->role->slug == 'super_admin')
            <div class="sidebar-heading">Administrator</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item  @if(isset($active_navs['super_admin'])) {{ $active_navs['super_admin'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item  @if(isset($active_navs['admin_category'])) {{ $active_navs['admin_category'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.categories.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dvr</span>
                        <span class="sidebar-menu-text">Categories</span>
                    </a>
                </li>

                <li class="sidebar-menu-item  @if(isset($active_navs['admin_role'])) {{ $active_navs['admin_role'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.roles.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dvr</span>
                        <span class="sidebar-menu-text">Role Management</span>
                    </a>
                </li>

                <li class="sidebar-menu-item  @if(isset($active_navs['admin_users'])) {{ $active_navs['admin_users'] }} @endif">
                    <a class="sidebar-menu-button" href="{{ route('admin.users.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dvr</span>
                        <span class="sidebar-menu-text">User Management</span>
                    </a>
                </li>
            </ul>
            @endif

            <div class="sidebar-heading">Applications</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#enterprise_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                        Enterprise
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="enterprise_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-erp-dashboard.html">
                                <span class="sidebar-menu-text">ERP Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-crm-dashboard.html">
                                <span class="sidebar-menu-text">CRM Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-hr-dashboard.html">
                                <span class="sidebar-menu-text">HR Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-employees.html">
                                <span class="sidebar-menu-text">Employees</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-staff.html">
                                <span class="sidebar-menu-text">Staff</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-leaves.html">
                                <span class="sidebar-menu-text">Leaves</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-departments.html">
                                <span class="sidebar-menu-text">Departments</span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-documents.html">
                                <span class="sidebar-menu-text">Documents</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-attendance.html">
                                <span class="sidebar-menu-text">Attendance</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-recruitment.html">
                                <span class="sidebar-menu-text">Recruitment</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-payroll.html">
                                <span class="sidebar-menu-text">Payroll</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-training.html">
                                <span class="sidebar-menu-text">Training</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-employee-profile.html">
                                <span class="sidebar-menu-text">Employee Profile</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-accounting.html">
                                <span class="sidebar-menu-text">Accounting</span>
                            </a>
                            </li>
                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-inventory.html">
                                <span class="sidebar-menu-text">Inventory</span>
                            </a>
                            </li> -->
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#productivity_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">access_time</span>
                        Productivity
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="productivity_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-projects.html">
                                <span class="sidebar-menu-text">Projects</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-tasks-board.html">
                                <span class="sidebar-menu-text">Tasks Board</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-tasks-list.html">
                                <span class="sidebar-menu-text">Tasks List</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-kanban.html">
                                <span class="sidebar-menu-text">Kanban</span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button disabled" href="fixed-task-details.html">
                            <span class="sidebar-menu-text">Task Details</span>
                        </a>
                        </li>
                        <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button disabled" href="fixed-team-members.html">
                            <span class="sidebar-menu-text">Team Members</span>
                        </a>
                        </li> -->
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#ecommerce_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">shopping_cart</span>
                        eCommerce
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="ecommerce_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-ecommerce.html">
                                <span class="sidebar-menu-text">Shop Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button disabled" href="fixed-edit-product.html">
                                <span class="sidebar-menu-text">Edit Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#messaging_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">message</span>
                        Messaging
                        <span class="sidebar-menu-badge badge badge-accent badge-notifications ml-auto">2</span>
                        <span class="sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="messaging_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-messages.html">
                                <span class="sidebar-menu-text">Messages</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-email.html">
                                <span class="sidebar-menu-text">Email</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#cms_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">content_copy</span>
                        CMS
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="cms_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-cms-dashboard.html">
                                <span class="sidebar-menu-text">CMS Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-posts.html">
                                <span class="sidebar-menu-text">Posts</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#account_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_box</span>
                        Account
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="account_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-pricing.html">
                                <span class="sidebar-menu-text">Pricing</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-login.html">
                                <span class="sidebar-menu-text">Login</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-signup.html">
                                <span class="sidebar-menu-text">Signup</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-signup-payment.html">
                                <span class="sidebar-menu-text">Payment</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-reset-password.html">
                                <span class="sidebar-menu-text">Reset Password</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-change-password.html">
                                <span class="sidebar-menu-text">Change Password</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-edit-account.html">
                                <span class="sidebar-menu-text">Edit Account</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-edit-account-profile.html">
                                <span class="sidebar-menu-text">Profile &amp; Privacy</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-edit-account-notifications.html">
                                <span class="sidebar-menu-text">Email Notifications</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-edit-account-password.html">
                                <span class="sidebar-menu-text">Account Password</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-billing.html">
                                <span class="sidebar-menu-text">Subscription</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-billing-upgrade.html">
                                <span class="sidebar-menu-text">Upgrade Account</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-billing-payment.html">
                                <span class="sidebar-menu-text">Payment Information</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-billing-history.html">
                                <span class="sidebar-menu-text">Payment History</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-billing-invoice.html">
                                <span class="sidebar-menu-text">Invoice</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#community_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people_outline</span>
                        Community
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="community_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-teachers.html">

                                <span class="sidebar-menu-text">Browse Teachers</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-student-profile.html">

                                <span class="sidebar-menu-text">Student Profile</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-teacher-profile.html">

                                <span class="sidebar-menu-text">Teacher Profile</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-blog.html">

                                <span class="sidebar-menu-text">Blog</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-blog-post.html">

                                <span class="sidebar-menu-text">Blog Post</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-faq.html">
                                <span class="sidebar-menu-text">FAQ</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-help-center.html">
                                <!--  -->
                                <span class="sidebar-menu-text">Help Center</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-discussions.html">
                                <span class="sidebar-menu-text">Discussions</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-discussion.html">
                                <span class="sidebar-menu-text">Discussion Details</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fixed-discussions-ask.html">
                                <span class="sidebar-menu-text">Ask Question</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- // END drawer -->