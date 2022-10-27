<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CKSTJV | CDCS-DC</title>
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
   
</head>
<body>
	{{-- <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4" style="margin-top: 45px">
                <h4>CDCS Login</h4><hr>
                <form action="{{ route('cdcs.check') }}" method="post">
                    @if (Session::get('fail'))
                        <div class="alert alert-danger">
                            {{ Session::get('fail') }}
                        </div>
                    @endif
                    @csrf
                    <div class="form-group">
                        <label for="loginname">Login Name</label>
                        <input type="text" class="form-control" name="loginname" placeholder="Enter login name" value="{{old('loginname')}}">
                        <span class="text-danger">@error('loginname') {{ $message }} @enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter password" value="{{ old('password') }}">
                        <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                </form>
            </div>
        </div>
    </div>  --}}
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Section -->
        <section class="pt-1 pb-1" data-bg-image="{{ asset('assets/images/pages/pexels-messala-ciulla-1162644.jpg') }}">
            <div class="container-fluid d-flex flex-column">
                <div class="row align-items-center min-vh-100">
                    <div class="col-md-6 col-lg-4 col-xl-3 mx-auto">
                          <div class="card">
                            <div class="card-body py-5 px-sm-5">
                                <div class="mb-5 text-center">
                                    <h6 class="h3 mb-1">Login</h6>
                                    <p class="text-muted mb-0">Sign in to your account to continue.</p>
                                </div><span class="clearfix"></span>
                                <form action="{{ route('cdcs.check') }}" method="post">
                                    @if (Session::get('fail'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('fail') }}
                                        </div>
                                    @endif
                                    @csrf
                                    <div class="form-group">
                                        <label for="loginname">Login Name</label>
                                        <div class="input-group">
                                            <input type="text" autocomplete="off" class="form-control" name="loginname" placeholder="Enter your login name" value="{{old('loginname')}}">
                                            <span class="text-danger">@error('loginname') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="password">Password</label>
                                        <div class="input-group show-hide-password">
                                            <input autocomplete="off" class="form-control" name="password" placeholder="Enter password" type="password" value="{{old('loginname')}}">
                                            <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="mt-4"><button type="submit" class="btn btn-primary btn-block btn-primary">Sign in</button></div>
                                </form>
                                <br>
                                {{-- <div class="mt-4 text-center"><small>Not registered?</small> <a href="page-user-register.html" class="small fw-bold">Create account</a> --}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end: Section -->
    </div>
    <!-- end: Body Inner -->
    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
    <!--Plugins-->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <!--Template functions-->
    <script src="{{ asset('assets/js/functions.js') }}"></script>
</body>
</html>