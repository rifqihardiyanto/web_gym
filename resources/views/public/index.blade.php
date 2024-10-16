<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Bespoke - Creative One Page HTML5 Template</title>

    <!-- ALL CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/owl.theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/flaticon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/settings.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/preset.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/responsive.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    

    <!--[if lt IE 9]>
              <script src="js/html5shiv.js"></script>
              <script src="js/respond.min.js"></script>
          <![endif]-->

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ asset('public/images/favicon.png') }}">
    <!-- Favicon Icon -->
</head>

<body>

    <!--PRELOADER START-->
    <div class="preloader">
        <div class="loader">
            <img src="images/loader.gif" alt="">
        </div>
    </div>
    <!--PRELOADER END-->

    <!--SLIDER START-->
    <section class="slider" id="slider">
        <div class="tp-banner">
            <ul>
                <li data-transition="cube" data-slotamount="7" data-masterspeed="1000">
                    <img src="{{ asset('public/images/slider/s1.jpg') }}" alt="">
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="-40"
                        data-speed="1600" data-start="1000" data-easing="easeInOutCubic">
                        <div class="revCon">
                            <h5 class="text-uppercase color_white">we do nothing less than perfect</h5>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="35"
                        data-speed="2000" data-start="1500" data-easing="Power4.easeOut">
                        <div class="revCon">
                            <h2 class="lead color_white">PRASASTI GYM.</h2>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="148"
                        data-speed="2000" data-start="2000" data-easing="Power4.easeOut">
                        <div class="revCon revBtn">
                            <a href="#" class="bes_button">Learn more about us <i
                                    class="flaticon-arrows"></i></a>
                        </div>
                    </div>
                </li>
                <li data-transition="cube" data-slotamount="7" data-masterspeed="1000">
                    <img src="images/slider/s2.jpg" alt="">
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="-40"
                        data-speed="1600" data-start="1000" data-easing="easeInOutCubic">
                        <div class="revCon">
                            <h5 class="text-uppercase color_white">we do nothing less than perfect</h5>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="35"
                        data-speed="2000" data-start="1500" data-easing="Power4.easeOut">
                        <div class="revCon">
                            <h2 class="lead color_white">Adventure starts here.</h2>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="148"
                        data-speed="2000" data-start="2000" data-easing="Power4.easeOut">
                        <div class="revCon revBtn">
                            <a href="#" class="bes_button">Learn more about us <i
                                    class="flaticon-arrows"></i></a>
                        </div>
                    </div>
                </li>
                <li data-transition="cube" data-slotamount="7" data-masterspeed="1000">
                    <img src="images/slider/s3.jpg" alt="">
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="-40"
                        data-speed="1600" data-start="1000" data-easing="easeInOutCubic">
                        <div class="revCon">
                            <h5 class="text-uppercase color_white">we do nothing less than perfect</h5>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="35"
                        data-speed="2000" data-start="1500" data-easing="Power4.easeOut">
                        <div class="revCon">
                            <h2 class="lead color_white">Adventure starts here.</h2>
                        </div>
                    </div>
                    <div class="tp-caption sfb" data-x="center" data-y="center" data-hoffset="0" data-voffset="148"
                        data-speed="2000" data-start="2000" data-easing="Power4.easeOut">
                        <div class="revCon revBtn">
                            <a href="#" class="bes_button">Learn more about us <i
                                    class="flaticon-arrows"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="mouseSlider">
            <a href="#team" class="normal"><img src="images/mouse.png" alt=""></a>
            <a href="#team" class="hover"><img src="images/mouseh.png" alt=""></a>
        </div>
    </section>
    <!--SLIDER END-->

    <!--COPY RIGHT START-->
    <section class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="copyPera">&COPY; 2017 Bespoke. All Rights Reserved. Nasfactor.com</p>
                </div>
            </div>
        </div>
    </section>
    <!--COPY RIGHT END-->

    <div class="subscriptionSuccess">
        <div class="subsNotice">
            <i class="fa fa-thumbs-o-up closers"></i>
            <div class="clearfix"></div>
            <p class="closers">Subscription Request Successfully placed!</p>
        </div>
    </div>
    <div class="contactSuccess">
        <div class="consNotice">
            <i class="fa fa-thumbs-o-up closers"></i>
            <div class="clearfix"></div>
            <p class="closers">Your Message successfully sent!</p>
        </div>
    </div>

    <a id="backToTop" href="#"><i class="fa fa-angle-double-up"></i></a>

    <!-- ALL JS -->
    <script type="text/javascript" src="{{ asset('public/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/owl.carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/slick.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/jquery.themepunch.tools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/theme.js') }}"></script>
</body>

</html>
