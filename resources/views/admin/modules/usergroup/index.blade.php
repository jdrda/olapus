@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-people')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-administration', 'menu-open')
@section('menu-class-usergroup', 'active')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h3 class="card-title float-none mb-1">{{ trans($moduleNameBlade . '.name') }}</h3>
            <small class="text-body-secondary">
                {{ trans('admin.total_rows') }} {{ $results->count() }} {{ trans('admin.of') }} {{ $results->total() }},
                {{ trans('admin.showing_page') }} {{ $results->currentPage() }} {{ trans('admin.of') }} {{ $results->lastPage() }}
            </small>
        </div>
        <form action="{{ route($moduleBasicRoute . '.index') }}" method="get" class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm w-auto">
                <input type="search" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="{{ trans('admin.search') }}" aria-label="{{ trans('admin.search') }}">
                <button type="submit" class="btn btn-outline-secondary" aria-label="{{ trans('admin.search') }}"><i class="bi bi-search" aria-hidden="true"></i></button>
            </div>
            <a href="{{ route($moduleBasicRoute . '.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> {{ trans('admin.add_new') }}
            </a>
        </form>
    </div>

    <div class="card-body">
        @include('admin.errors')

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ trans($moduleNameBlade . '.fields.id') }}</th>
                        <th>{{ trans($moduleNameBlade . '.fields.name') }}</th>
                        <th>{{ trans($moduleNameBlade . '.fields.description') }}</th>
                        <th>{{ trans($moduleNameBlade . '.fields.users') }}</th>
                        <th class="d-none d-lg-table-cell">{{ trans($moduleNameBlade . '.fields.updated_at') }}</th>
                        <th class="text-end">{{ trans('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td><code>{{ $result->name }}</code></td>
                            <td>{{ $result->description }}</td>
                            <td>{{ $result->users_count }}</td>
                            <td class="d-none d-lg-table-cell">{{ $result->updated_at->format(trans('locale.date_format')) }}</td>
                            <td class="text-end">
                                <a href="{{ route($moduleBasicRoute . '.edit', $result->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> {{ trans('admin.edit') }}</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $result->id }}"><i class="bi bi-trash"></i> {{ trans('admin.delete') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-body-secondary py-4">{{ trans('admin.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $results->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
    @include('admin.modules.delete_modals')
@endpush
