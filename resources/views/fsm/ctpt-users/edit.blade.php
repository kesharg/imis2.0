@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
@include('layouts.components.error-list')
@include('layouts.components.success-alert')
@include('layouts.components.error-alert')
<div class="card card-info">
	{!! Form::model($info, ['method' => 'PATCH', 'action' => ['Fsm\CtptUserController@update', $info], 'class' => 'form-horizontal']) !!}
		@include('fsm/ctpt-users.partial-form', ['submitButtomText' => 'Update'])
	{!! Form::close() !!}
</><!-- /.card -->
@stop