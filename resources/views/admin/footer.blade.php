<footer class="app-footer">

  <div class="float-end d-none d-sm-inline">

    @yield('right_footer', '')

  </div>


  @section('copyright')

    <strong>

      {{ trans('admin.copyright') }}
      &copy;
      {{ date('Y') }}

      <a
              href="{{ config('olapus.vendor.company_url') }}"
              target="_blank"
              rel="noopener"
      >
        {{ config('olapus.vendor.company_name') }}
      </a>.

    </strong>

    {{ trans('admin.all_rights_reserved') }}.

    {{ trans('admin.developed_on') }}

    <a
            href="https://www.olapus.com?version={{ config('olapus.version') }}"
            rel="nofollow"
            target="_blank"
    >
      Olapus
    </a>.

    @if(config('app.debug'))

      <small>
        |
        Used memory:
        {{ \App\Helpers::formatByteSize(memory_get_peak_usage()) }}
      </small>

    @endif

  @show

</footer>