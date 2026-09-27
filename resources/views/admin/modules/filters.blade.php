{{-- Active filters remain visible even when no row matches. --}}
@if (!empty($filterBadges))
    <div class="border rounded p-2 mb-3" aria-label="{{ trans('admin_filters.active') }}">
        <strong class="me-2">{{ trans('admin_filters.active') }}</strong>
        @foreach ($filterBadges as $filterBadge)
            <a href="{{ $filterBadge['url'] }}" class="btn btn-sm btn-outline-secondary m-1">
                {{ $filterBadge['label'] }}
                <i class="bi bi-x-lg ms-1" aria-hidden="true"></i>
                <span class="visually-hidden">{{ trans('admin_filters.remove') }}</span>
            </a>
        @endforeach
        <a href="{{ $indexFilters->clearUrl() }}" class="btn btn-sm btn-outline-danger m-1">{{ trans('admin_filters.clear_all') }}</a>
    </div>
@endif

{{-- One common panel; individual module templates need no filter query logic. --}}
@include('admin.modules.column_filters')
