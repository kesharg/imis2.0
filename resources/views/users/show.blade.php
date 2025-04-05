<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', 'User Detail')
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
                {!! Form::label('Gender',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label(($userDetail->gender=='M')?"Male":(($userDetail->gender=='F')?"Female":"-"),null,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('Username',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                {!! Form::label('username', $userDetail->username, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('Email',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label('email',$userDetail->email,['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('Role',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label(implode(', ', $userRoles),null,['class' => 'form-control', 'style' => 'min-height:200px;']) !!}
                </div>
            </div>
            {{--<div class="form-group row">
                {!! Form::label('ward',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label($userDetail->ward,null,['class' => 'form-control']) !!}
                </div>
            </div>--}}
            <div class="form-group row">
                {!! Form::label('User Type',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label(@$userDetail->user_type,null,['class' => 'form-control']) !!}
                </div>
            </div>

            @isset($treatmentPlants->name)
            <div class="form-group row">
                {!! Form::label('Treatment Plant',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label(@$treatmentPlants->name,null,['class' => 'form-control']) !!}
                </div>
            </div>
            @endisset
            @isset($serviceProviders->company_name)
                <div class="form-group row">
                    {!! Form::label('Service Provider',null,['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::label(@$serviceProviders->company_name,null,['class' => 'form-control']) !!}
                    </div>
                </div>
            @endisset
            @isset($helpDesks->name)
            <div class="form-group row">
                {!! Form::label('Help Desk',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label(@$helpDesks->name,null,['class' => 'form-control']) !!}
                </div>
            </div>
            @endisset

            <div class="form-group row">
                {!! Form::label('Status',null,['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-3">
                    {!! Form::label($status,null,['class' => 'form-control']) !!}
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

