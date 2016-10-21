
<!DOCTYPE html>
<!--
Admin master template
-->
<html>
<head>
  @include('admin.head')
</head>

<body class="hold-transition @yield('body-skin', 'skin-blue') @yield('body-style', 'fixed') sidebar-mini">
<div class="wrapper">

 
  @include('admin.header')
  
  @include('admin.left_sidebar')

  @include('admin.content')
  
  @include('admin.footer')

 
</div>
<!-- ./wrapper -->

@include('admin.foot')
</body>
</html>
