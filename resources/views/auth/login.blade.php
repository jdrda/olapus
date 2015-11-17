<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ env('APP_NAME') }} | {{ trans('login.sign_in') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- CSS styles -->
  <link rel="stylesheet" href="{{ asset(elixir('css/admin.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/admin/iCheck/skins/square/blue.css') }}">
  <!-- CSS styles -->
  
  @section('html5_workaround')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset(elixir('js/html5workaround.js')) }}"></script>
    <![endif]-->
  @endsection
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
      <a href="{{ route('authPostLogin') }}"><b>Admin</b>{{ env('APP_NAME') }}</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
      <p class="login-box-msg">{{ trans('login.headline') }}</p>

      @include('admin.errors')
      
      <form action="{{ route('authPostLogin') }}" method="post">
      {!! csrf_field() !!}
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="{{ trans('login.email') }}" value="{{ old('email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('login.password') }}" id="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
     
         
      <div class="row">
        <div class="col-xs-8">
          <!-- Remember me --> 
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> {{ trans('login.remeber_me') }}
            </label>
          </div>
          <!-- /Remember me -->
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('login.sign_in') }}</button>
        </div>
      </div>

    </form>

    <!-- Social networks login NOT SUPPORTED YET -->
    <!--<div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>-->
    <!-- /Social networks login NOT SUPPORTED YET -->
    
    <!-- Lost Password NOT SUPPORTED YET -->
    <!--<a href="#">{{ trans('login.lost_password') }}</a>-->
    <!-- /Lost Password NOT SUPPORTED YET -->
    <!-- Registration NOT SUPPORTED YET -->
    <!--<br>
    <a href="register.html" class="text-center">Register a new membership</a>-->
    <!-- /Registration NOT SUPPORTED YET -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- JS Scripts -->
<script src="{{ asset(elixir('js/admin.js')) }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<!-- /JS Scripts -->

</body>
</html>
