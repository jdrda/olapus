<!-- Possible alerts -->
@if (count($errors) > 0)
<div class="alert alert-danger">
    <h4><i class="icon fa fa-ban"></i>{{ trans('login.error') }} <small>{{ trans('login.problems_with_input') }}</small></h4>
    <ul>
        @foreach ($errors->all() as $error)
        <li>
            @if($error == 'Please ensure that you are a human!')
            {{ trans('recaptcha.validation') }}
            @else
            {{ $error }}
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endif
<!-- /Possible alerts -->
