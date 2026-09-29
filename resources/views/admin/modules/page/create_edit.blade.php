@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-file-earmark-text')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-publishing', 'menu-open')
@section('menu-class-page', 'active')

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
                            <div class="mb-3">
                                <label for="pagecategory_id" class="form-label">{{ trans($moduleNameBlade . '.fields.pagecategory') }} *</label>
                                <input type="hidden" name="_relations_present[pagecategory_id]" value="1">
                                <select name="pagecategory_id[]" id="pagecategory_id" class="form-select" data-enhance="select" multiple>
                                    @foreach (request('PageCategory', []) as $pagecategory)
                                        <option value="{{ $pagecategory->id }}" @selected(in_array($pagecategory->id, (array) old('pagecategory_id', old('_relations_present.pagecategory_id') ? [] : (isset($results->pagecategories) ? $results->pagecategories->pluck('id')->all() : []))))>{{ $pagecategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="author_name" class="form-label">{{ trans($moduleNameBlade . '.fields.author_name') }}</label>
                                <input type="text" name="author_name" id="author_name" class="form-control"
                                       value="{{ old('author_name', $results->author_name ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">{{ trans($moduleNameBlade . '.fields.author') }} *</label>
                                <select name="user_id" id="user_id" class="form-select" data-enhance="select">
                                    @foreach (request('User', []) as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id', $results->user_id ?? '') === (string) $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                        <button type="submit" name="published" value="1" class="btn btn-warning">{{ trans($moduleNameBlade . '.publish') }}</button>
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
