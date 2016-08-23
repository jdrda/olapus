@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-comment-o')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))


@section('menu-class-publishing', 'active')
@section('menu-class-comment', 'active')

{{-- Order by --}}


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
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'headline' and request('orderbytype') == 'asc'){{ route('admin.user.index', ['search' => request('search'), 'orderbycolumn' => 'headline', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'headline', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.headline') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'headline')
                                    <i class='fa fa-sort-numeric-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>
                            <th class="hidden-xs">
                                <form action="{{ route($moduleBasicRoute . '.index') }}" method="get" id='commentstatusForm'>
                                    <select name='relation' id='commentstatus_id' class='form-control input-sm'>
                                        <option value=''>{{ trans($moduleNameBlade . '.fields.commentstatus') }} ...</option>
                                        @foreach (request('CommentStatus') as $commentstatus)
                                        <option value='commentstatus:{{ $commentstatus->id }}' @if(request('external_tables_filter')['commentstatus'] == $commentstatus->id) selected @endif>{{ $commentstatus->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                
                            </th>
                            <th class="hidden-xs">
                               {{ trans($moduleNameBlade . '.fields.page') }}
                            </th>
                            <th class="hidden-xs">
                               {{ trans($moduleNameBlade . '.fields.article') }}
                            </th>      
                            <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'name' and request('orderbytype') == 'asc'){{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'name', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'name', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.name') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'name')
                                    <i class='fa fa-sort-alpha-{{ request('orderbytype') == 'asc' ? 'asc' : 'desc' }}'></i>
                                    @endif
                                </a>
                            </th>                
                            <th class="hidden-xs hidden-sm">
                                <a href="@if(Request::has('orderbycolumn') and request('orderbycolumn') == 'email' and request('orderbytype') == 'asc'){{ route('admin.user.index', ['search' => request('search'), 'orderbycolumn' => 'email', 'orderbytype' => 'desc']) }}@else{{ route($moduleBasicRoute . '.index', ['search' => request('search'), 'orderbycolumn' => 'email', 'orderbytype' => 'asc']) }}@endif">
                                    {{ trans($moduleNameBlade . '.fields.email') }}
                                    @if(Request::has('orderbycolumn') and request('orderbycolumn') == 'email')
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
                            <td>{{ $result->headline }}</td>
                            <td>
                                <span class="label label-{{ $result->commentstatuses->color }}">{{ $result->commentstatuses->name }}</span>
                            </td>
                            <td class="hidden-xs">{{ $result->pages->name or '' }}</td>
                            <td class="hidden-xs">{{ $result->articles->name or '' }}</td>
                            <td class="hidden-xs hidden-sm">{{ $result->name }}</td>
                            <td class="hidden-xs hidden-sm">{{ $result->email }}</td>
                            <td class="hidden-xs hidden-sm">{{ $result->updated_at->format(trans('locale.date_format'))}}</td>
                            <td class="text-right">
                                <a href="{{ route($moduleBasicRoute . '.approve', $result->id) }}" class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i> {{ trans('admin.approve') }}</a>
                                <a href="{{ route($moduleBasicRoute . '.spam', $result->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-bomb"></i> {{ trans('admin.spam') }}</a>
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
<script>
$(function() {
    $('#commentstatus_id').on('change', function() {
        $('#commentstatusForm').submit();
      });
});
</script>
@endsection
