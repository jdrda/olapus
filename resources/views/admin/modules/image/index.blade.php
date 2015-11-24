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
                    {{ trans($moduleNameBlade . '.name') }}
                    <small>({{ trans('admin.total_rows') }} {{ $results->count() }} {{ trans('admin.of') }} {{ $results->total() }}, {{ trans('admin.showing_page') }} {{ $results->currentPage() }} {{ trans('admin.of') }} {{ $results->lastPage() }})</small>
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
                            <th>
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'id' and request('orderbytype') == 'asc'){{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'id', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'id', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.id') }}
                                    @if(Request::has('orderbycolumn'))
                                        @if(request('orderbycolumn') == 'id')
                                        <i class='fa fa-sort-numeric-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                        @endif
                                    @else
                                    <i class='fa fa-sort-numeric-desc'></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'name' and request('orderbytype') == 'asc'){{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'name', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'name', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.name') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'name')
                                    <i class='fa fa-sort-alpha-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                            <th class="hidden-xs">
                                <form action="{{ route($moduleBasicRoute . '.index') }}" method="get" id='imageCategoryForm'>
                                    <select name='relation' id='imagecategory_id' class='input-sm'>
                                        <option value=''>{{ trans($moduleNameBlade . '.fields.category') }} ...</option>
                                        @foreach (request('ImageCategory') as $imageCategory)
                                        <option value='imageCategory:{{ $imageCategory->id }}' @if(request('external_tables_filter')['imageCategory'] == $imageCategory->id) selected @endif>{{ $imageCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                
                            </th>
                            <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'updated_at' and request('orderbytype') == 'asc'){{ route('admin.user.index', ['search' => request('search'), 'orderbycolumn' => 'updated_at', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'updated_at', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.updated_at') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'updated_at')
                                    <i class='fa fa-sort-numeric-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
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
                            <div class="panel-heading" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis" data-toggle="tooltip" data-placement="top" title="{{ $result->name }}">{{ $result->name }}</div>
                            <div class="panel-body image-square row-xs-flex-center text-center">
                                <a href="#" data-toggle="modal" data-target="#imageDetailModal{{ $result->id }}">
                                    <img data-original="{{ route('getImage', ['imageName' => $result->url, 'imageExtension' => $result->image_extension]) }}" alt="{{ $result->name }}" class="img-responsive lazy" style="margin: 0 auto; float: none;"  data-toggle="tooltip" data-placement="bottom" title="{{ $result->imagecategories->name }}">
                                </a>  
                            </div>
                            <div class="panel-footer text-center">
                                <div class="btn-group">
                                    
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ trans('admin_module_image.action') }} <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-toggle="modal" data-target="#imageURLModal{{ $result->id }}"><i class="fa fa-link"></i> {{ trans('admin_module_image.get_url') }}</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#imageDetailModal{{ $result->id }}"><i class="fa fa-info-circle"></i> {{ trans('admin_module_image.get_info') }}</a></li>
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
@include('admin/modules/image_url_modals');
@include('admin/modules/delete_modals');

@parent

<script>

$( document ).ready(function() {
    
    // image containers must be squares
    squareThis('.image-square');
    
    $(".image-square img").css({ "max-height": $(".image-square").height() + 'px' });

    // lazyload images
    $("img.lazy").lazyload({
        effect : "fadeIn"
    });

    $('.url_modal').on('shown.bs.modal', function () {
        $(this).add('input').select();
    });
    
    // Category filter
    $('#imagecategory_id').on('change', function() {
        $('#imageCategoryForm').submit();
    });
});
</script>

@endsection