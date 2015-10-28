<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
     
    @section('content_header')  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('page_name', '')
        <small>@yield('page_description', '')</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="@yield('page_icon', 'fa fa-dashboard')"></i> {{ trans('home') }}</a></li>
        <li class="active">@yield('page_name', '')</li>
      </ol>
    </section>
    <!-- /Content Header (Page header) -->
    @show

    <!-- Main content -->
    <section class="content">

      @yield('content', '')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->