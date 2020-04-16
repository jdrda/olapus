<!-- Possible alerts -->
@if (count($successes) > 0)
<div class="alert alert-success">
    <h4><i class="icon fa fa-check"></i>{{ trans('admin.success') }}</h4>
    <ul>
        @foreach ($successes as $success)
        <li>
            {{ trans('successes.' . $success) }}
        </li>
        @endforeach
    </ul>
</div>
@endif
<!-- /Possible alerts -->
