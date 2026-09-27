@if (!empty($filterPresets))
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <span class="small text-body-secondary">{{ trans('admin_filters.presets') }}</span>
        @foreach ($filterPresets as $preset)
            <a href="{{ $preset['url'] }}" class="btn btn-sm btn-outline-primary">{{ $preset['label'] }}</a>
        @endforeach
    </div>
@endif

@if (!empty($columnFilterControls))
    <details class="border rounded p-2 mb-3" @if(collect($columnFilterControls)->contains('selected', true)) open @endif>
        <summary class="fw-semibold">{{ trans('admin_filters.columns') }}</summary>
        <div class="row g-3 mt-0">
            @foreach ($columnFilterControls as $columnFilter)
                <div class="col-12 col-sm-6 col-xl-3">
                    @include('admin.modules.column_filter', ['filter' => $columnFilter])
                </div>
            @endforeach
        </div>
    </details>
@endif
