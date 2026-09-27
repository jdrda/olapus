@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-sliders')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-components', 'menu-open')
@section('menu-class-slider', 'active')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans($moduleNameBlade . '.name') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                @include('admin.errors')

                <form action="{{ isset($results->_method) ? route($moduleBasicRoute . '.update', $results->id) : route($moduleBasicRoute . '.store') }}" method="post">
                    @csrf
                    @if (isset($results->_method))
                        @method($results->_method)
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $results->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ trans($moduleNameBlade . '.fields.description') }}</label>
                        <input type="text" name="description" id="description" class="form-control"
                               value="{{ old('description', $results->description ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="cycle_interval" class="form-label">{{ trans($moduleNameBlade . '.fields.cycle_interval') }}</label>
                        <input type="number" name="cycle_interval" id="cycle_interval" class="form-control"
                               value="{{ old('cycle_interval', $results->cycle_interval ?? 5000) }}" min="1">
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        @if (isset($results->_method))
                            <a href="{{ route('admin.slide.index', ['relation' => 'slider:' . $results->id]) }}" class="btn btn-warning">{{ trans('admin_module_slider.show_slides') }}</a>
                        @endif
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
