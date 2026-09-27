@section('meta_information')
  <meta charset="utf-8">

  <meta name="viewport"
        content="width=device-width, initial-scale=1">

  <title>
    {{ config('app.name') }}
    @hasSection('page-name')
      | @yield('page-name')
    @endif
  </title>
@show

@vite([
    'resources/css/adminlte.css',
    'resources/js/adminlte.js',
])

@yield('google_charts')

@stack('styles')

@yield('css_styles')