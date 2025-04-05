<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', 'Edit User')
@section('content')
@include('layouts.components.error-list')
        @include('layouts.components.success-alert')
        @include('layouts.components.error-alert')
    <div class="card card-info">
        
        {!! Form::model($user, ['method' => 'PATCH', 'action' => ['Auth\UserController@update', $user->id], 'class' => 'form-horizontal']) !!}
                @hasanyrole('Super Admin|Municipality - IT Admin|Municipality - Sanitation Department')
                @include('users.partial-form', ['submitButtomText' => 'Update'])
                @endhasanyrole
                @hasanyrole('Service Provider - Admin')
                @include('users.partial-form-service-provider', ['submitButtomText' => 'Update'])
                @endhasanyrole
        {!! Form::close() !!}
    </div><!-- /.card -->
@endsection
