<!-- Delete modals -->
@foreach ($results as $result)
<div class='example-modal' >
    <div class='modal modal-danger' id='deleteModal{{ $result->id }}'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ trans('admin.delete_row') }} {{ $result->id }}</h4>
                </div>
                <div class='modal-body'>
                    <p>@yield('delete_confirmation_text') <strong>{{ $result->name }}</strong>?</p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline pull-left' data-dismiss='modal'>{{ trans('admin.close') }}</button>
                    <form action="{{ route(str_replace(["/", env('APP_ADMIN_URL', 0)], [".", 'admin'], Route::getCurrentRoute()->getPath()).".destroy", $result->id) }}" method="post" role="form" class="form">
                        <input type="hidden" name="_method" value="delete">
                        {!! csrf_field() !!}
                        <button type='submit' class='btn btn-outline'>{{ trans('admin.delete') }}</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endforeach
<!-- /Delete modals -->