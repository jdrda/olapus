@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-description', trans($moduleNameBlade.'.description'))
@section('page-icon', 'bi bi-list-ul')
@section('menu-class-composite-example', 'menu-open')
@section('menu-class-externalrow', 'active')
@section('delete_confirmation_text', trans($moduleNameBlade.'.delete_row_confirmation'))

@section('content')
<div class="card">
    @include('admin.modules.index_toolbar')
    <div class="card-body">
        @include('admin.errors')
        @include('admin.modules.filters')
        <p class="small text-body-secondary">{{ trans('admin_composite.explanation') }}</p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr>
                    <th>ID</th>
                    <th>{{ trans($moduleNameBlade.'.fields.name') }}</th>
                    <th>{{ trans('admin_composite.source') }}</th>
                    <th>{{ trans('admin_composite.external_id') }}</th>
                    <th>{{ trans('admin_module_externalrecord.name') }} @include('admin.modules.index_filter', ['filter' => $filterControls['record']])</th>
                    <th class="text-end">{{ trans('admin.actions') }}</th>
                </tr></thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td>{{ $result->source_id }}</td>
                            <td>{{ $result->record_external_id }}</td>
                            <td>@if ($result->record)
                                <a href="{{ $indexFilters->addUrl('record', $result->record->id) }}">{{ $result->record->name }}</a>
                                @else <span class="text-body-secondary">&mdash;</span> @endif</td>
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
