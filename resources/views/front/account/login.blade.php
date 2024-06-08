@extends('front.layouts.app');

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        @if(Session::has('success') || Session::has('error'))
             @include('sweetalert::alert'); 
        @endif
    
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Login</h1>
                    <form action="{{ route('account.authenicate') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" autocomplete="off" placeholder="example@example.com">
                                @if($errors->has('email'))
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                @endif
                        </div>
                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control" autocomplete="off" placeholder="Enter Password">
                                @if($errors->has('password'))
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
                        </div>
                        <div class="justify-content-between d-flex">
                            <button class="btn btn-primary mt-2">Login</button>
                            <a href="forgot-password.html" class="mt-3">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Do not have an account? <a href="{{ route('account.registration') }}">Register</a></p>
                </div>
            </div>
        </div>
        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>
@endsection

@section('customJs')
@if($errors->has('email'))
<script>
    $("#email").addClass("is-invalid");
</script>
@endif

@if($errors->has('password'))
<script>
    $("#password").addClass("is-invalid");
</script>
@endif
@endsection
