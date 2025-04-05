@extends('layouts.dashboard')
@push('style')
    <style type="text/css">
        .dataTables_filter {
            display: none;
        }
    </style>
@endpush
@section('title', $page_title)
@section('content')
    @include('layouts.components.error-list')
    @include('layouts.components.success-alert')
    @include('layouts.components.error-alert')
    <div class="card">

        <div class="card-body">
            {!! Form::model([
                'method' => 'PATCH',
                'action' => ['Fsm\CwisSettingController@update'],
                'class' => 'form-horizontal',
                'id' => 'editForm',
            ]) !!}





            @php
                $units = [
                    'average_household_size' => 'Average household size in building',
                    'average_family_size' => 'Number of people in family',
                    'total_population' =>'',
                    'fs_generation_rate_for_septictank' => 'm³/day',
                    'fs_generation_rate_for_pit' => 'm³/day',
                    'ww_generated_from_sewerconnection' => 'm³',
                    'ww_generated_from_greywater' => 'm³',
                    'ww_generated_from_supernatant' => 'm³',
                    'water_consumption_lpcd' => 'liters/day',
                    'average_family_size_LIC'=> 'Average Family Size',
                    'average_household_size_LIC'=> 'Average House Size LIC',
                ];

                $abbreviations = [
                    'fs_generation_rate_for_septictank' => 'Fecal sludge Generation Rate for Septic Tank (m³/day)',
                    'fs_generation_rate_for_pit' => 'Fecal sludge Generation Rate for Septic Pit (m³/day)',
                    'ww_generated_from_sewerconnection' => 'Waste Water Generated From Sewer Connection (liter/day)',
                    'ww_generated_from_greywater' => 'Waste Water Generated From GreyWater (liter/day)',
                    'ww_generated_from_supernatant' => 'Waste Water Generated From Supernatant (liter/day)',
                    'total_population' =>'Total Population',
                    'water_consumption_lpcd' => 'Water Consumption (liter/day)',
                    'average_family_size_LIC'=> 'Average Family Size',
                    'average_household_size_LIC'=> 'Average House Size LIC',
                ];
            @endphp

            @foreach (['average_household_size', 'total_population', 'fs_generation_rate_for_septictank', 'fs_generation_rate_for_pit', 'ww_generated_from_sewerconnection', 'ww_generated_from_greywater', 'ww_generated_from_supernatant', 'water_consumption_lpcd', 'average_family_size','average_family_size_LIC','average_household_size_LIC'] as $key)
                <div class="form-group row">
                    {!! Form::label(
                        $key,
                        isset($abbreviations[$key])
                            ? $abbreviations[$key]
                            : ucwords(str_replace('_', ' ', $key)) . ' (' . $units[$key] . ')',
                        ['class' => 'col-sm-3 control-label'],
                    ) !!}
                    <div class="col-sm-3">
                        {!! Form::number($key, $data[$key], [
                            'class' => 'form-control',
                            'placeholder' => ucwords(str_replace('_', ' ', $key)),
                        ]) !!}
                    </div>
                </div>
            @endforeach





        </div><!-- /.box-body -->
        <div class="card-footer">
            <span id="editButton" class="btn btn-info">Edit</span>
            <button type="submit" id="saveButton" class="btn btn-info" style="display: none;">Save</button>
        </div><!-- /.box-footer -->
    </div>
    {!! Form::close() !!}
    </div>


    </div><!-- /.box -->
@stop
@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to toggle readonly attribute
            function toggleReadOnly(readonly) {
                $('input').prop('readonly', readonly);
            }

            // Initially set form fields as read-only
            toggleReadOnly(true);

            // Edit button click event
            $('#editButton').click(function() {
                $('input').removeAttr('readonly');
                $('#editButton').hide();
                $('#saveButton').show();
            });

            // Check for errors and update buttons accordingly
            var hasErrors = $('.alert-danger').length > 0;

            if (hasErrors) {
                $('input').removeAttr('readonly');
                $('#editButton').hide();
                $('#saveButton').show();
            } else {
                $('#saveButton').hide();
                $('#editButton').show();
            }
        });
    </script>
@endpush
