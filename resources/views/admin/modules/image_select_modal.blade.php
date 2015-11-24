<!-- Image detail modals -->
<div class='example-modal' >
    <div class='modal modal-default fade' id='imageSelectModal' tabindex='-1'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ trans('admin.select_image') }}</h4>
                </div>
                <div class='modal-body text-center'>
                    <div class="row">
                        @foreach(request('other_tables')['Image'] as $image)
                    
                        <div class="col-xs-3 col-sm-3 col-md-2">
                            <div class="caption" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis" data-toggle="tooltip" data-placement="top" title="{{ $image->name }}"><small>{{ $image->name }}</small></div>
                            
                            <div class="thumbnail thumbnail-select" style="height: 100px;">
                                <a href='#'>
                                    <img data-original="{{ route('getImage', ['imageName' => $image->url, 'imageExtension' => $image->image_extension]) }}" alt="{{ $image->name }}" class="lazy" style="max-height: 80px;"> 
                                </a>
                                <input type='hidden' class='thumbnail_id' value='{{ $image->id }}'>
                                <input type='hidden' class='thumbnail_name' value='{{ $image->name }}'>
                            </div>
                        </div>
                    
                        @endforeach
                    </div>
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
<!-- /Image detail modals -->