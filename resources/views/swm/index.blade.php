@extends('layouts.dashboard')
@section('title', 'SWM Dashboard')
@section('content')
<div class="row">
      <div class="col-lg-8 col-xs-8">
        {{--
          <form class="form-inline" id="filter-form" action="{{ action('dashboard.buildingsController@index') }}" method="get">
                      <div class="form-group">
                          <label for="year_select">Year</label>
                          <select class="form-control" id="year_select" name="year">
                            <option value="">All Years</option>
                            <?php
                                    $pickdate ="select distinct extract(year from emptied_date) as date1 from fsm.emptyings";
                                    $pickdateResults = DB::select($pickdate);
                                    foreach($pickdateResults as $unique) {
                                      ?> <option value= "<?php echo $unique->date1 ?>" > <?php echo $unique->date1 ?></option>

                                    <?php }
                                ?>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-info">Filter</button>
                      <a href="{{ action('dashboard.buildingsController@index') }}" class="btn btn-info reset">Reset</a>
          </form>
          --}}
          </div>
  </div>
<div class="row">
    <div class="col-lg-6">
        @include('swm.charts._transferStations')
    </div>
    <div class="col-lg-6">
        @include('swm.charts._landfillSites')
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        @include('swm.charts._noOfTripstransferStations')
    </div>
    <div class="col-lg-6">
        @include('swm.charts._noOfCollectionPointReached')
    </div>
</div> 
<div class="row">
    <div class="col-lg-6">
        @include('swm.charts._noOfCollectionPointHouseHoldServed')
    </div>
    <div class="col-lg-6">
        @include('swm.charts._volumeOfWasteRecycled')
    </div>
</div>
@stop 

