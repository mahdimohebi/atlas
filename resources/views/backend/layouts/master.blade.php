<!DOCTYPE html>
<html lang="fa" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Atlas">
    <meta name="author" content="Atlas">
    <meta name="keywords" content="Atlas">

    <title>فابریکه ریخت المونیم اطلس</title>
    <link rel="icon" href="{{ asset('assets\logo 2.png') }}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.rtl.min.css') }}">
    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- Node Waves Css -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">
    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css') }}">
</head>

<body>
    @include('backend.layouts.setting')

    <div id="loader">
        <img src="{{ asset('assets/images/media/loader.svg') }}" alt="">
    </div>

    <div class="page">
        <!-- Start::main-header -->
        <header class="app-header sticky" id="header">
            <div class="main-header-container container-fluid">
                <div class="header-content-left">
                    <!-- Logo -->
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="index.html" class="header-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                                <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                                <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                            </a>
                        </div>
                    </div>
                    <!-- Sidebar toggle -->
                    <div class="header-element mx-lg-0 mx-2">
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link" data-bs-toggle="sidebar" href="javascript:void(0);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" d="M4 5h12M4 12h16M4 19h8"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn-close" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" d="m18 6l-6 6m0 0l-6 6m6-6l6 6m-6-6L6 6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <ul class="header-content-right">
                    <li class="header-element d-md-none d-block">
                        <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal"
                            data-bs-target="#header-responsive-search">
                            <i class="bi bi-search header-link-icon"></i>
                        </a>
                    </li>

                    <li class="header-element header-theme-mode">
                        <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em" height="1em"
                                    viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M21.5 14.078A8.557 8.557 0 0 1 9.922 2.5C5.668 3.497 2.5 7.315 2.5 11.873a9.627 9.627 0 0 0 9.627 9.627c4.558 0 8.376-3.168 9.373-7.422">
                                    </path>
                                </svg>
                            </span>
                            <span class="dark-layout">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em"
                                    height="1em" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 12a5 5 0 1 1-10 0a5 5 0 0 1 10 0M12 2v1.5m0 17V22m7.07-2.929l-1.06-1.06M5.99 5.989L4.928 4.93M22 12h-1.5m-17 0H2m17.071-7.071l-1.06 1.06M5.99 18.011l-1.06 1.06">
                                    </path>
                                </svg>
                            </span>
                        </a>
                    </li>

                    <li class="header-element dropdown">
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div>
                                    @if(Auth::check() && Auth::user()->image)
                                        <img src="{{ asset('storage/' . Auth::user()->image) }}" 
                                            alt="User Image" 
                                            class="avatar avatar-sm avatar-rounded">
                                    @else
                                        <img src="{{ asset('assets/images/faces/10.jpg') }}" 
                                            alt="Default User" 
                                            class="avatar avatar-sm avatar-rounded">
                                    @endif

                                </div>
                            </div>
                        </a>
                        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                            aria-labelledby="mainHeaderProfile">
                            <li class="p-3 border-bottom text-center">
                                <p class="mb-0 fw-semibold lh-1">
                                    @if(Auth::check() && Auth::user()->name)
                                        {{ Auth::user()->name .' '.Auth::user()->last_name }}
                                    @endif
                                </p>
                                <span class="fs-11 text-muted">
                                    @if(Auth::check() && Auth::user()->email)
                                        {{ Auth::user()->email }}
                                    @endif
                                </span>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{  route('user.index') }}">
                                    <i class="ri-user-line fs-15 me-2 text-gray fw-normal"></i> حساب کاربری
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-circle-line fs-15 me-2 text-gray fw-normal"></i> خروج
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="header-element">
                        <a href="javascript:void(0);" class="header-link switcher-icon" data-bs-toggle="offcanvas"
                            data-bs-target="#switcher-canvas">
                            <i class="ri-settings-3-line fs-18"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </header>
        <!-- End::main-header -->

        <aside class="app-sidebar sticky" id="sidebar">
            <div class="main-sidebar-header">
                <a href="index.html" class="header-logo">
                    <img src="{{ asset('assets/logo 2.png') }}" alt="logo" class="desktop-logo">
                    <img src="{{ asset('assets/logo 2.png') }}" alt="logo" class="toggle-dark">
                    <img src="{{ asset('assets/logo 2.png') }}" alt="logo" class="desktop-dark">
                    <img src="{{ asset('assets/logo 2.png') }}" alt="logo" class="toggle-logo">
                </a>
            </div>
            <div class="main-sidebar" id="sidebar-scroll">
                @include('backend.layouts.nav')
            </div>
        </aside>

        @yield('content')

        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted">
                    <span id="year"></span>
                    طراحی با <span class="bi bi-heart-fill text-danger"></span> توسط
                    <a href="" target="_blank"><span class="fw-medium text-primary">Mahdi Mohebi</span></a>
                </span>
            </div>
        </footer>
    </div>

    <div class="scrollToTop">
        <span class="arrow lh-1"><i class="ri-rocket-line align-middle fs-18"></i></span>
    </div>
    <div id="responsive-overlay"></div>

    <!-- JS Libraries -->
    
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- همیشه اول -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/defaultmenu.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/js/sticky.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>
<script src="{{ asset('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/custom-switcher.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



@if(Session::has('success'))
<script>
    $(function() {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: "{!! Session::get('success') !!}",
            showConfirmButton: false,
            timer: 1500
        });
    });
</script>
@endif

@yield('script')
</body>
</html>
