<aside
        class="app-sidebar bg-body-secondary shadow"
        data-bs-theme="dark"
>

    {{-- Brand --}}
    <div class="sidebar-brand">

        <a href="{{ route('admin.dashboard.index') }}"
           class="brand-link">

            <span class="brand-text fw-light">
                {{ config('app.name') }}
            </span>

        </a>

    </div>


    {{-- Optional user panel --}}
    @section('sidebar_user')

        <div class="p-3 d-flex align-items-center gap-2">

            <img
                    src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                    class="rounded-circle"
                    width="36"
                    height="36"
                    alt="{{ auth()->user()->name }}"
            >

            <div>
                {{ auth()->user()->name }}
            </div>

        </div>

    @show


    {{-- Sidebar search --}}
    @section('sidebar_search')

        <div class="sidebar-search px-3 pb-3">

            <label
                    for="sidebar-search-input"
                    class="visually-hidden"
            >
                {{ trans('admin.search') }}
            </label>

            <input
                    type="search"
                    id="sidebar-search-input"
                    class="form-control form-control-sm"
                    placeholder="{{ trans('admin.search') }}"
                    autocomplete="off"
                    data-lte-toggle="sidebar-search"
                    data-lte-target="#admin-navigation"
            >

        </div>

    @show


    <div class="sidebar-wrapper">

        @section('sidebar_menu')

            <nav class="mt-2">

                <ul
                        id="admin-navigation"
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                        data-accordion="false"
                >

                    <li class="nav-header">
                        {{ strtoupper(trans('admin.menu_header')) }}
                    </li>


                    @foreach (config('admin.menu') as $item)

                        @if(empty($item['items']))

                            <li class="nav-item">

                                <a
                                        href="{{ route($item['route']) }}"
                                        class="nav-link @yield($item['class'])"
                                >

                                    <i class="nav-icon {{ $item['icon'] }}"></i>

                                    <p>
                                        {{ trans($item['name']) }}
                                    </p>

                                </a>

                            </li>

                        @else

                            <li class="nav-item @yield($item['class'])">

                                <a
                                        href="#"
                                        class="nav-link"
                                >

                                    <i class="nav-icon {{ $item['icon'] }}"></i>

                                    <p>
                                        {{ trans($item['name']) }}

                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>

                                </a>


                                <ul class="nav nav-treeview">

                                    @foreach($item['items'] as $subitem)

                                        <li class="nav-item">

                                            <a
                                                    href="{{ route($subitem['route']) }}"
                                                    class="nav-link @yield($subitem['class'])"
                                            >

                                                <i class="nav-icon {{ $subitem['icon'] }}"></i>

                                                <p>
                                                    {{ trans($subitem['name']) }}
                                                </p>

                                            </a>

                                        </li>

                                    @endforeach

                                </ul>

                            </li>

                        @endif

                    @endforeach

                </ul>

            </nav>

        @show

    </div>

</aside>