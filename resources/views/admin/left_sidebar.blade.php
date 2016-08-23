<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      @section('sidebar_user')
      <!-- Sidebar user panel (optional) DISABLED -->
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
      <!-- /Sidebar user panel (optional) DISABLED -->
      @endsection

      @section('sidebar_search')
      <!-- search form (Optional) DISABLED -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form DISABLED -->
      @endsection

      @section('sidebar_menu')
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">{{ strtoupper(trans('admin.menu_header')) }}</li>
        
        @foreach (config('admin.menu') as $item)
            @if(empty($item['items']))
            <li class="@yield($item['class'])"><a href="{{ route($item['route']) }}"><i class="{{ $item['icon'] }}"></i> <span>{{ trans($item['name']) }}</span></a></li>
            @else
            <li class="treeview @yield($item['class'])">
                <a href="#"><i class="{{ $item['icon'] }}"></i> <span>{{ trans($item['name']) }}</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @foreach($item['items'] as $subitem)
                    <li class="@yield($subitem['class'])"><a href="{{ route($subitem['route']) }}"><i class="{{ $subitem['icon'] }}"></i> {{ trans($subitem['name']) }}</a></li>
                    @endforeach
                </ul>
            </li>
            @endif
        @endforeach
        
      </ul>
      <!-- /.sidebar-menu -->
      @show
      
      
    </section>
    <!-- /.sidebar -->
  </aside>
