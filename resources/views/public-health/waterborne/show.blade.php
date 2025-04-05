@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
    <div class="card-header bg-transparent">
        <a href="{{ action('PublicHealth\YearlyWaterborneController@index') }}" class="btn btn-info">Back to List</a>

    </div><!-- /.card-header -->
    <div class="form-horizontal">
        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('infected_disease', 'Infected Disease', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null, ucwords($diseaseName), ['class' => 'form-control']) !!}
                </div>
            </div>


            <div class="form-group row">
                {!! Form::label(null,'Year',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$YearlyWaterborne->year,['class' => 'form-control']) !!}
                </div>
            </div>

            <h3>No of Cases</h3>
            <div class="form-group row">
                 {!! Form::label(null,'Male Cases',['class' => 'col-sm-3 control-label']) !!}
                 <div class="col-sm-6">
                     {!! Form::label(null,$YearlyWaterborne->male_cases,['class' => 'form-control']) !!}
                 </div>
             </div>

             <div class="form-group row">
                 {!! Form::label(null,'Female Cases',['class' => 'col-sm-3 control-label']) !!}
                 <div class="col-sm-6">
                     {!! Form::label(null,$YearlyWaterborne->female_cases,['class' => 'form-control']) !!}
                 </div>
             </div>

             <div class="form-group row">
                 {!! Form::label(null,'Female Cases',['class' => 'col-sm-3 control-label']) !!}
                 <div class="col-sm-6">
                     {!! Form::label(null,$YearlyWaterborne->other_cases,['class' => 'form-control']) !!}
                 </div>
             </div>

             <h3>No of Fatalities</h3>
             <div class="form-group row">
                  {!! Form::label(null,'Male Fatalities',['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-6">
                      {!! Form::label(null,$YearlyWaterborne->male_fatalities,['class' => 'form-control']) !!}
                  </div>
              </div>

              <div class="form-group row">
                  {!! Form::label(null,'Female Fatalities',['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-6">
                      {!! Form::label(null,$YearlyWaterborne->female_fatalities,['class' => 'form-control']) !!}
                  </div>
              </div>

              <div class="form-group row">
                  {!! Form::label(null,'Other Fatalities',['class' => 'col-sm-3 control-label']) !!}
                  <div class="col-sm-6">
                      {!! Form::label(null,$YearlyWaterborne->other_fatalities,['class' => 'form-control']) !!}
                  </div>
              </div>

            <div class="form-group row">
                {!! Form::label(null,'Notes',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$YearlyWaterborne->notes,['class' => 'form-control']) !!}
                </div>
            </div>
        </div><!-- /.card-body -->
    </div>
</div><!-- /.card -->
@stop

