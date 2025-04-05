@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
<div class="card-header bg-transparent">
    
        <a href="{{ action('Fsm\HelpDeskController@index') }}" class="btn btn-info">Back to List</a>

    </div><!-- /.card-header -->
    <div class="form-horizontal">
        <div class="card-body">
            <div class="form-group row ">
                {!! Form::label('name','Help Desk Name',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$helpDesk->name,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('description','Description',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$helpDesk->description,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('contact_number','Contact Number',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$helpDesk->contact_number,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('email','Email',['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::label(null,$helpDesk->email,['class' => 'form-control']) !!}
                </div>
            </div>
        </div><!-- /.box-body -->
    </div>
</div><!-- /.box -->
</div><!-- /.card -->
@stop

