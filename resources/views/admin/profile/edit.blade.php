@extends('admin.master')

@section('page-name', trans('admin_module_user.profile'))
@section('page-icon', 'bi bi-person-circle')
@section('page-description', trans('admin_module_user.profile_description'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('admin_module_user.profile') }}</h3>
    </div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        @include('admin.errors')

        <form action="{{ route('admin.profile.update') }}" method="post">
            @csrf
            @method('PUT')

            <section class="profile-section mb-3">
                <div class="profile-section-header">
                    <h4 class="h6 mb-0">
                        <i class="bi bi-person-lock me-2"></i>{{ trans('admin_module_user.sections.account') }}
                    </h4>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="name" class="form-label">{{ trans('admin_module_user.fields.name') }} *</label>
                            <input type="text" name="name" id="name" class="form-control" maxlength="255"
                                   value="{{ old('name', $results->name) }}" required>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="email" class="form-label">{{ trans('admin_module_user.fields.email') }} *</label>
                            <input type="email" name="email" id="email" class="form-control" maxlength="255"
                                   value="{{ old('email', $results->email) }}" required>
                            <div class="form-text">{{ trans('admin_module_user.help.email') }}</div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="password" class="form-label">{{ trans('admin_module_user.fields.password') }}</label>
                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                            <div class="form-text">{{ trans('admin_module_user.fields.password_leave_empty') }}</div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="password_confirmation" class="form-label">{{ trans('admin_module_user.fields.password_again') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </section>

            @include('admin.modules.user._profile_fields')

            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.dashboard.index') }}" class="btn btn-outline-secondary">{{ trans('admin.close') }}</a>
                <button type="submit" class="btn btn-primary js-disable-on-submit">
                    <i class="bi bi-floppy me-1"></i>{{ trans('admin.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.modules.form_scripts')
@endpush
