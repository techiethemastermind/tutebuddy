    <div class="js-fix-footer2 bg-white border-top-2">
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