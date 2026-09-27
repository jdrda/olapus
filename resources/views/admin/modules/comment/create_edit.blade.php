@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-chat-dots')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-publishing', 'menu-open')
@section('menu-class-comment', 'active')

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
                                <label for="text" class="form-label">{{ trans($moduleNameBlade . '.fields.text') }}</label>
                                <textarea name="text" id="text" class="form-control" rows="18">{{ old('text', $results->text ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="page" class="form-label">{{ trans($moduleNameBlade . '.fields.page') }}</label>
                                <input type="text" name="page" id="page" class="form-control" value="{{ $results->pages->name ?? '-' }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="article" class="form-label">{{ trans($moduleNameBlade . '.fields.article') }}</label>
                                <input type="text" name="article" id="article" class="form-control" value="{{ $results->articles->name ?? '-' }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="headline" class="form-label">{{ trans($moduleNameBlade . '.fields.headline') }}</label>
                                <input type="text" name="headline" id="headline" class="form-control"
                                       value="{{ old('headline', $results->headline ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ trans($moduleNameBlade . '.fields.name') }}</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ old('name', $results->name ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ trans($moduleNameBlade . '.fields.email') }}</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="{{ old('email', $results->email ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">{{ trans($moduleNameBlade . '.fields.url') }}</label>
                                <input type="url" name="url" id="url" class="form-control"
                                       value="{{ old('url', $results->url ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="commentstatus_id" class="form-label">{{ trans($moduleNameBlade . '.fields.commentstatus') }} *</label>
                                <select name="commentstatus_id" id="commentstatus_id" class="form-select" data-enhance="select">
                                    @foreach (request('CommentStatus', []) as $status)
                                        <option value="{{ $status->id }}" @selected((string) old('commentstatus_id', $results->commentstatus_id ?? '') === (string) $status->id)>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
