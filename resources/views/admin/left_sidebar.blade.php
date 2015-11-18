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
        <!-- Optionally, you can add icons to the links -->
        <li class="@yield('menu-class-dashboard')"><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        
        <li class="treeview @yield('menu-class-pages')">
          <a href="#"><i class="fa fa-sticky-note-o"></i> <span>{{ trans('admin.menu_group_pages') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="@yield('menu-class-page')"><a href="{{ route('admin.page.index') }}"><i class="fa fa-sticky-note-o"></i> {{ trans('admin_module_page.name') }}</a></li>
            <li class="@yield('menu-class-pagecategory')"><a href="{{ route('admin.pageCategory.index') }}"><i class="fa fa-object-group"></i> {{ trans('admin_module_pagecategory.name') }}</a></li>
          </ul>
        </li>
        
        <li class="treeview @yield('menu-class-articles')">
          <a href="#"><i class="fa fa-newspaper-o"></i> <span>{{ trans('admin.menu_group_articles') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="@yield('menu-class-article')"><a href="{{ route('admin.article.index') }}"><i class="fa fa-newspaper-o"></i> {{ trans('admin_module_article.name') }}</a></li>
            <li class="@yield('menu-class-articlecategory')"><a href="{{ route('admin.articleCategory.index') }}"><i class="fa fa-clone"></i> {{ trans('admin_module_articlecategory.name') }}</a></li>
          </ul>
        </li>
        
        <li class="treeview @yield('menu-class-sliders')">
          <a href="#"><i class="fa fa-sliders"></i> <span>{{ trans('admin.menu_group_sliders') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="@yield('menu-class-slider')"><a href="{{ route('admin.slider.index') }}"><i class="fa fa-sliders"></i> {{ trans('admin_module_slider.name') }}</a></li>
            <li class="@yield('menu-class-slide')"><a href="{{ route('admin.slide.index') }}"><i class="fa fa-slideshare"></i> {{ trans('admin_module_slide.name') }}</a></li>
          </ul>
        </li>
        <li class="treeview @yield('menu-class-media')">
          <a href="#"><i class="fa fa-camera-retro"></i> <span>{{ trans('admin.menu_group_media') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="@yield('menu-class-image')"><a href="{{ route('admin.image.index') }}"><i class="fa fa-image"></i> {{ trans('admin_module_image.name') }}</a></li>
            <li class="@yield('menu-class-imagecategory')"><a href="{{ route('admin.imageCategory.index') }}"><i class="fa fa-file-image-o"></i> {{ trans('admin_module_imagecategory.name') }}</a></li>
          </ul>
        </li>
        <li class="treeview @yield('menu-class-administration')">
          <a href="#"><i class="fa fa-gears"></i> <span>{{ trans('admin.menu_group_administration') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li class="@yield('menu-class-settings')"><a href="{{ route('admin.settings.index') }}"><i class="fa fa-gear"></i> {{ trans('admin_module_settings.name') }}</a></li>
            <li class="@yield('menu-class-user')"><a href="{{ route('admin.user.index') }}"><i class="fa fa-user"></i> {{ trans('admin_module_user.name') }}</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
      @show
      
    </section>
    <!-- /.sidebar -->
  </aside>