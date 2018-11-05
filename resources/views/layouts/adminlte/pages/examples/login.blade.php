<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>StoreLK | Cabinet </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Store</b>LK</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Введи логин и пароль для входа в личный кабинет</p>
    <form action="{{ route('login') }}" method="post">
      @csrf
      <div class="form-group {{ $errors->has('name') ? ' has-error' : 'has-feedback' }}">
        <input type="text" class="form-control" placeholder="Login" name="name" value="{{ old('name') }}" >
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('name'))
          <span class="help-block" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
          </span>
        @endif
      </div>
      <div class="form-group {{ $errors->has('password') ? ' has-error' : 'has-feedback' }}">
        <input type="password" class="form-control" placeholder="Password" name="password">
        @if ($errors->has('password'))
          <span class="help-block" role="alert">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
        @endif
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            {{--<label>--}}
              {{--<input type="checkbox"> Remember Me--}}
            {{--</label>--}}
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    {{--<div class="social-auth-links text-center">--}}
      {{--<p>- OR -</p>--}}
      {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using--}}
        {{--Facebook</a>--}}
      {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using--}}
        {{--Google+</a>--}}
    {{--</div>--}}
    <!-- /.social-auth-links -->

    {{--<a href="#">I forgot my password</a><br>--}}
    {{--<a href="register.html" class="text-center">Register a new membership</a>--}}

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="{{ asset('js/app.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
