<!-- Possible alerts -->
@if (count($warnings) > 0)
<div class="alert alert-warning">
    <h4><i class="icon fa fa-warning"></i>{{ trans('admin.warning') }}</h4>
    <ul>
        @foreach ($warnings as $warning)
        <li>
            {{ trans('warnings.' . $warning) }}
        </li>
        @endforeach
    </ul>
</div>
@endif
<!-- /Possible alerts -->
