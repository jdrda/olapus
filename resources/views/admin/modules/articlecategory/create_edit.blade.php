@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-files')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-publishing', 'menu-open')
@section('menu-class-articlecategory', 'active')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans($moduleNameBlade . '.name') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                @include('admin.errors')

                <form action="{{ isset($results->_method) ? route($moduleBasicRoute . '.update', $results->id) : route($moduleBasicRoute . '.store') }}" method="post">
                    @csrf
                    @if (isset($results->_method))
                        @method($results->_method)
                    @endif

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ old('name', $results->name ?? '') }}" required>
                            </div>
                            <fieldset class="mb-3">
                                <legend class="form-label fs-6">{{ trans($moduleNameBlade . '.fields.color') }} *</legend>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-danger" value="danger" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'danger') required>
                                    <label for="color-danger" class="form-check-label">
                                        <span class="badge text-bg-danger">danger</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-success" value="success" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'success') required>
                                    <label for="color-success" class="form-check-label">
                                        <span class="badge text-bg-success">success</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-primary" value="primary" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'primary') required>
                                    <label for="color-primary" class="form-check-label">
                                        <span class="badge text-bg-primary">primary</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-warning" value="warning" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'warning') required>
                                    <label for="color-warning" class="form-check-label">
                                        <span class="badge text-bg-warning">warning</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-info" value="info" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'info') required>
                                    <label for="color-info" class="form-check-label">
                                        <span class="badge text-bg-info">info</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="color" id="color-default" value="default" class="form-check-input"
                                           @checked(old('color', $results->color ?? 'default') === 'default') required>
                                    <label for="color-default" class="form-check-label">
                                        <span class="badge text-bg-secondary">default</span>
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">{{ trans($moduleNameBlade . '.fields.meta_title') }}</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control"
                                       value="{{ old('meta_title', $results->meta_title ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">{{ trans($moduleNameBlade . '.fields.meta_description') }}</label>
                                <input type="text" name="meta_description" id="meta_description" class="form-control"
                                       value="{{ old('meta_description', $results->meta_description ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">{{ trans($moduleNameBlade . '.fields.meta_keywords') }}</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                       value="{{ old('meta_keywords', $results->meta_keywords ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">{{ trans($moduleNameBlade . '.fields.url') }} *</label>
                                <input type="text" name="url" id="url" class="form-control"
                                       value="{{ old('url', $results->url ?? '') }}" required @if (!isset($results->_method)) data-slug-source="#name" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">{{ trans($moduleNameBlade . '.fields.text') }}</label>
                        <textarea name="text" id="text" class="form-control" rows="10" data-editor="html">{{ old('text', $results->text ?? '') }}</textarea>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        <button type="submit" name="submit" class="js-disable-on-submit btn btn-primary">{{ trans('admin.save') }}</button>
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
