<!-- Last Modified Date: 21-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
	<div class="card-header bg-white">

                <form method="GET" action="{{ url('cwis/cwis-df-mne/export-mne-csv')}}" class="form-inline ml-auto">

                {{-- @if($add_cwis_data_button_visible)
                <a href="{{ action('Cwis\CwisMneController@createIndex', ['year' => $pickyear[0]]) }}" class="btn btn-info ml-2">Add CWIS Data</a>@endif --}}
                <a href="#" class="btn btn-info ml-2" id="edit">Edit CWIS Data</a>
                @php
                $currentYear = date('Y');
            @endphp

            @if($pickyear[0] == $currentYear && !Auth::user()->hasRole('Municipality - Executive'))

            @elseif($pickyear[0] < $currentYear && !Auth::user()->hasRole('Municipality - Executive'))
            <a href="{{ action('Cwis\CwisMneController@createIndex', ['year' => $slugyear[0], 'placeholder' => 'Enter value in percent','displayText' => 'quantitative']) }}" class="btn btn-info ml-2" id="addCwisData" style="display: none;">Add CWIS Data</a>

            @endif



                @can('Export CWIS MnE to Excel')
                <button type="submit" id="export" class="btn btn-info" style="margin-left: 1%">Export to Excel</button>
                @endcan
                <a href="{{ action('Cwis\CwisMneController@index') }}" class="btn btn-info float-left" style="display: none; margin-left:1%;" id="back">Back to List</a>
                        <div class="form-group float-right text-right ml-auto">
                            <label for="year_select">Year</label>
                                <select class="form-control" id="year_select" name="year_select">
                                    @foreach($pickyear as $key=>$unique)
                                    <option value= "{{$unique}}" @if($unique == $year){ selected } @endif> {{$unique}} </option>
                                    @endforeach
                                </select>
                        </div>

                </form>

    </div><!-- /.card-header -->
    @if(!Auth::user()->hasRole('Municipality - Executive'))
    @include('errors.list')
    {!! Form::open(['url' => 'cwis/cwis/cwis-df-mne', 'class' => 'form-horizontal']) !!}
        @include('cwis/cwis-df-mne.partial-form', ['submitButtonText' => 'Save'])
    {!! Form::close() !!}
@else
    @include('errors.list')
    {!! Form::open(['url' => 'cwis/cwis/cwis-df-mne', 'class' => 'form-horizontal']) !!}
        @include('cwis/cwis-df-mne.partial-form', ['submitButtonText' => 'Save'])
    {!! Form::close() !!}
@endif


</div><!-- /.card -->
@stop


@push('scripts')
<script>
    $('[name="year_select"]').change(function(e) {
        // e.preventDefault();
        var year = $(this).val();
        const url = '<?php echo url('');?>'+`/cwis/cwis/cwis-df-mne?year=${year}`;
        window.location.replace(url);
    });
    $(document).ready(function() {
        // Initially, show the "Edit" button and hide the "Add" button and the "Save" button
        $("#edit").show();
        $("#addCwisData").hide();
        $(".footer").hide();

        // When Edit CWIS Data button is clicked
        $("#edit").click(function() {
            // Hide the Edit CWIS Data button
            $(this).hide();
            // Display the Add CWIS Data button and Save button
            $("#addCwisData").show();
            $("#back").show();
            $(".footer").show();
        });
    });
</script>
@endpush

