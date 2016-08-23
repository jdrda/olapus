@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-slideshare')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-components', 'active')
@section('menu-class-slide', 'active')

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
                <div class='row'>
                    <div class='col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>

                        @include('admin.errors')

                        <!-- Form -->
                        <form action="@if(isset($results->_method)){{ route($moduleBasicRoute . '.update', $results->id) }}@else{{ route($moduleBasicRoute . '.store') }}@endif" method="post">
                            {!! csrf_field() !!}
                            @if(isset($results->_method))
                            <input type="hidden" name="_method" value="{{ $results->_method }}">
                            @endif
                            <div class="form-group has-feedback">
                                <label for='image_name'>{{ trans($moduleNameBlade . '.fields.image') }} *</label>
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
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                                <input type="text" name="name" class="form-control" value="{{ $results->name or old('name') }}" required>
                                <span class="fa fa-key form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='description'>{{ trans($moduleNameBlade . '.fields.description') }}</label>
                                <input type="text" name="description" class="form-control" value="{{ $results->description or old('description') }}">
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='caption'>{{ trans($moduleNameBlade . '.fields.caption') }}</label>
                                <input type="text" name="caption" class="form-control" value="{{ $results->caption or old('caption') }}">
                                <span class="fa fa-header form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='text'>{{ trans($moduleNameBlade . '.fields.text') }}</label>
                                <input type="text" name="text" class="form-control" value="{{ $results->text or old('text') }}">
                                <span class="fa fa-indent form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='text'>{{ trans($moduleNameBlade . '.fields.position') }} *</label>
                                <input type="number" step='1' name="position" class="form-control" value="{{ isset($results->position) ? $results->position : ((old('position') !== NULL) ? old('position') : '1') }}" required>
                                <span class="fa fa-sort-amount-asc form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.slider') }} * </label>
                                <select name="slider_id" class='form-control select2'>                      
                                    @foreach (request('Slider') as $slider) 
                                    <option value="{{ $slider->id }}" @if(isset($results) == TRUE and $results->slider_id == $slider->id)selected @endif>{{ $slider->name }}</option>                                 
                                    @endforeach
                                </select> 
                            </div>
                            
                            <div class="form-group text-right">
                                <button type='submit' name='submit' class='btn btn-primary btn-flat'>{{ trans('admin.save') }}</button>
                            </div>
                        </form>
                        <!-- /Form -->
                    </div>
                </div>
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

<script>
    $(function() {
        $('select').select2();
        
        // Lazy load of images
        $('#imageSelectModal').on("shown.bs.modal", function () {
                $("img.lazy").lazyload();
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
