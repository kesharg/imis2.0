<!-- Last Modified Date: 11-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">

    <div class="card-header bg-transparent ">
        <a href="{{ action('UtilityInfo\DrainController@index') }}" class="btn btn-info">Back to List</a>
		{{-- <a href="{{ action('UtilityInfo\DrainController@create') }}" class="btn btn-info">Create new Roadline</a> --}}
    </div>

	<div class="form-horizontal">
		<div class="card-body">

			<div class="form-group row">
				{!! Form::label('code','Code',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->code,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('road_code','Road Code',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->road_code,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('surface_type','Surface Type',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->surface_type,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('cover_type','Cover Type',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->cover_type,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('tp_id','Treatment Plant ',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->tp_id,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('size','Width (mm)',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->size,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group row">
				{!! Form::label('length','Length (m)',['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-3">
					{!! Form::label(null,$drain->length,['class' => 'form-control']) !!}
				</div>
			</div>
		</div><!-- /.card-body -->
	</div>
<div class="card-footer">

</div>
</div><!-- /.box -->
@stop

