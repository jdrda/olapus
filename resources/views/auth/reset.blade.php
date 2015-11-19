<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ env('APP_NAME') }} | {{ trans('passwords.reset_password') }}</title>
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
      <p class="login-box-msg">{{ trans('passwords.enter_new_password') }}</p>

      @include('admin.errors')
      
      <form action="{{ route('authPasswordPostReset') }}" method="post">
      {!! csrf_field() !!}
      <input type="hidden" name="token" value="{{ $token }}">
      
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="{{ trans('passwords.email') }}" value="{{ old('email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('passwords.password') }}" id="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
      <div class="form-group has-feedback">
        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('passwords.confirm_password') }}" id="confirm_password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
      @if(env('RECAPTCHA_ENABLED') == 1)
      <div class='form-group text-center'>
      {!! Recaptcha::render() !!}
      </div>
      @endif
     
         
      <div class="row">
        <div class="col-xs-6">
         &nbsp
        </div>
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('passwords.reset_password') }}</button>
        </div>
      </div>

    </form>

    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- JS Scripts -->
<script src="{{ asset(elixir('js/admin.js')) }}"></script>
<!-- /JS Scripts -->

</body>
</html>
