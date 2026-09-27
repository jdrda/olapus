@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'bi bi-speedometer2')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-dashboard', 'active')

@section('content')

@if (!empty($statistics['ga']))
<div class="row">
    <div class="col-lg-3 col-md-6 col-12">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary"><i class="bi bi-person"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.visitors') }} - 7 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['visitors_percent_this_week'] > 0){{ '+' }}@endif{{ $statistics['ga']['visitors_percent_this_week'] }}<small>%</small></span>
                <span class='small text-lowercase'>{{ $statistics['ga']['visitors_this_week'] }} {{ trans('admin_module_dashboard.analytics.visitors') }}</span>
            </div>
            
        </div>
        
    </div>
    
    <div class="col-lg-3 col-md-6 col-12">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary"><i class="bi bi-eye"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.pageviews') }} - 7 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['pageviews_percent_this_week'] > 0){{ '+' }}@endif{{ $statistics['ga']['pageviews_percent_this_week'] }}<small>%</small></span>
                <span class='small text-lowercase'>{{ $statistics['ga']['pageviews_this_week'] }} {{ trans('admin_module_dashboard.analytics.pageviews') }}</span>
            </div>
            
        </div>
        
    </div>

    <div class="col-lg-3 col-md-6 col-12">
        <div class="info-box">
            <span class="info-box-icon text-bg-warning"><i class="bi bi-person"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.visitors') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['visitors_percent_this_month'] > 0){{ '+' }}@endif{{ $statistics['ga']['visitors_percent_this_month'] }}<small>%</small></span>
                <span class='small text-lowercase'>{{ $statistics['ga']['visitors_this_month'] }} {{ trans('admin_module_dashboard.analytics.visitors') }}</span>
            </div>
            
        </div>
        
    </div>
    
    <div class="col-lg-3 col-md-6 col-12">
        <div class="info-box">
            <span class="info-box-icon text-bg-warning"><i class="bi bi-eye"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.pageviews') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['pageviews_percent_this_month'] > 0){{ '+' }}@endif{{ $statistics['ga']['pageviews_percent_this_month'] }}<small>%</small></span>
                <span class='small text-lowercase'>{{ $statistics['ga']['pageviews_this_month'] }} {{ trans('admin_module_dashboard.analytics.pageviews') }}</span>
            </div>
            
        </div>
        
    </div>
    
</div>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">{{ trans('admin_module_dashboard.analytics.top_referers') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</h3>
            </div>
            
            <div class="card-body">
                <div class="table-responsive"><table class="table table-borderless table-sm table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">
                                {{ trans('admin_module_dashboard.analytics.url') }}
                            </th>
                            <th class="text-end">
                                {{ trans('admin_module_dashboard.analytics.pageviews') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statistics['ga']['top_referers'] as $referer)
                        <tr>
                            <td>
                                {{ $referer['url'] }}
                            </td>
                            <td class='text-end'>
                                {{ $referer['pageViews'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
            </div>

          </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">{{ trans('admin_module_dashboard.analytics.visits_and_pageviews') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</h3>
            </div>
            
            <div class="card-body">
                <div id='chart30days'></div>
            </div>

          </div>
    </div>
</div>
@else
    <div class="card">
        <div class="card-body">
            <h3 class="card-title float-none">{{ trans($moduleNameBlade . '.name') }}</h3>
            <p class="text-body-secondary mb-0">{{ trans('admin.statistics_unavailable') }}</p>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if (!empty($statistics['ga']['visitors_pageviews_chart']))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const target = document.getElementById('chart30days');
    if (!target || !window.ApexCharts) return;

    const rows = [
        @foreach ($statistics['ga']['visitors_pageviews_chart'] as $object)
            {
                day: {{ \Illuminate\Support\Js::from($object['date']->format(trans('locale.date_format_without_year'))) }},
                visitors: {{ (int) $object['visitors'] }},
                pageViews: {{ (int) $object['pageViews'] }},
            },
        @endforeach
    ];

    new window.ApexCharts(target, {
        chart: { type: 'area', height: 300, toolbar: { show: false } },
        series: [
            { name: {{ \Illuminate\Support\Js::from(trans('admin_module_dashboard.analytics.visitors')) }}, data: rows.map(row => row.visitors) },
            { name: {{ \Illuminate\Support\Js::from(trans('admin_module_dashboard.analytics.pageviews')) }}, data: rows.map(row => row.pageViews) },
        ],
        xaxis: { categories: rows.map(row => row.day) },
        yaxis: { min: 0 },
        dataLabels: { enabled: false },
        legend: { position: 'top' },
    }).render();
});
</script>
@endif
@endpush
