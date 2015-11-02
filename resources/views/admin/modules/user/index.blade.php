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
                        <a href="{{ route('admin.user.create') }}" class="btn btn-success btn-sm">
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
                
                <div class='row'>
                    <div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>
                    @include('admin.errors')
                    </div>
                </div>
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
                                <a href="{{ route('admin.user.edit', [$result->id]) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> {{ trans('admin.edit') }}</a>
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

@include('admin/modules/delete_modals');

@parent
@endsection