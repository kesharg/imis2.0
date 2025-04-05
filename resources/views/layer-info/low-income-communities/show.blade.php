{{-- Last Modified Date: 14-04-2024
 Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) --}}
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
    <div class="card-header bg-transparent">
        <a href="{{ action('LayerInfo\LowIncomeCommunityController@index') }}" class="btn btn-info">Back to List</a>

    </div><!-- /.card-header -->
    <div class="form-horizontal">
        <div class="card-body">
            <div class="form-group required row">
        {!! Form::label('community_name','Community Name',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->community_name,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group required row">
         {!! Form::label('no_of_buildings','No. of Buildings',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_buildings,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group required row">
         {!! Form::label('population_total','Population',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->population_total,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group required row">
         {!! Form::label('number_of_households','No. of Households',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->number_of_households,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('population_male','Male Population',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->population_male,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('population_female','Female Population',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->population_female,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('population_others','Other Population',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->population_others,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('no_of_septic_tank','No. of Septic Tanks',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_septic_tank,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('no_of_holding_tank','No. of Holding Tanks',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_holding_tank,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('no_of_pit','No. of Pits',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_pit,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('no_of_sewer_connection','No. of Sewer Connections',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_sewer_connection,['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
         {!! Form::label('no_of_community_toilets','No. of Community Toilets',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::label(null,$lic->no_of_community_toilets,['class' => 'form-control']) !!}
        </div>
    </div>

        </div><!-- /.card-body -->
    </div>
</div><!-- /.card -->
@stop

