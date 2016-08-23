@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-file-image-o')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-media', 'active')
@section('menu-class-imagecategory', 'active')

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
                                <label for='description'>{{ trans($moduleNameBlade . '.fields.description') }}</label>
                                <input type="text" name="description" class="form-control"value="{{ $results->description or old('description') }}">
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.color') }} *</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="color" value="danger" required=""@if (isset($results) and $results->color == 'danger') checked @endif>
                                        <span class='label label-danger'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                       <input type="radio" name="color" value="success"@if (isset($results) and $results->color == 'success') checked @endif>
                                       <span class='label label-success'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                       <input type="radio" name="color" value="primary"@if (isset($results) and $results->color == 'primary') checked @endif>
                                       <span class='label label-primary'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                       <input type="radio" name="color" value="warning"@if (isset($results) and $results->color == 'warning') checked @endif>
                                       <span class='label label-warning'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                       <input type="radio" name="color" value="info"@if (isset($results) and $results->color == 'info') checked @endif>
                                       <span class='label label-info'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                       <input type="radio" name="color" value="default"@if (isset($results) and $results->color == 'default') checked @endif>
                                       <span class='label label-default'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </label>
                                </div>
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
