<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | {{ trans('passwords.reset_password') }}</title>

    @vite([
        'resources/css/adminlte.css',
        'resources/js/adminlte.js'
    ])
</head>

<body class="login-page bg-body-secondary">

<div class="login-box">

    <div class="login-logo text-center">
        <a href="{{ route('root') }}">
            {{ config('app.name') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">
                {{ trans('passwords.enter_new_password') }}
            </p>

            @include('admin.errors')

            <form action="{{ route('authPasswordPostReset') }}" method="post">
                @csrf
                @honeypot

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mb-3">
                    <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="{{ trans('passwords.email') }}"
                            value="{{ old('email') }}"
                    >

                    <div class="input-group-text">
                        <span class="bi bi-envelope"></span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="{{ trans('passwords.password') }}"
                            id="password"
                    >

                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="{{ trans('passwords.confirm_password') }}"
                            id="confirm_password"
                    >

                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {{ trans('passwords.reset_password') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>