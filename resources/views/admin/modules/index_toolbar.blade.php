<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h3 class="card-title float-none mb-1">{{ trans($moduleNameBlade.'.name') }}</h3>
        <small class="text-body-secondary">{{ trans('admin.total_rows') }} {{ $results->total() }}</small>
    </div>
    <form action="{{ route($moduleBasicRoute.'.index') }}" method="get" class="d-flex flex-wrap gap-2">
        @foreach ($indexFilters->hiddenFields(['search', 'search_mode']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <div class="input-group input-group-sm">
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ trans('admin.search') }}" aria-label="{{ trans('admin.search') }}">
            <button class="btn btn-outline-secondary" type="submit" aria-label="{{ trans('admin.search') }}"><i class="bi bi-search" aria-hidden="true"></i></button>
        </div>
        <select name="search_mode" class="form-select form-select-sm w-auto" aria-label="{{ trans('admin_filters.search_mode') }}">
            <option value="phrase" @selected($indexFilters->searchMode() === 'phrase')>{{ trans('admin_filters.phrase') }}</option>
            <option value="all_words" @selected($indexFilters->searchMode() === 'all_words')>{{ trans('admin_filters.all_words') }}</option>
        </select>
        <a href="{{ route($moduleBasicRoute.'.create') }}" class="btn btn-sm btn-success text-nowrap"><i class="bi bi-plus-lg" aria-hidden="true"></i> {{ trans('admin.add_new') }}</a>
    </form>
</div>
