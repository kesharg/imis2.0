<div class="form-group row">
    {!! Form::label('route_id','Route',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('route_id',null,['class' => 'form-control', 'placeholder' => 'Route']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('type','Type',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('type',null,['class' => 'form-control', 'placeholder' => 'Type']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('capacity','Capacity',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('capacity',null,['class' => 'form-control', 'placeholder' => 'Capacity']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('ward','Ward',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('ward',null,['class' => 'form-control', 'placeholder' => 'Ward']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('service_type','Service type',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('service_type',null,['class' => 'form-control', 'placeholder' => 'Service Type']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('household_served','Household Served',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('household_served',null,['class' => 'form-control', 'placeholder' => 'Household Served']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('status','Status',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('status',null,['class' => 'form-control', 'placeholder' => 'Status']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('collection_type','Collection Type',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('collection_type',null,['class' => 'form-control', 'placeholder' => 'Collection Type']) !!}
    </div>
</div>
<div class="form-group row">
    {!! Form::label('geom','Geom',['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('geom',null,['class' => 'form-control', 'placeholder' => 'Geom']) !!}
    </div>
</div>

<div class="card-footer">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-info float-right']) !!}
</div>
