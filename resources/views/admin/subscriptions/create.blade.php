@extends('layouts.admin')

@section('title', tr('add_subscription'))

@section('content-header', tr('subscriptions'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.subscriptions.index')}}"><i class="fa fa-key"></i> {{tr('subscriptions')}}</a></li>
    <li class="active"><i class="fa fa-plus"></i>  {{tr('add_subscription')}}</li>
@endsection

@section('content')

@include('notification.notify')

    <div class="row">

        <div class="col-md-10 ">

            <div class="box box-primary">

                <div class="box-header label-primary">
                    <b>@yield('title')</b>
                    <a href="{{route('admin.subscriptions.index')}}" style="float:right" class="btn btn-default"><i class="fa fa-eye"></i> {{tr('view_subscriptions')}}</a>
                </div>

                <form class="form-horizontal" action="{{route('admin.subscriptions.save')}}" method="POST" enctype="multipart/form-data" role="form">

                    <div class="box-body">

                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">{{tr('title')}} * </label>

                            <div class="col-sm-10">
                                <input type="text" required name="title" class="form-control" id="title" value="{{old('title')}}" placeholder="{{tr('title')}}">
                            </div>
                        </div>

                        <div class="form-group">

                            <label for="description" class="col-sm-2 control-label">{{tr('description')}} * </label>

                            <div class="col-sm-10">

                                <textarea class="form-control" name="description" required placeholder="{{tr('description')}}">{{old('description')}}</textarea>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="plan" class="col-sm-2 control-label">{{tr('no_of_months')}} * </label>

                            <div class="col-sm-10">
                                <input type="number" min="1" max="12" pattern="[0-9][0-2]{2}"  required name="plan" class="form-control" id="plan" value="{{old('plan')}}" title="{{tr('enter_plan_month')}}" placeholder="{{tr('no_of_months')}}">
                            </div>
                        </div>

                        <div class="form-group">

                            <label for="amount" class="col-sm-2 control-label">{{tr('amount')}} * </label>

                            <div class="col-sm-10">
                                <input type="number" min="0" oninput="validity.valid||(value='');" required  name="amount" class="form-control" id="amount" placeholder="{{tr('amount')}}" step="any">
                            </div>

                        </div>

                        <div class="form-group">

                            <div class="col-sm-8 col-sm-offset-2">

                                <input type="checkbox" name="popular_status" class="">

                                <label for="mark_popular" class="control-label">
                                    {{tr('mark_popular')}}
                                </label>

                            </div>
                             
                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="" class="btn btn-danger">{{tr('cancel')}}</a>
                        <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
                    </div>
                    
                </form>
            
            </div>

        </div>

    </div>

@endsection