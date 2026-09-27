@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-icon', 'bi bi-check2-square')
@section('menu-class-relations-example', 'menu-open')
@section('menu-class-projecttask', 'active')

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
                <label for="project_id" class="form-label">{{ trans($moduleNameBlade.'.fields.project') }}</label>
                <select id="project_id" name="project_id" class="form-select" data-enhance="select" required>
                    @foreach (request('Project', []) as $project)
                        <option value="{{ $project->id }}" @selected((string) old('project_id', $results->project_id ?? '') === (string) $project->id)>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="completed" value="0">
                <input id="completed" name="completed" type="checkbox" value="1" class="form-check-input" @checked(old('completed', $results->completed ?? false))>
                <label for="completed" class="form-check-label">{{ trans($moduleNameBlade.'.fields.completed') }}</label>
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
