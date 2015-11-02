<!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      @yield('right_footer', '')
    </div>
    <!-- Default to the left -->
    @section('copyright')
    <strong>{{ trans('admin.copyright') }} &copy; {{ date('Y') }} <a href="{{ env('VENDOR_COMPANY_NAME') }}">{{ env('VENDOR_COMPANY_NAME') }}</a>.</strong> {{ trans('admin.all_rights_reserved') }}. {{ trans('admin.developed_on') }} <a href="https://www.olapus.com?version={{ config('app.version') }}" rel="nofollow" target="_blank">Olapus</a>. 
    @show
  </footer>