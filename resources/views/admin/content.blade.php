<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
     
    @section('content_header')  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('page-name', '')
        <small>@yield('page-description', '')</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="@yield('page-icon', 'fa fa-home')"></i> {{ trans('admin.home') }}</a></li>
        <li class="active">@yield('page-name', '')</li>
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