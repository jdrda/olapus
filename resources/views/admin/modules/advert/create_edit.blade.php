@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-megaphone')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-advertising', 'menu-open')
@section('menu-class-advert', 'active')

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
                                <label for="caption" class="form-label">{{ trans($moduleNameBlade . '.fields.caption') }}</label>
                                <input type="text" name="caption" id="caption" class="form-control"
                                       value="{{ old('caption', $results->caption ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="advertlocation_id" class="form-label">{{ trans($moduleNameBlade . '.fields.advertlocation') }} *</label>
                                <input type="hidden" name="_relations_present[advertlocation_id]" value="1">
                                <select name="advertlocation_id[]" id="advertlocation_id" class="form-select" data-enhance="select" multiple>
                                    @foreach (request('AdvertLocation', []) as $advertlocation)
                                        <option value="{{ $advertlocation->id }}" @selected(in_array($advertlocation->id, (array) old('advertlocation_id', old('_relations_present.advertlocation_id') ? [] : (isset($results->advertlocations) ? $results->advertlocations->pluck('id')->all() : []))))>{{ $advertlocation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="link_url" class="form-label">{{ trans($moduleNameBlade . '.fields.link_url') }}</label>
                                <input type="text" name="link_url" id="link_url" class="form-control"
                                       value="{{ old('link_url', $results->link_url ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="link_title" class="form-label">{{ trans($moduleNameBlade . '.fields.link_title') }}</label>
                                <input type="text" name="link_title" id="link_title" class="form-control"
                                       value="{{ old('link_title', $results->link_title ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label">{{ trans($moduleNameBlade . '.fields.position') }} *</label>
                                <input type="number" name="position" id="position" class="form-control"
                                       value="{{ old('position', $results->position ?? 1) }}" required step="1">
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
