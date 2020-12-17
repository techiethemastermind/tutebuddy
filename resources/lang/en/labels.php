<?php

return array (
  'frontend' => 
  array (
    'home' => 
    array (
      'search_course_title' => 'Learn anything online.',
      'search_course_placeholder' => 'What do you want to learn today?',
      'select_course' => 
      array (
        'title' => 'Select Course',
      ),
      'find_expert' => 
      array (
        'title' => 'Find an Expert',
      ),
      'start_learning' => 
      array (
        'title' => 'Start Learning',
      ),
      'learning_paths' => 'Learning Paths',
      'featured_courses' => 'Featured Courses',
      'for_instructors' => 
      array (
        'title' => 'For Instructors',
      ),
      'for_business' => 
      array (
        'title' => 'For Business',
      ),
      'for_schools' => 
      array (
        'title' => 'For schools & institutions',
      ),
      'start_teaching' => 'Start Teaching',
      'start_training' => 'Start Training',
      'expert_teachers' => 
      array (
        'title' => 'Expert Teachers to Guide you',
      ),
      'search_teachers_placeholder' => 'Enter name or Subject',
      'education_for_all' => 'Education for all',
      'signup_as_student' => 'Sign up as Student',
      'feedback' => 
      array (
        'title' => 'Feedback',
      ),
    ),
    'search' => 
    array (
      'browse_course' => 'Browse Course',
      'find_instructors' => 'Find Instructors',
    ),
    'cart' => 
    array (
      'cart' => 'Cart',
      'cart_items' => 'Cart Items',
      'process_checkout' => 'Process Checkout',
      'pay_now' => 'Pay Now',
      'order_detail' => 'Order Detail',
      'checkout' => 'Checkout',
      'items' => 'Items',
      'product_added' => 'Product added to cart successfully',
    ),
    'course' => 
    array (
      'certified' => 'You\'re Certified for this course',
      'finish_course' => 'Finish Course',
    ),
  ),
  'backend' => 
  array (
    'translations' => 
    array (
      'title' => 'Translation Manager',
      'warning' => 'Warning, translations are not visible until they are exported back to the app/lang file, using
                        <code>php artisan translation:export</code> command or publish button.',
      'done_importing' => 'Done importing, processed <strong class="counter">N</strong> items! Reload this page to
                            refresh the groups!',
      'done_searching' => 'Done searching for translations, found <strong class="counter">N</strong> items!',
      'done_publishing_for_group' => 'Done publishing the translations for group',
      'done_publishing_for_all_groups' => 'Done publishing the translations for all group!',
      'append_new_translations' => 'Append new translations',
      'replace_existing_translations' => 'Replace existing translations',
      'import_groups' => 'Import Groups',
      'import_groups_note' => '<p>This will get all locale files from <code>lang</code> folder and insert into database.<br> <b>Append new translations :</b> It will append only new files and data <b>&amp;</b>
                                            <b>Replace existing translations:</b>It will replace existing records according to files</p>',
      'choose_a_group' => 'Choose a group to display the group translations. If no groups are visible, make sure
                                you have run the migrations and imported the translations.',
      'translation_warning' => 'Are you sure you want to publish the translations group :group ? This will overwrite existing language files',
      'publishing' => 'Publishing..',
      'publish_translations' => 'Publish Translations',
      'total' => 'Total',
      'changed' => 'Changed',
      'key' => 'Key',
      'supported_locales' => 'Supported Locales',
      'current_supported_locales' => 'Current Supported Locales',
      'add_new_locale' => 'Add new locale',
      'adding' => 'Adding...',
      'export_all_translations' => 'Export all translations',
      'publish_all' => 'Publish All',
      'publish_all_warning' => 'Are you sure you want to publish all translations group? This will overwrite existing language files.',
      'enter_new_locale_key' => 'Enter new locale key',
    ),
    'dashboard' => 
    array (
      'title' => 'Dashboard',
      'students' => 'Students',
      'instructors' => 'Instructors',
      'active_courses' => 'Active Courses',
      'enrolled_courses' => 'Enrolled Courses',
      'total_sales' => 'Total Sales',
      'total_payments' => 'Total Payments',
      'courses_approval' => 'Courses Approval',
      'live_lessons' => 'Live Lessons',
      'courses_pending_approval' => 'Courses Pending Approval',
      'withdraw_requests' => 'Withdraw Requests',
      'orders' => 'Orders',
      'daily_signups' => 'Daily Signups',
      'daily_orders' => 'Daily Orders',
      'table' => 
      array (
        'no' => 'No',
        'title' => 'Title',
        'instructor' => 'Instructor',
        'category' => 'Category',
        'action' => 'Action',
        'transaction_id' => 'Transaction Id',
        'amount' => 'Amount',
        'date' => 'Date',
        'order' => 'Order',
        'customer' => 'Customer',
        'total' => 'Total',
        'order_id' => 'Order Id',
        'course' => 'Course',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'course_title' => 'Course Title',
        'lesson_title' => 'Lesson Title',
        'name' => 'Name',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'status' => 'Status',
        'subject' => 'Subject',
        'due_date' => 'Due Date',
        'total_marks' => 'Total Marks',
        'student' => 'Student',
        'attachment' => 'Attachment',
        'lessons' => 'Lessons',
        'email' => 'Email',
      ),
      'earning_this_month' => 'Earning This Month',
      'account_balance' => 'Account Balance',
      'pending_orders' => 'Pending Orders',
      'upcomming_lessons' => 'Upcoming Lessons',
      'students_roster' => 'Student Roster (Enrolled Students)',
      'assignments_for_students' => 'Assignment For Students',
      'paths' => 'Paths',
      'discussions' => 'Discussions',
      'submitted_assignments' => 'Assignments Submitted by Students',
      'submitted_tests' => 'Test Submitted',
      'submitted_quizzes' => 'Quiz Submitted',
      'create_course' => 'Create Course',
      'create_lesson' => 'Create Lesson',
      'start_teaching' => 'Start Teaching',
      'search_teachers_placeholder' => 'Enter name or Subject',
      'my_live_lessons' => 'My Live Lessons',
      'my_courses' => 'My Courses',
      'my_instructors' => 'My Instructors',
      'my_assignments' => 'My Assignments',
      'my_paths' => 'My Paths',
      'my_tests' => 'My Tests',
      'select_course' => 'Select Course',
      'find_an_expert' => 'Find an expert',
      'start_learning' => 'Start Learning',
    ),
    'buttons' => 
    array (
      'browse_all' => 'Browse All',
      'verify' => 'Verify',
      'confirm' => 'Confirm',
      'publish' => 'Publish',
      'save_draft' => 'Save Draft',
      'preview' => 'Preview',
      'add_new' => 'Add New',
      'submit' => 'Submit',
      'close' => 'Close',
      'save' => 'Save',
      'enroll' => 'Enroll',
      'enrolled' => 'Enrolled',
    ),
    'courses' => 
    array (
      'my_courses' => 'My Courses',
      'title' => 'Courses',
      'all_my_courses' => 'My All Courses',
      'outdated' => 'Outdated Courses',
      'my_favorites' => 
      array (
        'title' => 'My Favorites',
        'no_results' => 'No Favorites',
      ),
      'no_results' => 'No Courses',
      'create' => 'Create Course',
      'fields' => 
      array (
        'title' => 'Title',
        'description' => 'Description',
        'description_note' => 'Shortly describe this course. It will show under title',
        'about_course' => 'About Course',
        'about_course_note' => 'Describe about this course. What you will teach?',
      ),
      'add_lesson' => 'Add Lesson',
      'create_lesson' => 'Create Lesson',
      'edit' => 'Edit Course',
      'set_schedule' => 'Set Schedule',
      'add_course' => 'Add Course',
      'all' => 'All Courses',
      'draft' => 'Drafted Courses',
      'pending' => 'Pending to Review',
      'published' => 'Accepted Courses',
      'achieved' => 'Deleted Courses',
    ),
    'table' => 
    array (
      'no' => 'NO.',
      'title' => 'Title',
      'owner' => 'Owner',
      'category' => 'Category',
      'progress_percent' => 'Progress (%)',
      'actions' => 'Actions',
      'date' => 'Date',
      'start_time' => 'Start Time',
      'end_time' => 'End Time',
      'course_title' => 'Course Title',
      'lesson_title' => 'Lesson Title',
      'name' => 'Name',
      'email' => 'Email',
      'due_date' => 'Due Date',
      'marks' => 'Marks',
      'type' => 'Type',
      'duration' => 'Duration',
      'total_marks' => 'Total Marks',
      'status' => 'Status',
      'course_name' => 'Course Name',
      'score' => 'Score',
      'percentage' => 'Percentage',
      'grade' => 'Grade',
      'badge' => 'Badge',
      'result' => 'Result',
      'topics' => 'Topics',
      'course' => 'Course',
      'last_message' => 'Last Message',
      'message_time' => 'Message Time',
      'price' => 'Price',
      'course_type' => 'Course Type',
      'subject' => 'Subject',
      'student' => 'Student',
      'attachment' => 'Attachment',
      'lesson' => 'Lesson',
    ),
    'lessons' => 
    array (
      'my_live_lessons' => 'My Live Lessons',
      'all_live_lessons' => 'All Live Lessons',
      'today_scheduled' => 'Today Scheduled',
      'today_scheduled_description' => 'Today Scheduled Live Lessons',
      'teacher' => 
      array (
        'live_lesson' => 'My Scheduled Lessons',
        'all' => 'All Scheduled Lessons',
        'scheduled_title' => 'Today Scheduled',
        'scheduled_description' => 'Today Scheduled Live Lessons',
      ),
    ),
    'paths' => 
    array (
      'learning_paths' => 
      array (
        'title' => 'Learning Paths',
      ),
      'my_paths' => 'My Paths',
      'no_paths' => 'No Paths',
      'title' => 'Paths',
      'all' => 'All Paths',
      'achieved' => 'Deleted Bundles',
      'create' => 'Create a Path',
      'information' => 'Path Information',
      'fields' => 
      array (
        'title' => 'Path Title',
        'description' => 'Path Description',
      ),
      'description_note' => 'Shortly describe this course. It will show under title',
      'edit' => 'Edit Path',
      'courses' => 'Courses',
    ),
    'my_instructors' => 
    array (
      'title' => 'My Instructors',
    ),
    'my_assignments' => 
    array (
      'title' => 'My Assignments',
      'all_assignments' => 'All Assignments',
      'marked_assginments' => 'Marked Assignments',
    ),
    'assignments' => 
    array (
      'all_assignments' => 'All Assignments',
      'create' => 
      array (
        'title' => 'Create Assignment',
        'assignment_title' => 'Assignment Title',
        'content' => 'Content',
        'document' => 'Document',
        'select_course' => 'Select a Course',
        'total_marks' => 'Total Marks',
      ),
      'title' => 'Assignments',
      'new' => 'New Assignments',
      'edit' => 
      array (
        'title' => 'Edit Assignment',
        'assignment_title' => 'Assignment Title',
        'content' => 'Content',
        'attached_document' => 'Attached Document',
        'attached_document_note' => 'Click to See Attached Document.',
        'document' => 'Document',
        'choose_file' => 'Choose ...',
        'pdf_note' => 'PDF for Doc file (Max 5MB).',
        'information' => 'Information',
        'course' => 'Course',
        'select_course' => 'Select a Course',
      ),
      'select_course' => 'Select a Course',
      'published' => 'Published to Students',
      'achived' => 'Deleted Assignments',
      'result' => 
      array (
        'title' => 'Review Assignment Submitted',
        'submitted_content' => 'Submitted Content',
        'attached_document' => 'Attached Document',
        'see_attach_note' => 'Click to See Attached Document.',
        'assignment_mark' => 'Assignment Mark',
        'summary' => 'Summary',
        'attach' => 'Attachment',
      ),
      'teacher' => 
      array (
        'title' => 'Assignment Submitted',
        'marked' => 'Marked',
        'marked_assignments' => 'Marked Assignments',
      ),
      'all' => 'All Assignments',
    ),
    'general' => 
    array (
      'marked' => 'Marked',
      'all' => 'All',
      'achieved' => 'Achieved',
      'next' => 'Next',
      'prev' => 'Prev',
      'course' => 'Course',
      'student' => 'Student',
      'new' => 'Add New',
      'your_reply' => 'Your reply',
      'lesson' => 'Lesson',
      'tags' => 'Tags',
      'newest' => 'Newest',
      'choose_file' => 'Choose File',
      'save_changes' => 'Save Changes',
      'pdf_note_5m' => 'PDF for Doc file (Max 5MB).',
      'information' => 'Information',
      'lessons' => 'Lessons',
      'due_date' => 'Due Date',
      'pending' => 'Pending',
      'draft_saved' => 'Draft Saved',
      'published' => 'Published',
      'choose_file_note' => 'Max file size is 5MB.',
      'no_rating' => 'No Rating',
      'group' => 'Group',
      'private' => 'Private',
      'session_time' => 'Session Time',
      'sessions' => 'Sessions',
      'category' => 'Category',
      'level' => 'Level',
      'draft' => 'Draft',
    ),
    'my_quizzes' => 
    array (
      'title' => 'My Quizzes',
      'all' => 'All Quizzes',
      'achieved' => 'Achieved Quizzes',
    ),
    'my_tests' => 
    array (
      'title' => 'My Tests',
      'all' => 'All Tests',
    ),
    'result' => 
    array (
      'course_performance' => 
      array (
        'title' => 'Course Performance',
      ),
      'courses' => 'Courses',
    ),
    'certificates' => 
    array (
      'title' => 'My Certifications',
      'no_result' => 'You have no Certificates',
    ),
    'results' => 
    array (
      'badges' => 
      array (
        'title' => 'My Badges',
        'no_results' => 'You have no badges',
      ),
      'performance_detail' => 
      array (
        'title' => 'Performance Detail',
      ),
    ),
    'discussions' => 
    array (
      'title' => 'My Discussions',
      'no_results' => 'You have no discussions',
      'ask_question' => 'Ask a question',
      'question_title' => 'Question Title',
      'question_details' => 'Question Details',
      'edit_notify' => 
      array (
        'title' => 'Notify me on email when someone replies to my question',
        'description' => 'If unchecked, you\'ll still receive notifications on our website.',
        'update_question' => 'Update Question',
        'pefore_post' => 'Before you post',
      ),
      'topics' => 
      array (
        'title' => 'All Topics',
        'my_topics' => 'My Topics',
        'newest' => 'Newest',
        'unread' => 'Unanswered',
        'new' => 'Ask a question',
        'no_result' => 'No Discussions',
        'question_title' => 'Question Title',
        'search_placeholder' => 'Your question ...',
        'question_details' => 
        array (
          'search_placeholder' => 'Describe your question in detail ...',
          'title' => 'Question Details',
        ),
      ),
    ),
    'discussion_detail' => 
    array (
      'back' => 'Back to Community',
      'comments' => 'Comments',
      'add' => 'Add Comment',
      'post' => 'Post Comment',
      'top_contributors' => 
      array (
        'description' => 'People who started the most discussions.',
        'title' => 'Top Contributors',
      ),
    ),
    'messages' => 
    array (
      'recent_chat' => 'Recent Chat',
      'search_placeholder' => 'Search Users',
    ),
    'pre_enrolled' => 
    array (
      'title' => 'Pre Enrolled',
      'send' => 'Send',
      'close' => 'Close',
      'no_result' => 'You have no pre enrolled students',
    ),
    'my_account' => 
    array (
      'title' => 'My Account',
      'personal_information' => 'Personal Information',
      'change_password' => 'Change Password',
      'banking' => 'Banking',
      'child_account' => 'Child Account',
      'your_photo' => 'Your Photo',
      'profile_name' => 'Profile Name',
      'headline' => 'Head Line',
      'about' => 'About You',
      'contact_information' => 'Contact Information',
      'email_address' => 'Email Address',
      'phone_number' => 'Phone Number',
      'country' => 'Country',
      'state' => 'State',
      'city' => 'City',
      'zip_code' => 'Zip Code',
      'address' => 'Address',
      'timezone' => 'Timezone',
      'profession' => 'Profession',
      'achievement' => 'Achievement',
      'experience' => 'Experience',
      'profession_certification' => 'Professional Qualifications and Certifications',
      'current_password' => 'Current Password',
      'current_password_placeholder' => 'Type Current Password ...',
      'new_password' => 'New Password',
      'new_password_placeholder' => 'Type New Password ...',
      'confirm_password' => 'Confirm Password',
      'confirm_password_placeholder' => 'Type Confirm Password ...',
      'save_password' => 'Save Password',
      'payment_type' => 'Payment Type',
      'bank_detail' => 'Bank Details',
      'link_account' => 'Link Account (Razorpay)',
      'account_number' => 'Account Number',
      'account_number_placeholder' => 'Bank Account Number',
      'ifsc' => 'IFSC',
      'ifsc_placeholder' => 'IFSC',
      'beneficiary_name' => 'Beneficiary Name',
      'beneficiary_name_placeholder' => 'Beneficiary Name',
      'account_type' => 'Account Type',
      'account_type_placeholder' => 'Account Type',
      'add_child_account' => 
      array (
        'title' => 'Add Child account?',
        'description' => 'If checked then you can add child account',
      ),
      'child_name' => 'Child Name',
      'child_nick_name' => 'Child Nick Name',
      'password' => 'Password',
      'parent_phone_number' => 'Parent Phone Number',
      'send_otp' => 'Send OTP',
      'enter_otp' => 'Enter OTP',
      'upload_parent_id' => 'Upload Parent ID',
      'upload_parent_description' => 'Upload a clear ID in png, jpeg or PDF format',
      'relationship_to_child' => 'Relationship to child',
      'create_child_account' => 'Create Child Account',
    ),
    'payment' => 
    array (
      'orders' => 
      array (
        'title' => 'Orders',
        'payment_history' => 'Payment History',
      ),
      'table' => 
      array (
        'order_id' => 'Order Id',
        'amount' => 'Amount',
      ),
      'order_detail' => 
      array (
        'title' => 'Order Details',
        'download_invoice' => 'Download Invoice',
        'refund_requested' => 'Refund Requested',
        'refund' => 'Refund',
        'refund_request' => 'Refund Request',
        'payment_detail' => 'Payment Detail',
        'order_id' => 'Order ID',
        'payment_date' => 'Payment Date',
        'amount' => 'Amount',
        'tax' => 'Tax',
        'total' => 'Total',
        'order_items' => 'Order Items',
        'refund_money' => 'Refund Money',
        'reason' => 'Reason',
      ),
    ),
    'genearl' => 
    array (
      'all' => 'All',
    ),
    'meta' => 
    array (
      'meta_information' => 'Meta Information',
      'title' => 'Meta Title',
      'description' => 'Meta Description',
      'fields' => 
      array (
        'description' => 'Meta Description',
      ),
      'description_note' => 'Meta description for SEO purpose.',
    ),
    'sidebar' => 
    array (
      'category' => 'Category',
      'no_category' => 'No Category',
      'select_category' => 'Select a Category',
      'tags' => 'Tags',
      'select_tags' => 'Select one or more tags.',
      'options' => 'Options',
      'group_course' => 'Group Course',
      'min_students' => 'Min Students',
      'max_students' => 'Max Students',
      'group_note' => 'Number of Students for Group',
      'price' => 'Price',
      'price_note' => 'Price for Group course.',
      'private_course' => 'Private Course',
      'private_course_note' => 'Price for Private course.',
      'thumb' => 'Thumbnail',
      'intro_video' => 'Introduction Video',
      'url' => 'URL',
      'url_placeholder' => 'Enter Video URL',
      'url_note' => 'Enter a valid video URL.',
      'level' => 'Level',
      'select_level' => 'Select a level',
      'time_setting' => 'Time Setting',
      'start_date' => 'Start Date',
      'end_date' => 'End Date',
      'date_note' => 'Course will start and end date',
      'timezone' => 'Your Timezone',
      'select_timezone' => 'Select Timezone',
      'repeat' => 'Repeat',
      'week' => 'Week',
      'month' => 'Month',
      'download_file' => 'Download File',
    ),
    'lesson' => 
    array (
      'fields' => 
      array (
        'title' => 'Title',
      ),
      'description' => 'Description',
      'add_step' => 'Add Step',
      'video_section' => 'Video Section',
      'text_section' => 'Full Text Section',
    ),
  ),
  'general' => 
  array (
    'back' => 'Back',
  ),
  'auth' => 
  array (
    'login' => 
    array (
      'title' => 'Login To Account',
      'email' => 'E-mail',
      'email_placeholder' => 'Your email address ...',
      'password' => 'Password',
      'forgot_password' => 'Forgot your password?',
      'login_button' => 'Login',
      'sign_with' => 'or sign-in with',
    ),
    'register' => 
    array (
      'title' => 'Sign Up As',
      'first_last_name' => 'Your first and last name',
      'your_email' => 'Your Email',
      'first_last_name_placeholder' => 'Your first and last name ...',
      'your_email_placeholder' => 'Your email address ...',
      'your_timezone' => 'Your Timezone',
      'password' => 'Password',
      'password_placeholder' => 'Your Password ...',
      'confirm_password' => 'Confirm Password',
      'confirm_password_placeholder' => 'Confirm password ...',
      'become_instructor' => 'Become An Instructor',
      'instructor_rules' => 'Instructor Rules',
      'start_with_course' => 'Start With Courses',
    ),
    'verify' => 
    array (
      'thankyou' => 'Thank You for Registration',
    ),
  ),
  'social' => 
  array (
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'google_plus' => 'Google+',
  ),
);
