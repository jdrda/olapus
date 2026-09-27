<form action="{{ $filter['action'] }}" method="get">
    @foreach ($filter['hidden'] as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
    <input type="hidden" name="field_filter" value="{{ $filter['key'] }}">
    <label for="column-filter-{{ $filter['key'] }}" class="form-label small">{{ $filter['label'] }}</label>
    @if ($filter['select'])
        <select id="column-filter-{{ $filter['key'] }}" name="{{ $filter['multiple'] ? 'field_value[]' : 'field_value' }}"
                class="form-select form-select-sm" @if($filter['multiple']) multiple data-enhance="select" @endif>
            @if (!$filter['multiple'])
                <option value="">{{ trans('admin_filters.any') }}</option>
            @endif
            @foreach ($filter['options'] as $option)
                <option value="{{ $option['id'] }}" @selected($option['selected'])>{{ $option['label'] }}</option>
            @endforeach
        </select>
    @else
        <input id="column-filter-{{ $filter['key'] }}" name="field_value" type="{{ $filter['input_type'] }}"
               class="form-control form-control-sm" value="{{ $filter['value'] }}" maxlength="500"
               @if($filter['input_type'] === 'number') step="{{ $filter['step'] }}" @endif
               @if($filter['min'] !== null) min="{{ $filter['min'] }}" @endif
               @if($filter['max'] !== null) max="{{ $filter['max'] }}" @endif>
    @endif
    <div class="d-flex gap-2 mt-1">
        <button type="submit" class="btn btn-sm btn-outline-primary">{{ trans('admin_filters.apply') }}</button>
        @if ($filter['selected'])
            <a href="{{ $filter['clear_url'] }}" class="btn btn-sm btn-outline-secondary">{{ trans('admin_filters.clear') }}</a>
        @endif
    </div>
</form>
