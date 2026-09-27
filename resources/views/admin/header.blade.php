<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">

        {{-- Left navbar --}}
        <ul class="navbar-nav">

            {{-- Sidebar toggle --}}
            <li class="nav-item">
                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="#"
                   role="button">

                    <i class="bi bi-list"></i>

                </a>
            </li>


            {{-- Configured top menu --}}
            @section('upper_menu')

                @foreach (config('admin.top_menu', []) as $item)

                    @if(empty($item['items']))

                        <li class="nav-item">

                            <a
                                    href="{{ route($item['route']) }}"
                                    class="nav-link @yield($item['class'])"
                            >
                                <i class="{{ $item['icon'] }}"></i>

                                <span class="d-none d-xl-inline ms-1">
                                    {{ trans($item['name']) }}
                                </span>
                            </a>

                        </li>

                    @else

                        <li class="nav-item dropdown">

                            <a
                                    href="#"
                                    class="nav-link dropdown-toggle @yield($item['class'])"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                            >
                                <i class="{{ $item['icon'] }}"></i>

                                <span class="d-none d-xl-inline ms-1">
                                    {{ trans($item['name']) }}
                                </span>
                            </a>


                            <ul class="dropdown-menu">

                                @foreach($item['items'] as $subitem)

                                    <li>
                                        <a
                                                href="{{ route($subitem['route']) }}"
                                                class="dropdown-item @yield($subitem['class'])"
                                        >
                                            <i class="{{ $subitem['icon'] }} me-2"></i>
                                            {{ trans($subitem['name']) }}
                                        </a>
                                    </li>

                                @endforeach

                            </ul>

                        </li>

                    @endif

                @endforeach

            @show


        </ul>


        {{-- Right navbar --}}
        <ul class="navbar-nav ms-auto">

            {{-- Optional messages --}}
            @section('messages')
            @show

            {{-- Optional notifications --}}
            @section('notifications')
            @show

            {{-- Optional tasks --}}
            @section('tasks')
            @show


            {{-- Fullscreen --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="#"
                   data-lte-toggle="fullscreen">

                    <i data-lte-icon="maximize"
                       class="bi bi-arrows-fullscreen"></i>

                    <i data-lte-icon="minimize"
                       class="bi bi-fullscreen-exit"
                       style="display: none;"></i>

                </a>

            </li>


            {{-- User menu --}}
            @section('user_menu')

                <li class="nav-item dropdown user-menu">

                    {{-- User menu toggle --}}
                    <a
                            href="#"
                            class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                    >
                        <img
                                src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                class="user-image rounded-circle shadow"
                                width="32"
                                height="32"
                                alt="{{ auth()->user()->name }}"
                        >

                        <span class="d-none d-md-inline">
                {{ auth()->user()->name }}
            </span>
                    </a>


                    {{-- User dropdown --}}
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

                        {{-- User header --}}
                        <li class="user-header text-bg-primary text-center p-3">

                            <img
                                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                    class="rounded-circle shadow mb-2"
                                    width="90"
                                    height="90"
                                    alt="{{ auth()->user()->name }}"
                            >

                            <p class="mb-0">
                                {{ auth()->user()->name }}
                            </p>

                            @if(auth()->user()->email)
                                <small class="d-block">
                                    {{ auth()->user()->email }}
                                </small>
                            @endif

                            @if(auth()->user()->created_at)
                                <small class="d-block mt-1">
                                    {{ trans('admin.member_since') }}
                                    {{ auth()->user()->created_at->format('m/Y') }}
                                </small>
                            @endif

                        </li>


                        {{-- User body --}}
                        <li class="user-body p-3">

                            <div class="text-center text-body-secondary small">
                                {{ config('app.name') }}
                            </div>

                        </li>


                        {{-- User footer --}}
                        <li class="user-footer d-flex align-items-center px-3 py-2">

                            <a
                                    href="{{ route('admin.dashboard.index') }}"
                                    class="btn btn-outline-secondary"
                            >
                                <i class="bi bi-speedometer2 me-1"></i>
                                {{ trans('admin.home') }}
                            </a>

                            <form
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="ms-auto mb-0"
                            >
                                @csrf

                                <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                >
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    {{ trans('login.sign_out') }}
                                </button>

                            </form>

                        </li>

                    </ul>

                </li>

            @show

        </ul>

    </div>

</nav>