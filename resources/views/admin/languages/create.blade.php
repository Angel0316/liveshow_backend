@extends('layouts.admin')

@section('title', tr('create_language'))

@section('content-header', tr('languages'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.languages.index')}}"><i class="fa fa-globe"></i> {{tr('languages')}}</a></li>
    <li class="active"><i class="fa fa-plus"></i> {{tr('create_language')}}</li>
@endsection

@section('content')

  	<div class="row">

	    <div class="col-md-10">
   			
   			@include('notification.notify')

	        <div class="box box-primary">

	            <div class="box-header label-primary">
	            	<b>@yield('title')</b>
	                <a href="{{route('admin.languages.index')}}" style="float:right" class="btn btn-default"><i class="fa fa-eye"></i> {{tr('view_languages')}}</a>
	            </div>

	            @include('admin.languages._form')

	        </div>

	    </div>

	</div>
   
@endsection

