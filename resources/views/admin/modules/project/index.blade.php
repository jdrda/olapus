@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-description', trans($moduleNameBlade.'.description'))
@section('page-icon', 'bi bi-kanban')
@section('menu-class-relations-example', 'menu-open')
@section('menu-class-project', 'active')
@section('delete_confirmation_text', trans($moduleNameBlade.'.delete_row_confirmation'))

@section('content')
<div class="card">
    @include('admin.modules.index_toolbar')
    <div class="card-body">
        @include('admin.errors')
        @include('admin.modules.filters')
        <p class="small text-body-secondary">{{ trans('admin_examples.filter_help') }}</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr>
                    <th><a href="{{ $indexFilters->url(['orderbycolumn' => 'id', 'orderbytype' => request('orderbytype') === 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
                    <th><a href="{{ $indexFilters->url(['orderbycolumn' => 'name', 'orderbytype' => request('orderbytype') === 'asc' ? 'desc' : 'asc']) }}">{{ trans($moduleNameBlade.'.fields.name') }}</a></th>
                    <th>{{ trans($moduleNameBlade.'.fields.owner') }} @include('admin.modules.index_filter', ['filter' => $filterControls['owner']])</th>
                    <th>{{ trans($moduleNameBlade.'.fields.labels') }} @include('admin.modules.index_filter', ['filter' => $filterControls['label']])</th>
                    <th>{{ trans($moduleNameBlade.'.fields.tasks') }} @include('admin.modules.index_filter', ['filter' => $filterControls['task']])</th>
                    <th class="text-end">{{ trans('admin.actions') }}</th>
                </tr></thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td>
                                @if ($result->owner)
                                    <a href="{{ $indexFilters->addUrl('owner', $result->owner->id) }}">{{ $result->owner->name }}</a>
                                @else
                                    <span class="text-body-secondary">&mdash;</span>
                                @endif
                            </td>
                            <td>
                                @foreach ($result->labels as $label)
                                    <a href="{{ $indexFilters->addUrl('label', $label->id) }}" class="badge text-bg-secondary text-decoration-none">{{ $label->name }}</a>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($result->tasks as $task)
                                    <a href="{{ $indexFilters->addUrl('task', $task->id) }}" class="badge text-bg-{{ $task->completed ? 'success' : 'secondary' }} text-decoration-none">{{ $task->name }}</a>
                                @endforeach
                            </td>
                            <td class="text-end text-nowrap">@include('admin.modules.index_actions')</td>
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
