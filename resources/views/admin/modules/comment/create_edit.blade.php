@extends('admin.master')

@section('page-name', trans($moduleNameBlade . '.name') )

@section('page-icon', 'fa fa-comments-o')

@section('page-description', trans($moduleNameBlade . '.description'))

@section('menu-class-publishing', 'active')
@section('menu-class-comment', 'active')

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

                    </div>

                </div>


                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <!-- Form -->
                        <form action="@if(isset($results->_method)){{ route($moduleBasicRoute . '.update', $results->id) }}@else{{ route($moduleBasicRoute . '.store') }}@endif" method="post">
                            {!! csrf_field() !!}
                            @if(isset($results->_method))
                            <input type="hidden" name="_method" value="{{ $results->_method }}">
                            @endif

                            <div class="form-group has-feedback">
                                <label for='color'>{{ trans($moduleNameBlade . '.fields.text') }}</label>
                                <textarea name='text' class='form-control html' rows="23">{!! $results->text ?? '' !!}</textarea>
                                <span class="fa fa-align-left form-control-feedback"></span>
                            </div>
                    </div>
                

                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group has-feedback">
                            <label for='page'>{{ trans($moduleNameBlade . '.fields.page') }}</label>
                            <input type="text" name="page" class="form-control" value="{{ $results->pages->name ?? '-' }}" disabled>
                            <span class="fa fa-sticky-note-o form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for='article'>{{ trans($moduleNameBlade . '.fields.article') }}</label>
                            <input type="text" name="article" class="form-control" value="{{ $results->articles->name ?? '-' }}" disabled>
                            <span class="fa fa-newspaper-o form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for='headline'>{{ trans($moduleNameBlade . '.fields.headline') }} *</label>
                            <input type="text" name="headline" class="form-control" value="{{ $results->headline ?? old('headline') }}">
                            <span class="fa fa-header form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for='name'>{{ trans($moduleNameBlade . '.fields.name') }}</label>
                            <input type="name" name="name" class="form-control" value="{{ $results->name ?? old('name') }}">
                            <span class="fa fa-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for='email'>{{ trans($moduleNameBlade . '.fields.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ $results->email ?? old('email') }}">
                            <span class="fa fa-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for='url'>{{ trans($moduleNameBlade . '.fields.url') }}</label>
                            <input type="url" name="url" class="form-control" value="{{ $results->url ?? old('url') }}">
                            <span class="fa fa-anchor form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label for='commentstatus_id'>{{ trans($moduleNameBlade . '.fields.commentstatus') }} * </label>
                            <select name="commentstatus_id" class='form-control select2'>                      
                                @foreach (request('CommentStatus') as $status) 
                                <option value="{{ $status->id }}" @if(isset($results) == TRUE and $results->commentstatus_id == $status->id)selected @endif>{{ $status->name }}</option>                                 
                                @endforeach
                            </select> 
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group text-right">
                            <button type='submit' name='submit' class='btn btn-primary btn-flat'>{{ trans('admin.save') }}</button>
                        </div>
                        </form>
                    </div>
                </div>

                <!-- /Form -->

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection

@section('foot')

@parent

<script>
    $(function () {
        $('select').select2();

    });

</script>

@endsection
