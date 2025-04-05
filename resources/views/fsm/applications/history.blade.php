<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
{{-- Extend the main layout --}}
@extends('layouts.dashboard')
{{-- Add sections for the main layout --}}
@section('title', 'Application History')
{{-- Add sections for the index layout --}}

{{-- Include the layout inside the main content section --}}
@section('content')
    <div class="card-header bg-transparent">
        <a href="{{ action('Fsm\ApplicationController@index') }}" class="btn btn-info">Back to List</a>
    </div><!-- /.card-header -->
    @include('layouts.history', compact('revisions'))
@endsection
