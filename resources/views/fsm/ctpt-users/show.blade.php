@extends('layouts.dashboard')

@section('title', $page_title)
@section('content')
<div class="card card-info">
    <div class="card-header bg-transparent ">
    <a href="{{ action('Fsm\CtptUserController@index') }}" class="btn btn-info">Back to List</a>
    </div><!-- /.box-header -->
    <div class="form-horizontal">
        <div class="card-body">
                <div class="form-group row">
                {!! Form::label('Toilet Name',null, ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                    {!! Form::label($name,null,['class' => 'form-control']) !!}
                    </div>
                </div>
    
                <div class="form-group row">
                {!! Form::label('date',null, ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                    {!! Form::label($info->date,null,['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group row">
                {!! Form::label('No. of Male Users (daily)',null, ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                    {!! Form::label($info->no_male_user,null,['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group row">
                {!! Form::label('No. of Female Users (daily)',null, ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                    {!! Form::label($info->no_female_user,null,['class' => 'form-control']) !!}
                    </div>
                </div>
                
        </div><!-- /.box-body -->
    </div>
</div><!-- /.box -->
@stop
