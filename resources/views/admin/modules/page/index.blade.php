@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-sticky-note-o')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))


@section('menu-class-pages', 'active')
@section('menu-class-page', 'active')


{{-- Order by --}}


@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
               <h3 class="box-title">
                    {{ trans($moduleNameBlade . '.name') }}
                    <small>(total rows {{ $results->count() }} of {{ $results->total() }}, showing page {{ $results->currentPage() }} of {{ $results->lastPage() }})</small>
                </h3>


                <div class="box-tools">

                    <form action="{{ route($moduleBasicRoute . '.index') }}" method="get">

                        <!-- Search box -->
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control pull-right" placeholder="{{ trans('admin.search') }}" value="{{ request('search') }}">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <!-- /Search box -->

                            <a href="{{ route($moduleBasicRoute . '.create') }}" class="btn btn-success btn-sm form-control">
                                <i class="fa fa-plus"></i> {{ trans('admin.add_new') }}
                            </a>
                        </div>

                    </form>

                </div>

            </div>
            <!-- /.box-header -->

            <!-- Data table -->
            <div class="box-body no-padding">

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
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'meta_title' and request('orderbytype') == 'asc'){{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'meta_title', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'meta_title', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.meta_title') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'meta_title')
                                    <i class='fa fa-sort-alpha-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                           <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'url' and request('orderbytype') == 'asc'){{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'url', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'url', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.url') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'url')
                                    <i class='fa fa-sort-alpha-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                            <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'published_at' and request('orderbytype') == 'asc'){{ route('admin.user.index', ['search' => request('search'), 'orderbycolumn' => 'published_at', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'published_at', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.published_at') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'published_at')
                                    <i class='fa fa-sort-numeric-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                            <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'updated_at' and request('orderbytype') == 'asc'){{ route('admin.user.index', ['search' => request('search'), 'orderbycolumn' => 'updated_at', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'updated_at', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.updated_at') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'updated_at')
                                    <i class='fa fa-sort-numeric-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                            <th class="text-right">
                                {{ trans('admin.actions') }}
                            </th>
                        </tr>
                        @foreach ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td class="hidden-xs">{{ $result->meta_title }}</td>
                            <td class="hidden-xs hidden-sm">{{ $result->url }}</td>
                            <td class="hidden-xs hidden-sm">@if(empty($result->published_at) == FALSE){{ $result->published_at->format(trans('locale.date_format')) }}@endif</td>
                            <td class="hidden-xs hidden-sm">{{ $result->updated_at->format(trans('locale.date_format'))}}</td>
                            <td class="text-right">
                                <a href="{{ route($moduleBasicRoute . '.edit', $result->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> {{ trans('admin.edit') }}</a>
                                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal{{ $result->id }}"><i class="fa fa-remove"></i> {{ trans('admin.delete') }}</button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>

                <hr>
                <div class="row">
                    <div class="col-xs-12 text-center"> 
                        {!! $results->appends(Request::all())->render() !!}
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

@include('admin/modules/delete_modals');

@parent
@endsection