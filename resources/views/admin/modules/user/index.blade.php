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
                    <small>(total rows {{ $results->count() }} of {{ $results->total() }}, showing page {{ $results->currentPage() }} of {{ $results->lastPage() }})</small>
                </h3>


                <div class="box-tools">

                    <div class="input-group input-group-sm">
                        <a href="{{ url('admin/user/create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> {{ trans('admin.add_new') }}
                        </a>

                    </div>

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

            <!-- Data table -->
            <div class="box-body no-padding">
                <table class="table table-hover table-responsive">
                    <tbody>
                        <tr>
                            <th>{{ trans('admin_module_user.fields.id') }}</th>
                            <th>{{ trans('admin_module_user.fields.name') }}</th>
                            <th class="hidden-xs">{{ trans('admin_module_user.fields.email') }}</th>
                            <th class="hidden-xs hidden-sm">{{ trans('admin_module_user.fields.update_at') }}</th>
                            <th class="text-right">{{ trans('admin.actions') }}</th>
                        </tr>
                        @foreach ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td class="hidden-xs">{{ $result->email }}</td>
                            <td class="hidden-xs hidden-sm">{{ $result->updated_at->format(trans('locale.date_format')) }}</td>
                            <td class="text-right">
                                <a href="{{ url('admin/user/' . $result->id . '/edit') }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> {{ trans('admin.edit') }}</a>
                                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal{{ $result->id }}"><i class="fa fa-remove"></i> {{ trans('admin.delete') }}</button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                    
                </table>
                
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
<!-- Delete modals -->
@foreach ($results as $result)
<div class='example-modal' >
    <div class='modal modal-danger' id='deleteModal{{ $result->id }}'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ trans('delete_the_row') }}{{ $result->id }}</h4>
                </div>
                <div class='modal-body'>
                    <p>{{ trans('delete_row_confirmation') }} <strong>{{ $result->name }}</strong>?</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline pull-left' data-dismiss='modal'>{{ trans('admin.close') }}</button>
                    <form action="{{ url('admin/user/' . $result->id) }}" method="post" role="form" class="form">
                        <input type="hidden" name="_method" value="delete">
                        {!! csrf_field() !!}
                        <button type='button' class='btn btn-outline'>{{ trans('admin.delete') }}</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endforeach
<!-- /Delete modals -->

@parent
@endsection