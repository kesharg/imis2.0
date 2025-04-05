<!-- Last Modified Date: 19-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')

<div class="row">
    <div class="col-md-6">
    @include('dashboard.charts._roadLengthPerWardChart')
    </div>
    <div class="col-md-6">
    @include('dashboard.charts._roadsSurfaceTypePerWardChart')
    </div>

    <div class="col-md-6">
    @include('dashboard.charts._roadsHierarchyPerWardChart')
    </div>
    <div class="col-md-6">
    @include('dashboard.charts._roadsWidthPerWardChart')
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    @include('dashboard.sewer._sewerLengthPerWardChart')
    </div>
    <div class="col-md-6">
    @include('dashboard.charts._sewerWidthPerWardChart')
    </div>
</div>
<div class="row">
<div class="col-md-6">
    @include('dashboard.charts._drainLengthPerWardChart')
    </div>
    <div class="col-md-6">
    @include('dashboard.charts._drainsTypePerWardChart')
    </div>
    <div class="col-md-6">
    @include('dashboard.charts._drainWidthPerWardChart')
    </div>
    <div class="col-md-6">
        @include('dashboard.charts._watersupplyLengthPerWardChart')
    </div>
    <div class="col-md-6">
        @include('dashboard.charts._watersupplyDiameterPerWardChart')
    </div>
</div>

@stop
