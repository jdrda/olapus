@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-dashboard')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-dashboard', 'active')

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
@endif
@endsection