<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', 'Login Activity')
@section('content')
<div class="col-md-12">
<div class="card card-info">
    
    <div class="form-horizontal">
        <div class="card-body">
            <div class="form-group row">
                {!! Form::label('Full Name',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label($userDetail->name,null,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('Last Login At',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label($last_login_at,null,['class' => 'form-control']) !!}                
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('Last Login IP',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label($last_login_ip,null,['class' => 'form-control']) !!}
                </div>
            </div>
           
        </div><!-- /.card-body -->
    </div>
    <div class="card-footer">
        <a href="{{ action('Auth\UserController@index') }}" class="btn btn-info">Back to List</a>
    </div>
</div><!-- /.card -->
</div>
@stop