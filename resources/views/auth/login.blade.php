<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/css/style_login.css') }}">

</head>

<body>



    <div class="container" id="container">
        @if(session('message'))
            <div class="alert alert-info" role="alert">
                {{ session('message') }}
            </div>
        @endif
        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Sign in</h1> 
                <input id="email" name="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="Email" value="{{ old('email', null) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <input id="password" name="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="Password">

                <div style="display: flex;align-items: center">
                    <div style="display: flex;width:13px;margin:0 7px">
                        <input class="" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                    </div>
                    <label class="" for="remember" style="font-size:13px;display:flex">
                        <span>Remember </span>
                        <span>&nbsp; Me</span>
                    </label>
                </div> 

                <button type="submit">Sign In</button>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                @if(Route::has('password.request'))
                    <a class="" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif 
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1>Welcome, Back!</h1>
                    <p>{{ \App\Models\GeneralSetting::first()->website_title ?? ''}}</p>

                </div>
            </div>
        </div>
    </div>

</body>

<footer>
    <small>
        <b>@ 2022</b> | All Rights Reserved. 
    </small>
    Powerd By <a href="https://ebtekarstore.com"><b>Ebtekar Store</b></a>
</footer>

</html> 