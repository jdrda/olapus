  @section('meta_information')
  <!-- Meta information -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ env('APP_NAME') }} | @yield('page-name', '')</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- /Meta information -->
  @show
  
  @section('pace')
  <script src="{{ asset(elixir('js/pace.js')) }}"></script>
  <link href="{{ asset(elixir('css/pace.css')) }}" rel="stylesheet" />
  @show
  
  @section('css_styles')
  <!-- CSS styles -->
  <link rel="stylesheet" href="{{ asset(elixir('css/admin.css')) }}">
  <!-- /CSS styles -->
  @show
  
  @section('google_charts')
  @show
  
  @section('html5_workaround')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="{{ asset(elixir('js/html5workaround.js')) }}"></script>
  <![endif]-->
  @show