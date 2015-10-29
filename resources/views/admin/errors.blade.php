<!-- Possible alerts -->
@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>{{ trans('login.error') }}</strong> {{ trans('login.problems_with_input') }}.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<!-- /Possible alerts -->