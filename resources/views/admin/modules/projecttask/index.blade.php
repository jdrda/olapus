@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-description', trans($moduleNameBlade.'.description'))
@section('page-icon', 'bi bi-check2-square')
@section('menu-class-relations-example', 'menu-open')
@section('menu-class-projecttask', 'active')
@section('delete_confirmation_text', trans($moduleNameBlade.'.delete_row_confirmation'))

@section('content')
<div class="card">
    @include('admin.modules.index_toolbar')
    <div class="card-body">
        @include('admin.errors')
        @include('admin.modules.filters')
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr>
                    <th><a href="{{ $indexFilters->url(['orderbycolumn' => 'id', 'orderbytype' => request('orderbytype') === 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
                    <th><a href="{{ $indexFilters->url(['orderbycolumn' => 'name', 'orderbytype' => request('orderbytype') === 'asc' ? 'desc' : 'asc']) }}">{{ trans($moduleNameBlade.'.fields.name') }}</a></th>
                    <th>{{ trans('admin_module_project.name') }} @include('admin.modules.index_filter', ['filter' => $filterControls['project']])</th>
                    <th>{{ trans('admin_filters.completed') }}</th>
                    <th>{{ trans($moduleNameBlade.'.fields.updated_at') }}</th>
                    <th class="text-end">{{ trans('admin.actions') }}</th>
                </tr></thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td>@if ($result->project)<a href="{{ $indexFilters->addUrl('project', $result->project->id) }}">{{ $result->project->name }}</a>@else &mdash; @endif</td>
                            <td>{{ trans($result->completed ? 'admin_filters.yes' : 'admin_filters.no') }}</td>
                            <td>{{ $result->updated_at?->format(trans('locale.date_format')) }}</td>
                            <td class="text-end">@include('admin.modules.index_actions')</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">{{ trans('admin.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $results->withQueryString()->links() }}</div>
</div>
@endsection
@push('scripts')
    @include('admin.modules.delete_modals')
    @include('admin.modules.form_scripts')
@endpush
