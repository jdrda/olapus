@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-image')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-media', 'active')
@section('menu-class-image', 'active')

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
                        <form action="@if(isset($results->_method)){{ route($moduleBasicRoute . '.update', $results->id) }}@else{{ route($moduleBasicRoute . '.store') }}@endif" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            @if(isset($results->_method))
                            <input type="hidden" name="_method" value="{{ $results->_method }}">
                            @endif
                            <div class="form-group has-feedback">
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.image') }} @if(isset($results->_method) == FALSE) * @endif</label>
                                @if(isset($results))<div class="input-group"> @endif
                                    <input type="file" name="image" class="form-control" @if(isset($results->_method) == FALSE) required @endif>
                                    <span class="fa fa-image form-control-feedback"></span>
                                    @if(isset($results))
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#imageDetailModal"><span class='fa fa-hand-pointer-o'></span> {{ trans('admin.show') }}</button>
                                    </div>
                                    @endif
                                @if(isset($results))</div> @endif
                            </div>
                            <div class="form-group has-feedback">
                                <label for='name'>{{ trans($moduleNameBlade . '.fields.name') }} *</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $results->name ?? old('name') }}" required>
                                <span class="fa fa-key form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='alt'>{{ trans($moduleNameBlade . '.fields.alt') }}</label>
                                <input type="text" name="alt" class="form-control" value="{{ $results->alt ?? old('alt') }}">
                                <span class="fa fa-code form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='value'>{{ trans($moduleNameBlade . '.fields.url') }}</label>
                                <input type="text" name="url" id='url' class="form-control" value="{{ $results->url ?? old('url') }}">
                                <span class="fa fa-anchor form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for='description'>{{ trans($moduleNameBlade . '.fields.description') }}</label>
                                <input type="text" name="description" class="form-control" value="{{ $results->description ?? old('description') }}">
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.category') }} * </label>
                                <select name="imagecategory_id" class='form-control select2'>                      
                                    @foreach (request('ImageCategory') as $imagecategory) 
                                    <option value="{{ $imagecategory->id }}" @if(isset($results) == TRUE and $results->imagecategory_id == $imagecategory->id)selected @endif>{{ $imagecategory->name }}</option>                                 
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
@parent

@if(isset($results))
<div class='example-modal'>
    <div class='modal modal-default fade' id='imageDetailModal' tabindex='-1'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='{{ trans('close') }}'>
                        <span aria-hidden='true'>×</span></button>
                    <h4 class='modal-title'>{{ $results->name }}</h4>
                </div>
                <div class='modal-body text-center'>
                    <img src="{{ route('getImage', ['imageName' => $results->url, 'imageExtension' => $results->image_extension]) }}" alt="{{ $results->name }}" class="img-responsive img-index">
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
<script>
    $(function() {
        $('select').select2();
    });
</script>
@if(isset($results->_method) ==  FALSE)
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
@endsection
