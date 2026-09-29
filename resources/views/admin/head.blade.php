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

<script>
  (() => {
    const theme = localStorage.getItem('olapus-theme');

    if (theme === 'dark' || theme === 'light') {
      document.documentElement.setAttribute('data-bs-theme', theme);
    }
  })();
</script>

@vite([
    'resources/css/adminlte.css',
    'resources/js/adminlte.js',
])

@yield('google_charts')

@stack('styles')

@yield('css_styles')