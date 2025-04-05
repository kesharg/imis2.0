<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@push('style')
<style type="text/css">
.chosen-container .chosen-results {
    max-height: 150px !important;
}
</style>
@endpush
    <div class="card-body">
        @if(!empty($treatment_plant_id))
        <div class="form-group required">
            {!! Form::label('treatment_plant_id','Treatment Plant',['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                {!! Form::select('treatment_plant_id', $treatmentPlants, $treatment_plant_id, ['class' => 'form-control chosen-select', 'placeholder' => '--- Choose treatment plant ---','disabled'=>'true']) !!}
                {!! Form::text('treatment_plant_id', $treatment_plant_id, ['class' => 'form-control','hidden'=>'hidden']) !!}
            </div>
        </div>
        @else
        <div class="form-group required">
            {!! Form::label('treatment_plant_id','Treatment Plant',['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                {!! Form::select('treatment_plant_id', $treatmentPlants, null, ['class' => 'form-control chosen-select', 'placeholder' => '--- Choose treatment plant ---']) !!}
            </div>
        </div>
        @endif
        <div class="form-group required">
        {!! Form::label('application_id','Application ID',['class' => 'col-sm-3 control-label']) !!}
        @if($sludgeCollection)
        <div class="col-sm-3">
            {!! Form::text('application_id',null,['class' => 'form-control datepicker ', 'placeholder' => 'Application ID','readonly']) !!}
        </div>
        @elseif($application_id)
        <div class="col-sm-3">
            {!! Form::text('application_id',$application_id,['class' => 'form-control datepicker ', 'placeholder' => 'Application ID','readonly']) !!}
        </div>
        @else
        <div class="col-sm-3">
            {!! Form::select('application_id',$applications, null,['class' => 'form-control datepicker', 'placeholder' => 'Application ID']) !!}
        </div>
        @endif
    </div>
    @if(!empty($volume_of_sludge))
        <div class="form-group required">

        {!! Form::label('volume_of_sludge', 'Sludge Volume (m³)',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('volume_of_sludge',$volume_of_sludge,['class' => 'form-control ','readonly']) !!}
        </div>
        </div>
    @endif
    <div class="form-group required">
        {!! Form::label('date',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('date',$sludgeCollection->date??null,['class' => 'form-control date', 'id' => 'sludge_collection_date', 'placeholder' => 'Date (mm-dd-yyyy)']) !!}
        </div>
    </div>
    <div class="form-group required">
        {!! Form::label('entry_time',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::time('entry_time',$sludgeCollection->entry_time??null,['class' => 'form-control timepicker', 'placeholder' => 'Time']) !!}
        </div>
    </div>
    <div class="form-group required">
        {!! Form::label('exit_time',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::time('exit_time',$sludgeCollection->exit_time??null,['class' => 'form-control timepicker', 'placeholder' => 'Time']) !!}
        </div>
    </div>
    @if(!empty($service_provider_id))
        <div class="form-group required">
            <div class="col-sm-3">
                {!! Form::text('service_provider_id', $service_provider_id, ['class' => 'form-control','hidden'=>'hidden']) !!}
            </div>
        </div>
    @endif
    @if(!empty($vacutug_id))
        <div class="form-group required">
            <div class="col-sm-3">
                {!! Form::text('desludging_vehicle_id', $vacutug_id, ['class' => 'form-control','hidden'=>'hidden']) !!}
            </div>
        </div>
    @endif

</div><!-- /.card-body -->
<div class="card-footer">
    <a href="{{ action('Fsm\SludgeCollectionController@index') }}" class="btn btn-info">Back to List</a>
    {!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
</div><!-- /.card-footer -->

@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('#sludge_collection_date').daterangepicker({
                minDate: moment(),
                singleDatePicker: true,
                autoUpdateInput: false,
                showDropdowns:true,
                autoApply:true,
                drops:"auto"
            });
            $('#sludge_collection_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
            });

            $('#sludge_collection_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

    $('.date').focus(function(){
         $(this).blur();
     });
  });
</script>
@endpush
