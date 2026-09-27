<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | {{ trans('login.sign_in') }}</title>

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
                {{ trans('login.headline') }}
            </p>

            @include('admin.errors')
            <form action="{{ route('authPostLogin') }}" method="post">
                @csrf
                @honeypot

                <div class="input-group mb-3">
                    <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="{{ trans('login.email') }}"
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
                            placeholder="{{ trans('login.password') }}"
                            id="password"
                    >

                    <div class="input-group-text">
                        <span class="bi bi-lock-fill"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="form-check">
                            <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                            >

                            <label class="form-check-label" for="remember">
                                {{ trans('login.remeber_me') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ trans('login.sign_in') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3">
                <a href="{{ route('authPasswordEmailGet') }}">
                    {{ trans('login.lost_password') }}
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>