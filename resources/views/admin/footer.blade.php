<!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      @yield('right_footer', '')
    </div>
    <!-- Default to the left -->
    @section('copyright')
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">Company</a>.</strong> All rights reserved.
    @show
  </footer>