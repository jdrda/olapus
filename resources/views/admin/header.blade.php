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
                @php($supportUrl = config('olapus.support_url'))

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
                    <ul class="dropdown-menu dropdown-menu-end p-0 olapus-user-menu">

                        {{-- User identity --}}
                        <li class="d-flex align-items-center gap-3 px-3 py-3 border-bottom">
                            <img
                                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                                    class="rounded-circle shadow-sm flex-shrink-0"
                                    width="44"
                                    height="44"
                                    alt="{{ auth()->user()->name }}"
                            >

                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate">
                                    {{ auth()->user()->name }}
                                </div>

                                @if(auth()->user()->email)
                                    <div class="small text-body-secondary text-truncate">
                                        {{ auth()->user()->email }}
                                    </div>
                                @endif
                            </div>
                        </li>

                        {{-- Profile actions --}}
                        <li class="p-2">
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item rounded-2">
                                <i class="bi bi-person-gear me-2"></i>
                                {{ trans('admin.user_menu.profile') }}
                            </a>

                            <a href="{{ route('admin.profile.edit') }}#password" class="dropdown-item rounded-2">
                                <i class="bi bi-key me-2"></i>
                                {{ trans('admin.user_menu.change_password') }}
                            </a>
                        </li>

                        <li><hr class="dropdown-divider my-0"></li>

                        {{-- UI / support --}}
                        <li class="p-2">
                            <button
                                    type="button"
                                    class="dropdown-item rounded-2 d-flex align-items-center"
                                    data-olapus-theme-toggle
                            >
                                <i class="bi bi-moon-stars me-2" data-olapus-theme-icon></i>
                                <span>{{ trans('admin.user_menu.theme') }}</span>
                            </button>

                            @if($supportUrl)
                                <a
                                        href="{{ $supportUrl }}"
                                        class="dropdown-item rounded-2 d-flex align-items-center"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                >
                                    <i class="bi bi-life-preserver me-2"></i>
                                    <span>{{ trans('admin.user_menu.support') }}</span>
                                    <i class="bi bi-box-arrow-up-right ms-auto small"></i>
                                </a>
                            @endif
                        </li>

                        <li><hr class="dropdown-divider my-0"></li>

                        {{-- Sign out --}}
                        <li class="p-2">
                            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                @csrf

                                <button type="submit" class="dropdown-item rounded-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ trans('admin.user_menu.sign_out') }}
                                </button>
                            </form>
                        </li>

                    </ul>

                </li>

            @show

        </ul>

    </div>

</nav>
