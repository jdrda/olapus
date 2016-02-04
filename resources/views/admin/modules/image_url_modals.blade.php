<!-- URL modals -->
@foreach ($results as $result)
<div class='example-modal' >
    <div class='modal modal-info fade url_modal' id='imageURLModal{{ $result->id }}' tabindex='-1'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ trans('admin_module_image.url_modal.url_of') }} {{ $result->name }}</h4>
                </div>
                <div class='modal-body text-center'>
                    <div class="row">
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                            <input type="text" name="url" class="form-control" value="{{ route('getImage', ['imageName' => $result->url, 'imageExtension' => $result->image_extension]) }}">
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h3>{{ trans('admin_module_image.url_modal.instruction') }}</h3>
                            <p>{{ trans('admin_module_image.url_modal.subinstruction') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endforeach
<!-- /URL modals -->