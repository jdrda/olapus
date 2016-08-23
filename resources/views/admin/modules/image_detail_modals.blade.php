<!-- Image detail modals -->
@foreach ($results as $result)
<div class='example-modal' >
    <div class='modal modal-default fade' id='imageDetailModal{{ $result->id }}' tabindex='-1'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ $result->name }}</h4>
                </div>
                <div class='modal-body text-center'>
                    <img src="{{ route('getImage', ['imageName' => $result->url, 'imageExtension' => $result->image_extension]) }}" alt="{{ $result->name }}" class="img-responsive">
                </div>
                <div class='modal-footer'>
                    <table class="table table-bordered table-responsive table-hover table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('admin_module_image.detail_modal.width') }}
                                </th>
                                <td>
                                    {{ $result->image_width }} {{ trans('admin_module_image.detail_modal.px') }}
                                </td>
                                <th>
                                    {{ trans('admin_module_image.detail_modal.type') }}
                                </th>
                                <td>
                                    {{ $result->image_mime_type }}
                                </td>
                            </tr>
                             <tr>
                                <th>
                                    {{ trans('admin_module_image.detail_modal.height') }}
                                </th>
                                <td>
                                    {{ $result->image_height }} {{ trans('admin_module_image.detail_modal.px') }}
                                </td>
                                <th>
                                    {{ trans('admin_module_image.detail_modal.original_name') }}
                                </th>
                                <td>
                                    {{ $result->image_original_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('admin_module_image.detail_modal.size') }}
                                </th>
                                <td>
                                    {{ \App\Helpers::formatByteSize($result->image_size) }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@endforeach
<!-- /Image detail modals -->
