<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
<div class="card-body">
<div class="form-group row ">
		{!! Form::label('code','Road Code',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('code',null,['class' => 'form-control', 'disabled' => 'true', 'placeholder' => 'Code']) !!}
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('name','Road Name',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('name',null,['class' => 'form-control', 'placeholder' => 'Road Name']) !!}
		</div>
	</div>

        <div class="form-group row ">
		{!! Form::label('hierarchy','Road Hierarchy',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::select('hierarchy', $roadHierarchy, null, ['class' => 'form-control', 'placeholder' => 'Road Hierarchy']);!!}
		</div>
	</div>

	<div class="form-group row ">
		{!! Form::label('surface_type','Road Surface Type',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::select('surface_type', $roadSurfaceTypes, null, ['class' => 'form-control', 'placeholder' => 'Road Surface Type']);!!}
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('length','Road Length (m)',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('length',null,['class' => 'form-control', 'placeholder' => 'Road Length (m)']) !!}
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('right_of_way','Right of Way',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('right_of_way',null,['class' => 'form-control', 'placeholder' => 'Right of Way']) !!}
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('carrying_width','Carrying Width of the Road (m)',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			{!! Form::text('carrying_width',null,['class' => 'form-control', 'placeholder' => 'Carrying Width of the Road (m)']) !!}
		</div>
	</div>
</div><!-- /.card-body -->
<div class="card-footer">
	<a href="{{ action('UtilityInfo\RoadlineController@index') }}" class="btn btn-info">Back to List</a>
	{!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
</div><!-- /.card-footer -->
