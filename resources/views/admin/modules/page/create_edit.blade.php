@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-sticky-note-o')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('delete_confirmation_text', trans($moduleNameBlade . '.delete_row_confirmation'))


@section('menu-class-publishing', 'active')
@section('menu-class-page', 'active')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    {{ trans($moduleNameBlade . '.name') }}
                </h3>


                <div class="box-tools">


                    <!-- Search box DEACTIVATED -->
                    <!--<div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>-->
                    <!-- /Search box DEACTIVATED -->
                </div>

            </div>
            <!-- /.box-header -->
            
            <div class='box-body'>
                <!-- Form -->
                <form action="@if(isset($results->_method)){{ route($moduleBasicRoute . '.update', $results->id) }}@else{{ route($moduleBasicRoute . '.store') }}@endif" method="post">
                    {!! csrf_field() !!}
                    @if(isset($results->_method))
                    <input type="hidden" name="_method" value="{{ $results->_method }}">
                    @endif
                    <div class="row">
                        <div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>
                            @include('admin.errors')
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-12 col-sm-6'>
                            
                            <div class="form-group has-feedback">
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $results->name or old('name') }}" required>
                                <span class="fa fa-key form-control-feedback"></span>
                            </div>

                            <div class="form-group">
                                <label for='pagecategory_id[]'>{{ trans($moduleNameBlade . '.fields.pagecategory') }} * </label>
                                <select name="pagecategory_id[]" class='form-control' multiple="multiple" id='pagecategories'>                      
                                @if(isset($results->pagecategories))
                                    @foreach ($results->pagecategories as $pagecategory) 
                                    <option value="{{ $pagecategory->id }}" selected>{{ $pagecategory->name }}</option>                                 
                                    @endforeach
                                @endif
                                </select> 
                            </div>

                            <div class="form-group has-feedback">
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.author_name') }}</label>
                                <input type="text" name="author_name" id="name" class="form-control" value="{{ $results->author_name or old('author_name') }}">
                                <span class="fa fa-user form-control-feedback"></span>
                            </div>

                            <div class="form-group">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.author') }} * </label>
                                <select name="user_id" class='form-control select2'>
                                    @foreach (request('User') as $user)
                                    <option value="{{ $user->id }}" @if(isset($results) == TRUE and $results->user_id == $user->id)selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <!-- /Form -->
                        </div>
                        <div class='col-xs-12 col-sm-6'>

                            <div class="form-group has-feedback">
                                <label for='meta_title'>{{ trans($moduleNameBlade . '.fields.meta_title') }}</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ $results->meta_title or old('meta_title') }}">
                                <span class="fa fa-header form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='meta_description'>{{ trans($moduleNameBlade . '.fields.meta_description') }}</label>
                                <input type="text" name="meta_description" class="form-control"value="{{ $results->meta_description or old('meta_description') }}">
                                <span class="fa fa-pencil-square-o form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='meta_keywords'>{{ trans($moduleNameBlade . '.fields.meta_keywords') }}</label>
                                <input type="text" name="meta_keywords" class="form-control"value="{{ $results->meta_keywords or old('meta_keywords') }}">
                                <span class="fa fa-flag form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='image_name'>{{ trans($moduleNameBlade . '.fields.image') }}</label>
                                <div class="input-group">
                                     @if(isset($results->images->id))
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#imageDetailModal"><span class='fa fa-hand-pointer-o'></span> {{ trans('admin.show') }}</button>
                                    </div>
                                    @endif
                                     <input type="text" name="image_name" id="image_name" class="form-control" readonly placeholder="No image selected" value='{{ $results->images->name or '' }}'>
                                    <input type="hidden" name="image_id" id="image_id" value='{{ $results->images->id or '' }}'>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#imageSelectModal"><span class="fa fa-th-large"></span> Select image</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='value'>{{ trans($moduleNameBlade . '.fields.url') }} *</label>
                                <input type="text" name="url" id='url' class="form-control" value="{{ $results->url or old('url') }}" required>
                                <span class="fa fa-anchor form-control-feedback"></span>
                            </div>

                            <!-- /Form -->
                        </div>
                    </div>
                     <div class='row'>
                        <div class='col-xs-12'>
                            <div class="form-group has-feedback">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.text') }}</label>
                                <textarea name='text' class='form-control html' rows="10">{!! $results->text or '' !!}</textarea>
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-12'>
                             <div class="form-group text-right">
                                 <button type='submit' name='published' class='btn btn-warning btn-flat' value="1">{{ trans($moduleNameBlade . '.publish') }}</button>
                                <button type='submit' name='submit' class='btn btn-primary btn-flat'>{{ trans('admin.save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection

@section('foot')

@include('admin/modules/image_select_modal')

@if(isset($results->images->id))
<div class='example-modal'>
    <div class='modal modal-default fade' id='imageDetailModal' tabindex='-1'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ $results->images->name }}</h4>
                </div>
                <div class='modal-body text-center'>
                    <img src="{{ route('getImage', ['imageName' => $results->images->url, 'imageExtension' => $results->images->image_extension]) }}" alt="{{ $results->images->name }}" class="img-responsive">
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
@endif
@parent

@if(isset($results->_method) == FALSE)
<script>
$(function() {
    
    // Automatic slugify
    var lastValue = '';
    var urlChanged = false;
    
    setInterval(function() {
        if ($("#name").val() != lastValue && urlChanged == false) {
            lastValue = $("#name").val();
            $('#url').val(getSlug($("#name").val()));
        }
    }, 500);
    
    $('#url').keydown(function(){
        urlChanged = true;
    })
});
</script>
@endif

<script type="text/javascript" src="{{ asset('js/admin/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
tinymce.init({
    selector: ".html",
    theme: "modern",
    entity_encoding : "raw",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "template paste textcolor colorpicker textpattern imagetools"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor",
    image_advtab: true,
    language: '{{ env('TINYMCE_LOCALIZATION') }}',
});
</script>

<script>
    $(function() {
        
        $('select').not('#articlecategories').select2();
        
        // Lazy load of images
        $('#imageSelectModal').on("shown.bs.modal", function () {
                $("img.lazy").lazyload();
        });
        
        $('#pagecategories').select2({
           data: [
                @foreach (request('PageCategory') as $pagecategory) 
                { id: {{ $pagecategory->id }}, text: '{{ $pagecategory->name }}' },
                @endforeach
           ] 
        });
        
      
        
        // Select an image
        $('.thumbnail-select a').click( function(){
            
            name = $(this).closest('div').find('.thumbnail_name').val();
            id = $(this).closest('div').find('.thumbnail_id').val();
            $('#image_id').val(id);
            $('#image_name').val(name);
            $('#imageSelectModal').modal('toggle');
        });
    });
    
</script>

@endsection
