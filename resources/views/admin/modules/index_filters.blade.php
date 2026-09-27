{{-- Use for filters whose relations have no separate column in this table. --}}
@if (!empty($extraFilterControls))
    <div class="row g-3 mb-3">
        @foreach ($extraFilterControls as $filter)
            <div class="col-12 col-md-4">
                <span class="small fw-semibold">{{ $filter['label'] }}</span>
                @include('admin.modules.index_filter', ['filter' => $filter])
            </div>
        @endforeach
    </div>
@endif
