@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-dashboard')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-dashboard', 'active')

@section('google_charts')
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['{{ trans('admin_module_dashboard.analytics.day') }}', '{{ trans('admin_module_dashboard.analytics.visitors') }}', '{{ trans('admin_module_dashboard.analytics.pageviews') }}'],
          
            @foreach($statistics['ga']['visitors_pageviews_chart'] as $object)
          ['{{ $object['date']->format(trans("locale.date_format_without_year")) }}',  {{ $object['visitors'] }},      {{ $object['pageViews'] }}],
          @endforeach
          
        ]);

        var options = {
          vAxis: {minValue: 0},
          legend: {position: 'top', maxLines: 3},
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart30days'));
        chart.draw(data, options);
      }
    </script>
@endsection

@section('content')

@if(env('ANALYTICS_ENABLED') == 1)
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-person-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.visitors') }} - 7 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['visitors_percent_this_week'] > 0){{ '+' }}@endif{{ $statistics['ga']['visitors_percent_this_week'] }}<small>%</small></span>
                <span class='info-box-more text-lowercase'>{{ $statistics['ga']['visitors_this_week'] }} {{ trans('admin_module_dashboard.analytics.visitors') }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-eye-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.pageviews') }} - 7 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['pageviews_percent_this_week'] > 0){{ '+' }}@endif{{ $statistics['ga']['pageviews_percent_this_week'] }}<small>%</small></span>
                <span class='info-box-more text-lowercase'>{{ $statistics['ga']['pageviews_this_week'] }} {{ trans('admin_module_dashboard.analytics.pageviews') }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-person-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.visitors') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['visitors_percent_this_month'] > 0){{ '+' }}@endif{{ $statistics['ga']['visitors_percent_this_month'] }}<small>%</small></span>
                <span class='info-box-more text-lowercase'>{{ $statistics['ga']['visitors_this_month'] }} {{ trans('admin_module_dashboard.analytics.visitors') }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-eye-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('admin_module_dashboard.analytics.pageviews') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</span>
                <span class="info-box-number">@if($statistics['ga']['pageviews_percent_this_month'] > 0){{ '+' }}@endif{{ $statistics['ga']['pageviews_percent_this_month'] }}<small>%</small></span>
                <span class='info-box-more text-lowercase'>{{ $statistics['ga']['pageviews_this_month'] }} {{ trans('admin_module_dashboard.analytics.pageviews') }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('admin_module_dashboard.analytics.top_referers') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-responsive table-borderless table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-left">
                                {{ trans('admin_module_dashboard.analytics.url') }}
                            </th>
                            <th class="text-right">
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
                            <td class='text-right'>
                                {{ $referer['pageViews'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            
          </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ trans('admin_module_dashboard.analytics.visits_and_pageviews') }} - 30 {{ trans('admin_module_dashboard.analytics.days') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id='chart30days'></div>
            </div>
            <!-- /.box-body -->
            
          </div>
    </div>
</div>
@endif
@endsection