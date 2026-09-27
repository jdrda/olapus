@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name'))
@section('page-icon', 'bi bi-person')
@section('page-description', trans($moduleNameBlade . '.description'))
@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))

@section('menu-class-administration', 'menu-open')
@section('menu-class-user', 'active')

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
            @if (request()->filled('relation'))
                <input type="hidden" name="relation" value="{{ request('relation') }}">
            @endif
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
        @include('admin.modules.filters')
        @include('admin.modules.index_filters', ['extraFilterControls' => \Illuminate\Support\Arr::only($filterControls ?? [], ['role'])])

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route($moduleBasicRoute . '.index', array_merge(request()->only(['search', 'relation', 'orderbycolumn', 'orderbytype']), [
                                    'orderbycolumn' => 'id',
                                    'orderbytype' => request('orderbycolumn') === 'id' && request('orderbytype') === 'asc' ? 'desc' : 'asc',
                                ])) }}">
                                {{ trans($moduleNameBlade . '.fields.id') }}
                                @if(request()->has('orderbycolumn'))
                                    @if(request('orderbycolumn') == 'id')
                                        <i class='bi bi-sort-numeric-{{ request('orderbytype') == 'asc' ? 'down' : 'down-alt' }}'></i>
                                    @endif
                                @else
                                    <i class='bi bi-sort-numeric-down-alt'></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route($moduleBasicRoute . '.index', array_merge(request()->only(['search', 'relation', 'orderbycolumn', 'orderbytype']), [
                                    'orderbycolumn' => 'name',
                                    'orderbytype' => request('orderbycolumn') === 'name' && request('orderbytype') === 'asc' ? 'desc' : 'asc',
                                ])) }}">
                                {{ trans($moduleNameBlade . '.fields.name') }}
                                @if(request()->has('orderbycolumn') and request('orderbycolumn') == 'name')
                                    <i class='bi bi-sort-alpha-{{ request('orderbytype') == 'asc' ? 'down' : 'down-alt' }}'></i>
                                @endif
                            </a>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <a href="{{ route($moduleBasicRoute . '.index', array_merge(request()->only(['search', 'relation', 'orderbycolumn', 'orderbytype']), [
                                    'orderbycolumn' => 'email',
                                    'orderbytype' => request('orderbycolumn') === 'email' && request('orderbytype') === 'asc' ? 'desc' : 'asc',
                                ])) }}">
                                {{ trans($moduleNameBlade . '.fields.email') }}
                                @if(request()->has('orderbycolumn') and request('orderbycolumn') == 'email')
                                    <i class='bi bi-sort-alpha-{{ request('orderbytype') == 'asc' ? 'down' : 'down-alt' }}'></i>
                                @endif
                            </a>
                        </th>
                        <th>{{ trans($moduleNameBlade . '.fields.roles') }}</th>
                        <th class="d-none d-lg-table-cell">
                            <a href="{{ route($moduleBasicRoute . '.index', array_merge(request()->only(['search', 'relation', 'orderbycolumn', 'orderbytype']), [
                                    'orderbycolumn' => 'updated_at',
                                    'orderbytype' => request('orderbycolumn') === 'updated_at' && request('orderbytype') === 'asc' ? 'desc' : 'asc',
                                ])) }}">
                                {{ trans($moduleNameBlade . '.fields.updated_at') }}
                                @if(request()->has('orderbycolumn') and request('orderbycolumn') == 'updated_at')
                                    <i class='bi bi-sort-numeric-{{ request('orderbytype') == 'asc' ? 'down' : 'down-alt' }}'></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-end">
                            {{ trans('admin.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>{{ $result->name }}</td>
                            <td class="d-none d-md-table-cell">{{ $result->email }}</td>
                            <td>
                                @foreach ($result->roles as $role)
                                    <span class="badge text-bg-secondary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="d-none d-lg-table-cell">{{ $result->updated_at->format(trans('locale.date_format')) }}</td>
                            <td class="text-end">
                                <a href="{{ route($moduleBasicRoute . '.edit', $result->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> {{ trans('admin.edit') }}</a>
                                @if (\Illuminate\Support\Facades\Route::has($moduleBasicRoute . '.destroy'))
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $result->id }}"><i class="bi bi-trash"></i> {{ trans('admin.delete') }}</button>
                                @endif
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
    @include('admin.modules.form_scripts')
@endpush
