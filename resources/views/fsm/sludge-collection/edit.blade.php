<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
	<div class="card-header with-border">
		<h3 class="card-title">Application ID: {{ $sludgeCollection->application_id }}</h3>
	</div><!-- /.card-header -->
	{!! Form::model($sludgeCollection, ['method' => 'PATCH', 'action' => ['Fsm\SludgeCollectionController@update', $sludgeCollection->id], 'files' => true, 'class' => 'form-horizontal']) !!}
		@include('fsm.sludge-collection.partial-form', ['submitButtomText' => 'Update'])
	{!! Form::close() !!}
</div><!-- /.card -->
@stop