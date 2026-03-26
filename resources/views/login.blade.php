<!DOCTYPE html>
<html lang="fa"  data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

    <head>

        <!-- Meta Data -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="Description" content="فابریکه ریخت المونیم اطلس">
        <meta name="Author" content="فابریکه ریخت المونیم اطلس">
        <meta name="keywords" content="فابریکه ریخت المونیم اطلس">
        
        <!-- Title -->
        <title>فابریکه ریخت المونیم اطلس </title>

		<!-- Favicon -->
        <link rel="icon" href="{{ asset('assets\logo 2.png') }}" type="image/x-icon">
       
        <!-- styles code -->
                <!-- Main Theme Js -->
        <script src="{{ asset('assets\js\authentication-main.js') }}"></script>

        <!-- Bootstrap Css -->
        <link id="style" href="{{ asset('assets\libs\bootstrap\css\bootstrap.rtl.min.css') }}" rel="stylesheet">

        <!-- Style Css -->
        <link href="{{ asset('assets\css\styles.css') }}" rel="stylesheet">

        <!-- Icons Css -->
        <link href="{{ asset('assets\css\icons.css') }}" rel="stylesheet">

        


        <!-- End styles -->

    </head>    
    
    <body class="bg-white">

 
        	
            <div class="row authentication authentication-cover-main mx-0">
                <div class="col-xxl-6 col-xl-7">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-xxl-6 col-xl-9 col-lg-6 col-md-6 col-sm-8 col-12">
                            <div class="card custom-card my-5 auth-circle">
                                <div class="card-body p-sm-5 p-4 m-1 m-sm-0">
                                    <p class="h4 mb-2 fw-semibold"> ورود</p>
                                        <form method="POST" action="{{ route('login.submit') }}" class="row g-3 needs-validation" novalidate>
                                            @csrf
                                            <div class="row gy-3">
                                                <div class="col-xl-12">
                                                    <label for="signup-firstname" class="form-label text-default">آدرس ایمیل</label>
                                                    <input type="email" name="email" class="form-control" id="signup-firstname" placeholder="ایمیل خود را وارد کنید" required>
                                                    @error('email')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-xl-12">
                                                    <label for="signup-password" class="form-label text-default">رمز عبور</label>
                                                    <div class="position-relative">
                                                        <input type="password" name="password" class="form-control" id="signup-password" placeholder="رمز عبور" required>
                                                        <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('signup-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></a>
                                                        @error('password')
                                                            <p class="text-danger mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="d-grid mt-4">
                                                    <button type="submit" class="btn btn-primary">ورود</button>
                                                </div>
                                            </div>
                                        </form>





                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-5 col-lg-12 d-xl-block d-none px-0">
                    <div class="authentication-cover overflow-hidden">
                        <div class="authentication-cover-logo"> 
                        </div>
                        <div class="d-flex align-items-center justify-content-center p-3 rounded m-5">
                            <div class="p-3">
                                <a href="index.html"> 
                                    <img src="assets\logo 2.png" style="width: 100px;" alt="" class="authentication-brand toggle-dark"> 
                                </a> 
                                <h2 class="text-fixed-white lh-base fw-semibold mt-4">فابریکه ریخت المونیم اطلس</h2>
                                <p class="mb-0 fs-16 lh-base text-fixed-white op-8">خوش آمدید</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


         

        <!-- Start::main-scripts -->
                <!-- Bootstrap JS -->
        <script src="{{ asset('assets\libs\bootstrap\js\bootstrap.bundle.min.js') }}"></script>

        	
        <!-- Show Password JS -->
        <script src="{{ asset('assets\js\show-password.js') }}"></script>

        <!-- End::main-scripts -->

    </body>

</html>