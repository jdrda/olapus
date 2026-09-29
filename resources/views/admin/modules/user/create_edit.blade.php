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
        @include('admin.errors')

        <form action="{{ $editing ? route($moduleBasicRoute . '.update', $results->id) : route($moduleBasicRoute . '.store') }}" method="post">
            @csrf
            @if ($editing)
                @method($results->_method)
            @endif

            <section class="profile-section mb-3">
                <div class="profile-section-header">
                    <h4 class="h6 mb-0">
                        <i class="bi bi-person-lock me-2"></i>{{ trans('admin_module_user.sections.account') }}
                    </h4>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="name" class="form-label">{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                            <input type="text" name="name" id="name" class="form-control" maxlength="255"
                                   value="{{ old('name', $results->name ?? '') }}" required>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="email" class="form-label">{{ trans($moduleNameBlade . '.fields.email') }} *</label>
                            <input type="email" name="email" id="email" class="form-control" maxlength="255"
                                   value="{{ old('email', $results->email ?? '') }}" required>
                        </div>
                        <div class="col-12 col-lg-8">
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
                        <div class="col-12 col-lg-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input type="hidden" name="active" value="0">
                                <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
                                       @checked((bool) old('active', $editing ? ($results->active ?? false) : true))>
                                <label for="active" class="form-check-label">{{ trans($moduleNameBlade . '.fields.active') }}</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="password" class="form-label">
                                {{ trans($moduleNameBlade . '.fields.password') }}@if(!$editing) *@endif
                            </label>
                            <input type="password" name="password" id="password" class="form-control"
                                   autocomplete="new-password" @required(!$editing)>
                            @if ($editing)
                                <div class="form-text">{{ trans($moduleNameBlade . '.fields.password_leave_empty') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="password_confirmation" class="form-label">
                                {{ trans($moduleNameBlade . '.fields.password_again') }}@if(!$editing) *@endif
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" autocomplete="new-password" @required(!$editing)>
                        </div>
                    </div>
                </div>
            </section>

            @include('admin.modules.user._profile_fields')

            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                <a href="{{ route($moduleBasicRoute . '.index') }}" class="btn btn-outline-secondary">{{ trans('admin.close') }}</a>
                <button type="submit" name="submit" class="js-disable-on-submit btn btn-primary">{{ trans('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.modules.form_scripts')
@endpush
