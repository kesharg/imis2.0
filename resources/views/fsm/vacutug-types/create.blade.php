<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
@include('layouts.components.error-list')
@include('layouts.components.success-alert')
@include('layouts.components.error-alert')
<div class="card card-info">
	{!! Form::open(['url' => 'fsm/desludging-vehicles', 'class' => 'form-horizontal']) !!}
		@include('fsm/vacutug-types.partial-form', ['submitButtomText' => 'Save'])
	{!! Form::close() !!}
</div><!-- /.box -->
@stop