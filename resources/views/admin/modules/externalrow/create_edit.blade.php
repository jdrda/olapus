@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-icon', 'bi bi-list-ul')
@section('menu-class-externalrow', 'active')
@section('menu-class-composite-example', 'menu-open')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ trans($moduleNameBlade.'.name') }}</h3></div>
    <div class="card-body">
        @include('admin.errors')
        <form method="post" action="{{ isset($results) ? route($moduleBasicRoute.'.update', $results->id) : route($moduleBasicRoute.'.store') }}">
            @csrf
            @isset($results) @method('PUT') @endisset
            <div class="mb-3">
                <label for="name" class="form-label">{{ trans($moduleNameBlade.'.fields.name') }}</label>
                <input id="name" name="name" class="form-control" value="{{ old('name', $results->name ?? '') }}" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="external_record_id" class="form-label">{{ trans('admin_module_externalrecord.name') }}</label>
                <select id="external_record_id" name="external_record_id" class="form-select" data-enhance="select" required>
                    <option value="">{{ trans('admin_composite.choose') }}</option>
                    @foreach (request('ExternalRecord', []) as $record)
                        <option value="{{ $record->id }}" @selected((string) old('external_record_id', isset($results) ? $results->record?->id : '') === (string) $record->id)>
                            [{{ $record->source_id }} / {{ $record->external_id }}] {{ $record->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">{{ trans('admin_composite.selection_help') }}</div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route($moduleBasicRoute.'.index') }}" class="btn btn-outline-secondary">{{ trans('admin.close') }}</a>
                <button class="btn btn-primary" type="submit">{{ trans('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    @include('admin.modules.form_scripts')
@endpush
