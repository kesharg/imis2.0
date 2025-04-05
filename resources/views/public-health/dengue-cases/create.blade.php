@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
@include('layouts.components.error-list')
@include('layouts.components.success-alert')
@include('layouts.components.error-alert')
<div class="card card-info">
	{!! Form::open(['url' => 'publichealth/dengue-cases', 'class' => 'form-horizontal']) !!}
		@include('public-health/dengue-cases.partial-form', ['submitButtomText' => 'Save'])
	{!! Form::close() !!}
</div><!-- /.card -->
@stop