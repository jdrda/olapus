@extends('admin.master')

@section('page-name', trans('admin_module_user.name') )

@section('page-icon', 'fa fa-user')

@section('page-description', trans('admin_module_user.description'))

@section('menu-class-administration', 'active')
@section('menu-class-user', 'active')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    {{ trans('admin_module_user.name') }}
                </h3>


                <div class="box-tools">


                    <!-- Search box DEACTIVATED -->
                    <!--<div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>-->
                    <!-- /Search box DEACTIVATED -->
                </div>

            </div>
            <!-- /.box-header -->
            
            <div class='box-body'>
                <div class='row'>
                    <div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>

                        @include('admin.errors')

                        <!-- Form -->
                        <form action="{{ url('admin/user') }}" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group has-feedback">
                                <label for='name'>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ isset($results->name) ? $results->name : old('name') }}">
                                <span class="fa fa-user form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='email'>E-mail</label>
                                <input type="email" name="email" class="form-control"value="{{ isset($results->email) ? $results->name : old('email') }}">
                                <span class="fa fa-envelope form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='password'>Password</label>
                                    <input type="password" name="password" class="form-control" id="password" value='{{ isset($results->password) ? '######' : '' }}'>
                                <span class="fa fa-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='password_confirmation'>Password again</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" value='{{ isset($results->password) ? '######' : '' }}'>
                                <span class="fa fa-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group text-right">
                                <button type='submit' name='submit' class='btn btn-primary btn-flat'>Save</button>
                            </div>
                        </form>
                        <!-- /Form -->
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection