@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-gear')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-administration', 'active')
@section('menu-class-settings', 'active')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    {{ trans($moduleNameBlade . '.name') }}
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
                        <form action="@if(isset($results->_method)){{ route($moduleBasicRoute . '.update', $results->id) }}@else{{ route($moduleBasicRoute . '.store') }}@endif" method="post">
                            {!! csrf_field() !!}
                            @if(isset($results->_method))
                            <input type="hidden" name="_method" value="{{ $results->_method }}">
                            @endif
                            <div class="form-group has-feedback">
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                                <input type="text" name="name" class="form-control" value="{{ $results->name or old('name') }}" required>
                                <span class="fa fa-key form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='value'>{{ trans($moduleNameBlade . '.fields.value') }}</label>
                                <input type="text" name="value" class="form-control" value="{{ $results->value or old('value') }}">
                                <span class="fa fa-paperclip form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='description'>{{ trans($moduleNameBlade . '.fields.description') }}</label>
                                <input type="text" name="description" class="form-control" value="{{ $results->description or old('description') }}">
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                            
                            <div class="form-group text-right">
                                <button type='submit' name='submit' class='btn btn-primary btn-flat'>{{ trans('admin.save') }}</button>
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
