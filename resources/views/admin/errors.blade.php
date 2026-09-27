@if ($errors->any())

    <div class="alert alert-danger">

        <h5 class="alert-heading">

            <i class="bi bi-exclamation-octagon me-1"></i>

            {{ trans('login.error') }}

        </h5>

        <div>
            {{ trans('login.problems_with_input') }}
        </div>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif