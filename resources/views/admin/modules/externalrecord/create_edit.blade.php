@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-icon', 'bi bi-folder2-open')
@section('menu-class-externalrecord', 'active')
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
                <label for="source_id" class="form-label">{{ trans('admin_composite.source') }}</label>
                <input id="source_id" name="source_id" type="number" min="1" max="2147483647" class="form-control" value="{{ old('source_id', $results->source_id ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="external_id" class="form-label">{{ trans('admin_composite.external_id') }}</label>
                <input id="external_id" name="external_id" type="number" min="1" class="form-control" value="{{ old('external_id', $results->external_id ?? '') }}" required>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route($moduleBasicRoute.'.index') }}" class="btn btn-outline-secondary">{{ trans('admin.close') }}</a>
                <button class="js-disable-on-submit btn btn-primary" type="submit">{{ trans('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    @include('admin.modules.form_scripts')
@endpush
