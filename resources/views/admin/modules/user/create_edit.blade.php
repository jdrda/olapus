@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-person')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-administration', 'menu-open')
@section('menu-class-user', 'active')

@section('content')
@php($editing = isset($results->_method))

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans($moduleNameBlade . '.name') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                @include('admin.errors')

                <form action="{{ $editing ? route($moduleBasicRoute . '.update', $results->id) : route($moduleBasicRoute . '.store') }}" method="post">
                    @csrf
                    @if ($editing)
                        @method($results->_method)
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $results->name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ trans($moduleNameBlade . '.fields.email') }} *</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email', $results->email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="roles" class="form-label">{{ trans($moduleNameBlade . '.fields.roles') }}</label>
                        <select name="roles[]" id="roles" class="form-select" multiple data-enhance="select">
                            @php($selectedRoles = array_map('intval', old('roles', isset($results) ? $results->roles->pluck('id')->all() : [])))
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(in_array((int) $role->id, $selectedRoles, true))>
                                    {{ $role->name }}@if($role->description) — {{ $role->description }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            {{ trans($moduleNameBlade . '.fields.password') }}@if(!$editing) *@endif
                        </label>
                        <input type="password" name="password" id="password" class="form-control"
                               autocomplete="new-password" @required(!$editing)>
                        @if ($editing)
                            <div class="form-text">
                                {{ trans($moduleNameBlade . '.fields.password_leave_empty') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            {{ trans($moduleNameBlade . '.fields.password_again') }}@if(!$editing) *@endif
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" autocomplete="new-password" @required(!$editing)>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        <button type="submit" name="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.modules.form_scripts')
@endpush
