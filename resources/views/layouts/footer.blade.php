    <div class="js-fix-footer2 bg-white border-top-2 position-relative">
        <div class="container page__container page-section d-flex flex-column">
            <div class="row pb-16pt mb-16pt border-bottom-2">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <a href="{{ config('app.url') }}" class="">
                        <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                            <img src="@if(!empty(config('sidebar_logo'))) 
                                    {{ asset('storage/logos/'.config('sidebar_logo')) }}
                                @else 
                                    {{ asset('assets/img/logo/tutebuddy-menu-logo.png') }}
                                @endif" alt="logo" class="img-fluid" />
                        </span>
                    </a>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <ul class="footer-menu">
                        <li class="footer-menu-item">
                            <a href="#" >About Us</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Support</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >FAQs</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <ul class="footer-menu">
                        <li class="footer-menu-item">
                            <a href="/page/how-it-works" >How It Works</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Teach on TuteBuddy</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Solutions for Business</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Solutions for Institutions</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <ul class="footer-menu">
                        <li class="footer-menu-item">
                            <a href="/page/terms-and-conditions" >Terms of Service</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="/page/privacy-policy" >Privacy Policy</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Cookies</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Student Safety</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="row">
                <div class="col-12 pt-2">
                    <p class="text-50 small mt-n1 mb-0">Copyright 2019 &copy; All rights reserved.</p>
                </div>
            </div>            
        </div>
    </div>
</div>

@push('after-scripts')

<script>
$(function() {

    var search_ele;
    var search_type = 'course';
    var reload_href = '{{ config("app.url") }}' + 'search/courses?_q=';

    $('.search-form input[type="text"]').on('focus', function(e) {
        search_type = $(this).attr('search-type');
        reload_href = (search_type == 'course') ? '{{ config("app.url") }}' + 'search/courses?_q=' : '{{ config("app.url") }}' + 'search/instructors?_q=';
    });

    $('.search-form input[type="text"]').on('keyup', function(e) {

        search_ele = $(this).closest('.search-form');
        var key = $(this).val();
        if (e.which == 13) {
            location.href = reload_href + key;
        } else if(e.which == 40 || e.which == 38) {

            var active_li = $(document).find('#search___result').find('li.active');
            
            if(active_li.length == 0) {
                if(e.which == 40) {
                    active_li = $(document).find('#search___result').find('li').first();
                }
                if(e.which == 38) {
                    active_li = $(document).find('#search___result').find('li').last();
                }
                active_li.addClass('active');
                $(this).val($.trim(active_li.text()));
            } else {
                
                if(e.which == 40) {
                    next_li = active_li.next();
                    if(next_li.length != 0) {
                        active_li.removeClass('active');
                        next_li.addClass('active');
                        $(this).val($.trim(next_li.text()));
                    }
                }

                if(e.which == 38) {
                    prev_li = active_li.prev();
                    if(prev_li.length != 0) {
                        active_li.removeClass('active');
                        prev_li.addClass('active');
                        $(this).val($.trim(prev_li.text()));
                    }
                }
            }            

        } else {
            if (key.length > 1) {
                send_ajax(key);
            } else {
                $(document).find('#search___result').remove();
            }
        }

    });

    $(document).on('click', '#search___result li', function() {
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var name = $(this).text();

        $('#search_homepage').val(name);
        $(document).find('#search___result').remove();

        location.href = reload_href + name + '&_t=' + type + '&_k=' + id;
    });

    function send_ajax(key) {

        var route = (search_type == 'course') ? '/ajax/search/courses/' + key : '/ajax/search/users/' + key;

        $.ajax({
            method: 'get',
            url: route,
            success: function(res) {
                if (res.success) {
                    var rlt = $(document).find('#search___result');
                    if (rlt.length > 0) {
                        rlt.remove();
                    }

                    $(res.html).insertAfter(search_ele);
                }
            }
        })
    }
});
</script>

@endpush