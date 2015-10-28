<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      @section('sidebar_user')
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('build/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- /Sidebar user panel (optional) -->
      @endsection

      @section('sidebar_search')
      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      @endsection

      @section('sidebar_menu')
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">{{ strtoupper(trans('admin.menu_header')) }}</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li><a href="{{ url('admin/article') }}"><i class="fa fa-newspaper-o"></i> <span>Articles</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-sliders"></i> <span>Sliders</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="{{ url('admin/slider') }}"><i class="fa fa-sliders"></i> Sliders</a></li>
            <li><a href="{{ url('admin/slide') }}"><i class="fa fa-slideshare"></i> Slides</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-gears"></i> <span>Administration</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
              <li><a href="{{ url('admin/category') }}"><i class="fa fa-clone"></i>Categories</a></li>
            <li><a href="{{ url('admin/settings') }}"><i class="fa fa-gear"></i> Settings</a></li>
            <li><a href="{{ url('admin/users') }}"><i class="fa fa-user"></i> Users</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
      @show
      
    </section>
    <!-- /.sidebar -->
  </aside>