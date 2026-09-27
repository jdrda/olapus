{{-- One reusable multi-value column filter. All data is prepared by AdminFilters. --}}
@if (!empty($filter))
    <form action="{{ $filter['action'] }}" method="get" class="mt-2" style="min-width: 180px">
        @foreach ($filter['hidden'] as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <input type="hidden" name="filter" value="{{ $filter['key'] }}">
        <label for="filter-{{ $filter['key'] }}" class="visually-hidden">{{ $filter['label'] }}</label>
        <select id="filter-{{ $filter['key'] }}" name="values[]" multiple class="form-select form-select-sm" data-enhance="select">
            @foreach ($filter['options'] as $option)
                <option value="{{ $option['id'] }}" @selected($option['selected'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
        <div class="d-flex gap-2 mt-1">
            <button type="submit" class="btn btn-sm btn-outline-primary">{{ trans('admin_filters.apply') }}</button>
            @if ($filter['selected'])
                <a href="{{ $filter['clear_url'] }}" class="btn btn-sm btn-outline-secondary">{{ trans('admin_filters.clear') }}</a>
            @endif
        </div>
    </form>
@endif
