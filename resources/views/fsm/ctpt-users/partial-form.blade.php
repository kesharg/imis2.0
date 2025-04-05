<div class="card-body">
	<div class="form-group row required">
		{!! Form::label('toilet_id','Toilet Name',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			@if(!isset($users))
				{!! Form::select('toilet_id', $name, 'Select Toilet', ['class' => 'form-control', 'placeholder' => 'Toilet Name']) !!}
			@else
				{!! Form::text('toilet_id',$name,['class' => 'form-control','readonly']) !!}
			@endif
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('date','Date',['class' => 'col-sm-3 control-label']) !!}
		<div class="col-sm-3">
			@if(!isset($users))
				{!! Form::date('date', null,['class' => 'form-control'  ,'placeholder' => 'Date']) !!}
			@else
				{!! Form::date('date',$users->date,['class' => 'form-control','readonly']) !!}
			@endif
		</div>
	</div>

	<div class="form-group row required">
		{!! Form::label('no_male_user','No. of Male Users (daily)',['class' => 'col-sm-3 calculate control-label']) !!}
		<div class="col-sm-3">
            {!! Form::number('no_male_user',null, ['class' => 'calculate form-control', 'placeholder' => 'No. of Male Users (daily)']) !!}
		</div>
	</div>
	<div class="form-group row required">
		{!! Form::label('no_female_user','No. of Female Users (daily)',['class' => 'col-sm-3 calculate control-label']) !!}
		<div class="col-sm-3">
			{!! Form::number('no_female_user', null,['class' => 'calculate form-control', 'placeholder' => 'No. of Female Users (daily)']) !!}
		</div>
	</div>



</div><!-- /.box-body -->
<div class="card-footer">
	<a href="{{ action('Fsm\CtptUserController@index') }}" class="btn btn-info">Back to List</a>
	{!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
</div><!-- /.box-footer -->

@push('scripts')
<script>
$(function() {
    $("input.calculate").keyup(function(){
        $("#total_user").val(parseInt($("#no_male_user").val())+parseInt($("#no_female_user").val()));
    });
});
</script>
@endpush
