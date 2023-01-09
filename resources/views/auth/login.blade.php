<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/css/style_login.css') }}">

    <style>
        @media (min-width: 768px){
            #welocme{
                padding: 60px !important;
            }
        }
    </style>
</head>

<body>



    @if(session('message'))
        <div class="alert alert-info" role="alert">
            {{ session('message') }}
        </div>
    @endif
    <div class="row" style="background: white;
                            box-shadow: 0 14px 28px rgb(0 0 0 / 25%), 0 10px 10px rgb(0 0 0 / 22%);
                            border-radius: 10px;">
        <div id="welocme" class="col-md-6" style="color: white;
                                    text-align: center;
                                    padding: 10px 35px;
                                    background: linear-gradient(to right, #000046, #1CB5E0);
                                    border-radius: 10px 0 0 10px ;
                                    background-repeat: no-repeat;
                                    background-size: cover;
                                    background-position: 0 0;">
            @php
                $sett = \App\Models\GeneralSetting::first();
            @endphp
            <img src="{{$sett->logo ? $sett->logo->getUrl()  : ''}}" width="70" height="70" alt="" style="margin:10px">
            <h1>Welcome, Back!</h1>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('login') }}" style="padding: 50px;">
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
    </div>
</body>

<footer>
    <small>
        <b>@ 2022</b> | All Rights Reserved.
    </small>
    Powerd By <a href="https://ebtekarstore.com"><b>Ebtekar Store</b></a>
</footer>

</html>
