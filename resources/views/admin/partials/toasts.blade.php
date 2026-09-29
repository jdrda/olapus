@php
    $toasts = [
        'success' => [
            'title' => trans('admin.toast.success'),
            'border' => 'border-success',
            'icon' => 'bi-check-circle-fill text-success',
        ],
        'error' => [
            'title' => trans('admin.toast.error'),
            'border' => 'border-danger',
            'icon' => 'bi-exclamation-octagon-fill text-danger',
        ],
        'warning' => [
            'title' => trans('admin.toast.warning'),
            'border' => 'border-warning',
            'icon' => 'bi-exclamation-triangle-fill text-warning',
        ],
        'info' => [
            'title' => trans('admin.toast.info'),
            'border' => 'border-info',
            'icon' => 'bi-info-circle-fill text-info',
        ],
    ];
@endphp

@if (collect(array_keys($toasts))->contains(fn ($key) => session()->has($key)))
    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5" style="z-index: 1090;">

        @foreach ($toasts as $key => $toast)
            @if (session()->has($key))
                <div class="toast border-start border-4 {{ $toast['border'] }} shadow"
                     role="alert"
                     aria-live="polite"
                     aria-atomic="true"
                     data-olapus-toast
                     data-bs-autohide="true"
                     data-bs-delay="5000">

                    <div class="toast-header">
                        <i class="bi {{ $toast['icon'] }} me-2"></i>

                        <strong class="me-auto">
                            {{ $toast['title'] }}
                        </strong>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="toast"
                                aria-label="{{ trans('admin.close') }}"></button>
                    </div>

                    <div class="toast-body">
                        {{ session($key) }}
                    </div>

                </div>
            @endif
        @endforeach

    </div>
@endif
