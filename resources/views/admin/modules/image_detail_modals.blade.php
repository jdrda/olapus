<!-- Delete modals -->
@foreach ($results as $result)
<div class='example-modal' >
    <div class='modal modal-default' id='imageDetailModal{{ $result->id }}'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ $result->name }}</h4>
                </div>
                <div class='modal-body'>
                    <img src="{{ route('getImage', ['imageName' => $result->url, 'imageExtension' => $result->image_extension]) }}" alt="{{ $result->name }}" class="img-responsive">
                </div>
                <div class='modal-footer'>
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