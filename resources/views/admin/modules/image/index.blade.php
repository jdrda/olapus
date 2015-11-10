@extends('admin.master')

@section('page-name', trans('admin_module_image.name') )

@section('page-icon', 'fa fa-image')

@section('page-description', trans('admin_module_image.description'))

@section('delete_confirmation_text', trans('admin_module_image.delete_row_confirmation'))

@section('menu-class-media', 'active')
@section('menu-class-image', 'active')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    {{ trans('admin_module_image.name') }}
                    <small>(total rows {{ $results->count() }} of {{ $results->total() }}, showing page {{ $results->currentPage() }} of {{ $results->lastPage() }})</small>
                </h3>


                <div class="box-tools">

                    <form action="{{ route('admin.image.index') }}" method="get">

                        <!-- Search box -->
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control pull-right" placeholder="{{ trans('admin.search') }}" value="{{ request('search') }}">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <!-- /Search box -->

                            <a href="{{ route('admin.image.create') }}" class="btn btn-success btn-sm form-control">
                                <i class="fa fa-plus"></i> {{ trans('admin.add_new') }}
                            </a>
                        </div>

                    </form>

                </div>

            </div>
            <!-- /.box-header -->

            <!-- Data table -->
            <div class="box-body">
                
                <div class='row'>
                    <div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>
                    @include('admin.errors')
                    </div>
                </div>
                <table class="table table-hover table-responsive">
                    <tbody>
                        <tr>
                            <th>{{ trans('admin_module_image.fields.id') }}</th>
                            <th>{{ trans('admin_module_image.fields.name') }}</th>
                            <th class="hidden-xs">{{ trans('admin_module_image.fields.url') }}</th>
                            <th class="hidden-xs hidden-sm">{{ trans('admin_module_image.fields.description') }}</th>
                            <th class="hidden-xs hidden-sm">{{ trans('admin_module_image.fields.updated_at') }}</th>
                            <th class="text-right">{{ trans('admin.actions') }}</th>
                        </tr>
                    </tbody>
                </table>
                <div class='row'>
                    &nbsp;
                </div>


                <div class="row">
                    @foreach ($results as $result)            
                    <div class="col-xs-6 col-sm-3 col-md-2">
                        <div class="panel panel-{{ $result->imagecategories()->first()->color }}">
                            <div class="panel-heading" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ $result->name }}</div>
                            <div class="panel-body image-square row-xs-flex-center">
                                <a href="#" data-toggle="modal" data-target="#deleteModal{{ $result->id }}"><img src="{{ route('getImage', ['imageName' => $result->url, 'imageExtension' => $result->image_extension]) }}" alt="{{ $result->name }}" class="img-responsive"></a>  
                            </div>
                            <div class="panel-footer text-center">
                                <div class="btn-group">
                                    
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#"><i class="fa fa-link"></i> Get URL</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ route($moduleBasicRoute . '.edit', $result->id) }}"><i class="fa fa-pencil"></i> {{ trans('admin.edit') }}</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#deleteModal{{ $result->id }}"><i class="fa fa-remove"></i> {{ trans('admin.delete') }}</a></li>
                                    </ul>
                                </div>                  
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                
                <hr>
                <div class="row">
                    <div class="col-xs-12 text-center"> 
                        {!! $results->render() !!}
                    </div>
                </div>
            </div>
            <!-- Data table -->
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection

@section('foot')

@include('admin/modules/image_detail_modals');

@parent

<script>

$( document ).ready(function() {
    squareThis('.image-square');
});
</script>

@endsection