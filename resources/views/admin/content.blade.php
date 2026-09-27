<main class="app-main">

  @section('content_header')

    <div class="app-content-header">

      <div class="container-fluid">

        <div class="row">

          <div class="col-sm-6">

            <h3 class="mb-0">

              <i class="@yield('page-icon', 'bi bi-house') me-1"></i>

              @yield('page-name', '')

              @hasSection('page-description')
                <small class="text-body-secondary fs-6">
                  @yield('page-description')
                </small>
              @endif

            </h3>

          </div>


          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-end">

              <li class="breadcrumb-item">

                <a href="{{ route('admin.dashboard.index') }}">
                  {{ trans('admin.home') }}
                </a>

              </li>

              <li
                      class="breadcrumb-item active"
                      aria-current="page"
              >
                @yield('page-name', '')
              </li>

            </ol>

          </div>

        </div>

      </div>

    </div>

  @show


  <div class="app-content">

    <div class="container-fluid">

      @yield('content')

    </div>

  </div>

</main>