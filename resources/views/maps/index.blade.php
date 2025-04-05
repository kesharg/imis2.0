<!-- Last Modified Date: 07-05-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->

@extends('layouts.maps')
@section('title', 'Map')
@section('content')

    <div class="content-wrapper map-container">
        <!-- <svg width="300px" height="300px" xmlns="http://www.w3.org/2000/svg" id="canvas">
    <text x="10" y="50" font-size="30">My SVG</text>
    </svg> -->
        <div class="map-toolbar clearfix">
            <div style="display: flex;flex-grow: 1;justify-content: space-between;" >
                <div class="controls-div" style="display: flex;">
                    <ul>
                        <a href="#" id="zoomin_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Zoom In"><i class="fa fa-search-plus fa-fw"></i></a>
                        <a href="#" id="zoomout_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Zoom Out"><i class="fa fa-search-minus fa-fw"></i></a>
                        <a href="#" id="zoomfull_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Municipality"><i class="fa fa-globe fa-fw"></i></a>
                        <a href="#" id="pan_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Navigate"><i class="fa fa-arrows fa-fw"></i></a>
                        <!-- <a href="#" id="navprev_control" class="btn btn-default glyphicon glyphicon-arrow-left" data-toggle="tooltip" data-placement="bottom" title="Previous View"></a>
                        <a href="#" id="navnext_control" class="btn btn-default glyphicon glyphicon-arrow-right" data-toggle="tooltip" data-placement="bottom" title="Next View"></a> -->
                        <a href="#" id="identify_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Info"><i class="fa fa-info-circle fa-fw"></i></a>
                        <a href="#" id="coordinate_control" class="btn btn-default map-control"
                           style="padding:6px 14px!important;" data-toggle="tooltip" data-placement="bottom"
                           title="Coordinate Information"><i class="fa fa-map-pin fa-fw"></i></a>
                        <a href="#" id="getpointbycoordinates_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Locate Point by Coordinate"><i
                                    class="fa fa-location-arrow" aria-hidden="true"></i></a>
                        <a href="#" id="linemeasure_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom"
                           title="Measure Distance"><i class="fa-solid fa-ruler"></i></a>
                        <a href="#" id="polymeasure_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom"
                           title="Measure Area"><i class="fas fa-draw-polygon"></i></a>
                        <a href="#" id="print_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Print"><i class="fa fa-print fa-fw"></i></a>
                        {{--                    <a target="_blank" href="{{ asset('pdf/legends.pdf') }}" class="btn btn-default map-control"
                                               data-toggle="tooltip" data-placement="bottom" title="Legends"><i class="fa-solid fa-toolbox"></i></a>--}}
                        <a target="_blank" href="{{ asset('pdf/tools-help.pdf') }}" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Help"><i class="fa-solid fa-file"></i></a>
                        <a href="#" id="nearestroad_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Find Nearest Road"> <img src="{{ asset('img/svg/imis-icons/nearestroad.svg')}}" style="height:24px;" alt="Nearest Road Icon"></a>
                        <a href="#" id="containmentbuilding_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Find Buildings Connected to Containment"><img src="{{ asset('img/svg/imis-icons/building_to_containment.svg')}}" style="height:24px;" alt="Buildings Connected to Containment Icon"></a>
                        <a href="#" id="buildingcontainment_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Find Containments Connected to Building"> <img src="{{ asset('img/svg/imis-icons/containment_to_building.svg')}}" style="height:24px;"alt="Containment to Buildings Connected Icon"></a>
                        <a href="#" id="associatedtomain_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Find Associated Buildings"><img src="{{ asset('img/svg/imis-icons/associated_building.svg')}}" style="height:24px;"alt="Associated Buildings Icon"></a>
                        <a href="#" id="add_road_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Add roads"><i class="fa-solid fa-road-circle-check"></i></a>
                        <!-- <a href="#" id="road_inaccessible_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Hard to reach building"><i class="fa-brands fa-buffer"></i></a> -->
                        <!-- <a href="#" id="waterbody_inaccessible_control" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Building close to water bodies"><i class="fa-solid fa-water"></i></a> -->
                        <!-- <a href="#" id="ptct_network" class="btn btn-default map-control"
                           data-toggle="tooltip" data-placement="bottom" title="Building Connected to Public /Community Toilets"><i class="fa-solid fa-bezier-curve"></i></a> -->
                        <a href="#" id="removemarkers_control" class="btn btn-default map-control" data-toggle="tooltip"
                           data-placement="bottom" title="Remove Markers"><i class="fa fa-trash fa-fw"></i></a>

                    </ul>

                </div>

                <div class="">
                    <form class="form-inline" name="filterward_form" id="wardfilter_form">
                        <div class="form-group">
                            <div class="input-group">
                                <select class="form-control" id="filterward_select" name="ward" style="min-width:50px;">
                                    <option value="">All Wards</option>
                                    @foreach($pickWardResults as $unique)
                                    <option value= "{{ $unique->ward }}" > {{ $unique->ward }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="float-right">
                    <form class="form-inline" name="building_search_form" id="building_search_form">
                        <div class="form-row">

                                <input type="text" class="form-control" id="building_value_text" style="min-width:30% !important;" />
                            </div>
                            <div class="form-row">
                                <select class="form-control" id="building_field_select">
                                    <option value="bin">House Number</option>
                                    {{--(Commented - Might be needed in the future)
                                    <option value="holding">Holding No.</option>
                                    <option value="taxcd">Tax Code</option>--}}
                                    <option value="places_layer">Places</option>
                                    <!-- <option value="pubsrv">Public Services</option> -->
                                    <option value="roadlines_layer">Roads</option>
                                </select>


                        </div>

                            <button class="btn btn-default" type="submit"><i class="fa fa-search fa-fw"></i></button>

                    </form>
                </div>
            </div>

            <div id="buildings-road-popup" class="ol-popup" style="display: none;">
                <h3 class="card-title py-2">Summary Info</h3>
                <div id="buildings-road-popup-content"></div>
                @can('Export in Decision Map Tools')
                <div id="buildings-road-popup-content-download">

                        <form method="get" action="{{ url("maps/export-buildings-road") }}">
                            <input type="hidden" name="road_codes" value="" id="road_codes"/>
                            <button type="submit" id="buildings-road-popup-export-excel-btn" class="btn btn-default">
                               Export to Excel
                            </button>
                            <button id="buildings-road-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>

                </div>
                @endcan
            </div>
            <div id="waterbody-inaccessible-popup" class="ol-popup" style="display: none;">
                <div id="waterbody-inaccessible-popup-content"></div>
                    <div id="waterbody-inaccessible-popup-content-download">
                        <form method="get" action="{{ url("maps/waterbody-inaccessible-buildings-reports") }}">
                            <input type="hidden" name="waterbody_hose_length_report" value="" id="waterbody_hose_length_report"/>
                            <input type="hidden" name="waterbody_hose_length_unit_report" value="" id="waterbody_hose_length_unit_report"/>
                            <button type="submit" id="waterbody-inaccessible-export-csv-btn" class="btn btn-default"> Export to Excel</button>
                            <button id="waterbody-inaccessible-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer">Close</button>
                        </form>

                </div>
            </div>
            <div class="text-right">
                <a href="#" id="map-right-sidebar-toggle" class="btn btn-default">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>

        <div id="map">
            <div id="gmap" style="width: 100%; height: 100%; visibility: hidden;"></div>
            <div id="olmap" style="position: absolute; top: 0; left: 0; right: 0; bottom:0;"></div>
            <div id="popup" class="ol-popup" style="display: none;">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>
            <div class="box-footer" id="layer-select-box" style="position: absolute;top: 10px;left: 15px;display: none;filter: drop-shadow(0 10px 4px rgba(0,0,0,0.2));border-radius: 5px;border: 1px solid #cccccc;">
                <div id="feature-info-popup-content">
                    <form class="form-inline" id="feature_info_form">
                        <div class="form-group">
                            <div class="input-group">
                                <select class="form-control" id="feature_info_overlay">
                                    <option value="">Select a layer</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div id="feature_info_content"></div>
                </div>
            </div>
            <div class="box-footer" id="add-road-tool-box" style="display:none;position: absolute;top: 10px;left: 15px;filter: drop-shadow(0 10px 4px rgba(0,0,0,0.2));border-radius: 5px;border: 1px solid #cccccc;">
                <div id="add-road-tool-box-content">
                    <form class="form-horizontal" id="add-road-form">
                        <div xclass="form-group">
                            <div class="input-group">
                                <a href="#" id="add_road_start_control" class="btn btn-default map-control" data-toggle="tooltip"
                                   data-placement="bottom" title="Add"><i class="fa fa-circle-plus fa-fw"></i></a>
                                <a href="#" id="add_road_undo_last_point_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Undo last point"><i class="fa fa-clock-rotate-left fa-fw"></i></a>
                                <a href="#" id="add_road_undo_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Undo"><i class="fa fa-rotate-left fa-fw"></i></a>
                                <a href="#" id="add_road_redo_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Redo"><i class="fa fa-rotate-right fa-fw"></i></a>
                                <a href="#" id="add_road_edit_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Edit"><i class="fa fa-pen-to-square fa-fw"></i></a>
                                <a href="#" id="add_road_delete_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Remove all drawn lines"><i class="fa fa-trash fa-fw"></i></a>
                                <a href="#" id="add_road_submit_control" class="btn btn-default map-control ml-1" data-toggle="tooltip"
                                   data-placement="bottom" title="Save"><i class="fa fa-floppy-disk fa-fw"></i></a>
                            </div>
                            <div class="add-road-form" style="display: none">
                                <div>
                                    <hr>
                                    <h4>Add Road Network</h4>
                                </div>
                                <div id="add-road-errors" tabindex='1'>

                                </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('name','Road Name <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        {!! Form::text('name',null,['class' => 'form-control', 'placeholder' => 'Road Name']) !!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('hierarchy','Road Hierarchy',['class' => 'control-label'],false) !!}
                                        {!! Form::select('hierarchy', $roadHierarchy, null, ['class' => 'form-control', 'placeholder' => 'Road Hierarchy']);!!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('surface_type','Road Surface Type',['class' => 'control-label'],false) !!}
                                        {!! Form::select('surface_type', $roadSurfaceTypes, null, ['class' => 'form-control', 'placeholder' => 'Road Surface Type']);!!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('length','Road Length (m) <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        {!! Form::number('length',null,['class' => 'form-control', 'placeholder' => 'Road Length (m)','min' => 1]) !!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('right_of_way','Right of Way <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        {!! Form::number('right_of_way',null,['class' => 'form-control', 'placeholder' => 'Right of Way (m)','min' => 1]) !!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::label('carrying_width','Carrying Width of the Road (m) <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        {!! Form::number('carrying_width',null,['class' => 'form-control', 'placeholder' => 'Carrying Width of the Road (m)','min' => 1]) !!}
                                    </div>
                                    <div class="add-road-form-group">
                                        {!! Form::button('Save', ['class' => 'btn btn-info','id'=>'add_road_submit_btn']) !!}
                                    </div>
                            </div>
                        </div>
                    </form>
                    <div id="feature_info_content"></div>
                </div>
            </div>
            <div class="box-footer" id="add-road-inaccessible-box" style="display:none;position: absolute;top: 10px;left: 15px;filter: drop-shadow(0 10px 4px rgba(0,0,0,0.2));border-radius: 5px;border: 1px solid #cccccc;">
                <div id="add-road-inaccessible-box-content">
                    <form class="form-horizontal" id="add-road-inaccessible-form">
                        <div class="form-group">
                            <div class="add-road-inaccessible-form" >
                                <div>
                                    <h4>Hard to Reach Buildings</h4>
                                </div>
                                <div id="add-road-inaccessible-errors" tabindex='1'>
                                </div>
                                    <div class="add-road-inaccessible-form-group">
                                        {!! Form::label('road_width','Carrying Width <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                         <div class="container-fluid">
                                        <div class="row">
                                        {!! Form::text('road_width',null,['class' => 'form-control col-md-6', 'placeholder' => 'Carrying Width']) !!}
                                        <select class="form-control col-md-6" id="road_width_unit"><option value="meter">Meter</option><option value="feet">Feet</option></select>
                                    </div></div>
                                        </div>
                                    <div class="add-road-inaccessible-form-group">
                                        {!! Form::label('hose_length','Hose Length <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        <div class="container-fluid">
                                        <div class="row">

                                            {!! Form::text('hose_length',null,['class' => 'form-control col-md-6', 'placeholder' => 'Hose Length']) !!}
                                        <select class="form-control col-md-6" id="hose_length_unit"><option value="meter">Meter</option><option value="feet">Feet</option></select>
                                    </div></div></div>
                                    <div class="add-road-inaccessible-form-group">
                                        {!! Form::button('Submit', ['class' => 'btn btn-info','id'=>'add_road_inaccessible_submit_btn']) !!}
                                    </div>
                            </div>
                        </div>
                    </form>
                    <div id="feature_info_content"></div>
                </div>
            </div>
            <div class="box-footer" id="add-waterbody-inaccessible-box" style="display:none;position: absolute;top: 10px;left: 15px;filter: drop-shadow(0 10px 4px rgba(0,0,0,0.2));border-radius: 5px;border: 1px solid #cccccc;">
                <div id="add-waterbody-inaccessible-box-content">
                    <form class="form-horizontal" id="add-waterbody-inaccessible-form">
                        <div class="form-group">
                            <div class="add-waterbody-inaccessible-form" >
                                <div>
                                    <h4>Buildings Close to Water Bodies</h4>
                                </div>
                                <div id="add-waterbody-inaccessible-errors" tabindex='1'>
                                </div>
                                    <div class="add-waterbody-inaccessible-form-group">
                                        {!! Form::label('waterbody_hose_length','Buffer Distance <span style="color: red">*</span>',['class' => 'control-label'],false) !!}
                                        <div class="container-fluid">
                                        <div class="row">

                                            {!! Form::text('waterbody_hose_length',null,['class' => 'form-control col-md-6', 'placeholder' => 'Buffer Distance']) !!}
                                        <select class="form-control col-md-6" id="waterbody_hose_length_unit"><option value="meter">Meter</option><option value="feet">Feet</option></select>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="add-waterbody-inaccessible-form-group">
                                        {!! Form::button('Submit', ['class' => 'btn btn-info','id'=>'add_waterbody_inaccessible_submit_btn']) !!}
                                    </div>
                            </div>
                        </div>
                    </form>
                    <div id="feature_info_content"></div>
                </div>
            </div>
            <div id="popup-marker" class="ol-popup" style="display: none;">
                <a href="#" id="popup-marker-closer" class="ol-popup-closer"></a>
                <div id="popup-marker-content"></div>
            </div>
            <div id="feature-info-popup" class="ol-popup" style="display: none;">
                <a href="#" id="feature-info-popup-closer" class="ol-popup-closer"></a>
                <div id="feature_information" style="max-height: 250px;min-width:450px;overflow-y: auto">

                </div>
            </div>
            <div id="report-popup" class="ol-popup" style="display: none;">
                <a href="#" id="report-popup-closer" class="ol-popup-closer"></a>
                <div id="report-popup-content"></div>
                <div id="report-popup-content-download">
                    <div><strong>Export to:</strong></div>
                    <div class="btn-group">
                        <form method="post" action="{{ url("getPolygonReportCSV") }}">
                            <input type="hidden" name="geom" value="" id="report-export-geom"/>
                            <button type="submit" id="report-export-csv-btn" class="btn btn-default">Excel</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="drain-potentialSummary-popup" class="ol-popup" style="display: none;">
                <h3 class="card-title py-2">Summary Info</h3>
                <div id="drain-potentialSummary-popup-content"></div>
                <div id="drain-potentialSummary-popup-content-download">
                        <form method="get" action="{{ url("maps/export-drain-potential-buildings") }}">
                            <input type="hidden" name="db_code" value="" id="DrainBufferCode"/>
                            <input type="hidden" name="db_distance" value="" id="DBdistance"/>
                            @can('Export in Decision Map Tools')
                            <button type="submit" id="drain-potentialSummary-export-excel-btn" class="btn btn-default">
                                Export to Excel
                            </button>
                            @endcan
                            <button id="drain-potentialSummary-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>
            </div>
            <div id="water-body-popup" class="ol-popup" style="display: none;">
                <h3 class="card-title py-2">Summary Info</h3>
                <div id="water-body-popup-content"></div>

                <div id="water-body-popup-content-download">
                        <form method="get" action="{{ url("maps/export-buffer-polygon-waterbody") }}">
                            <input type="hidden" name="wb_code" value="" id="waterBodyCode"/>
                            <input type="hidden" name="wb_distance" value="" id="WBdistance"/>
                            @can('Export in Summary Information Map Tools')
                            <button type="submit" id="water-body-export-excel-btn" class="btn btn-default">Export to Excel
                            </button>
                            @endcan
                            <button id="water-body-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>

            </div>
            <div id="ward-buildings-popup" class="ol-popup" style="display: none;">
                <div id="ward-buildings-popup-content"></div>

                <div id="ward-buildings-popup-content-download">
                        <form method="get" action="{{ url("maps/export-ward-buildings") }}">
                            <input type="hidden" name="ward_building_no" value="" id="ward_building_no"/>
                            @can('Export in Summary Information Map Tools')
                            <button type="submit" id="ward-buildings-export-excel-btn" class="btn btn-default">Export to Excel
                            </button>
                            @endcan
                            <button id="ward-buildings-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>

            </div>
            <div id="buffer-polygon-popup" class="ol-popup" style="display: none;">


                    <h3 class="card-title py-2">Summary Info</h3>

                    <div id="buffer-polygon-popup-content"></div>



                <div id="buffer-polygon-popup-content-download">
                        <form method="get" action="{{ url("maps/export-buffer-polygon") }}">
                            <input type="hidden" name="buffer_polygon_geom" value="" id="buffer_polygon_geom"/>
                            <input type="hidden" name="buffer_polygon_distance" value="" id="buffer_polygon_distance"/>
                            @can('Export in Summary Information Map Tools')
                            <button type="submit" id="buffer-polygon-export-excel-btn" class="btn btn-default">Export to Excel
                            </button>
                             @endcan
                             <button id="buffer-polygon-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>



            </div>
            <div id="road-popup" class="ol-popup" style="display: none;">
                <h3 class="card-title py-2">Summary Info</h3>
                <div id="road-popup-content"></div>

                <div id="road-popup-content-download">
                        <form method="get" action="{{ url("maps/export-road-buildings") }}">
                            <input type="hidden" name="road_code" value="" id="RDCode"/>
                            <input type="hidden" name="rb_distance" value="" id="RDBdistance"/>
                            @can('Export in Summary Information Map Tools')
                            <button type="submit" id="road-export-excel-btn" class="btn btn-default">Export to Excel</button>
                            @endcan
                            <button id="road-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>

            </div>
            <div id="point-buffer-popup" class="ol-popup" style="display: none;">
                <h3 class="card-title py-2">Summary Info</h3>
                <div id="point-buffer-popup-content"></div>
                <div id="point-buffer-popup-content-download">
                        <form method="get" action="{{ url("maps/export-point-buildings") }}">
                            <input type="hidden" id="PTB-long-csv" name="PTB_long" value=""/>
                            <input type="hidden" id="PTB-lat-csv" name="PTB_lat" value=""/>
                            <input type="hidden" id="PTB-distance" name="PTB_distance" value=""/>
                            @can('Export in Summary Information Map Tools')
                            <button type="submit" id="ptb-export-excel-btn" class="btn btn-default">Export to Excel</button>
                            @endcan
                            <button id="point-buffer-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer" >Close</button>
                        </form>
                </div>
            </div>
            <div id="export-popup" class="ol-popup" style="display: none;">
                <a href="#" id="export-popup-closer" class="ol-popup-closer"></a>
                <h3 class="card-title py-2">Export Data Set</h3>
                <div id="export-popup-content">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Overlay</span>
                    </div>
                    <select class="form-control"  id="export_overlay">
                        <option value="">Select a layer</option>
                    </select>
                    </div>
                    <div class="input-group">
            <div class="input-group-prepend">
                                        <span class="input-group-text" >Export</span>
                                </div>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select a format</button>
                <div class="dropdown-menu">
                <a class="dropdown-item" id="export-csv-btn" href="#">CSV</a>
                <a class="dropdown-item"  id="export-kml-btn" href="#">KML</a>
                <a class="dropdown-item" id="export-shape-btn"  href="#">Shape File</a>
                </div>
            </div>
            </div>

                </div>
            </div>

            <div id="export-csv-popup" class="ol-popup" style="display: none;">
                <a href="#" id="export-csv-popup-closer" class="ol-popup-closer"></a>
                <h3 class="card-title py-2">Buidings data with owner info</h3>
                <div id="export-csv-popup-content">
                    <div class="input-group">
                        <div class="input-group-prepend">
                                <span class="input-group-text" >Export</span>
                        </div>
                        <div class="input-group-append">
                            <input type="hidden" id="building-with-owner-polygon-geom" value=""/>
                            <button id="export-buildings-csv-btn" class="btn btn-default">Excel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="population-popup" class="ol-popup" style="display: none;">
                <a href="#" id="population-popup-closer" class="ol-popup-closer"></a>
                <div id="population-popup-content"></div>
            </div>
            <div id="road-inaccessible-popup" class="ol-popup" style="display: none;">
                <div id="road-inaccessible-popup-content"></div>
                <div id="road-inaccessible-popup-content-download">
                        <form method="get" action="{{ url("maps/road-inaccessible-buildings-reports") }}">
                            <input type="hidden" name="road_width_report" value="" id="road_width_report"/>
                            <input type="hidden" name="road_width_unit_report" value="" id="road_width_unit_report"/>
                            <input type="hidden" name="road_hose_length_report" value="" id="road_hose_length_report"/>
                            <input type="hidden" name="road_hose_length_unit_report" value="" id="road_hose_length_unit_report"/>
                            <button type="submit" id="report-road-inaccessible-export-csv-btn" class="btn btn-default">Export to Excel</button>
                            <button id="road-inaccessible-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer">Close</button>
                        </form>

                </div>
            </div>
            <div id="map-right-sidebar">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active nav-item"><a href="#layers-tab" aria-controls="layers" role="tab"
                                                              data-toggle="tab" class="nav-link active" aria-selected="true">Layers</a></li>
                    <li role="presentation" class="nav-item"><a href="#analysis-tab" aria-controls="analysis" role="tab"
                                               data-toggle="tab" class="nav-link">Tools</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active show" id="layers-tab">
                        <div>
                            <div>
                                <label for="base_layer_select">Base Layer</label>
                            </div>
                            <div>
                                <select id="base_layer_select">
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="overlay-spacer">
                            <div>
                                <label for="">Overlays</label>
                            </div>
                            <div id="overlay_checkbox_container"></div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="analysis-tab">
                        <!-- Service Providers Tools -->
                        @can('Service Delivery Map Tools')
                        <span data-toggle="tooltip" data-placement="bottom" title="Service Delivery">
                    <a id="servicedelivery_control" class="btn btn-default collapse-control" role="button"
                       data-toggle="collapse" href="#collapse_service_delivery" aria-expanded="false"
                       aria-controls="collapse_service_delivery"><i class="fa-brands fa-servicestack"></i>Service Delivery Tools</a>
                </span>
                        <div class="collapse" id="collapse_service_delivery">
                            <div class="card">
                                <!-- find application -->
                                <div class="card-body">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Find Applications">
                            <a id="applicationcontainments_control" class="btn btn-default collapse-control collapsed"
                               role="button" data-toggle="collapse" href="#collapse_find_appications"
                               aria-expanded="false" aria-controls="collapse_find_appications"><i
                                        class="fa fa-file-text"></i>Applications</a>
                        </span>
                                    <div class="collapse" id="collapse_find_appications">
                                        <div class="card">
                                            <div class="card-body">
                                            <form role="form" class="form-inline" name="find_application_yearmonth"
                                                  id="find_application_yearmonth" style="margin-bottom: 15px">
                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <!--<div class="input-group-addon">Year</div>-->
                                                        <select name="applicaion_year" class="form-control col-md-4"
                                                                id="applicaion_year">
                                                            <option value="">Year</option>

                                                                @foreach($pickDateResults as $unique)
                                                                     <option value= "{{ $unique->date1 }}" > {{ $unique->date1 }}</option>

                                                                @endforeach
                                                        </select>
                                                        <select name="application_month" class="form-control col-md-4"
                                                                id="application_month">
                                                            <option value="">Month</option>
                                                            @for ($mm=1; $mm<=12; $mm++)
                                                                <option value="{{ $mm }}">{{ $mm }}</option>
                                                            @endfor
                                                        </select>
                                                        <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                                 aria-hidden="true"></i></button>
                                            </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <form role="form" name="application_date_form" id="application_date_form" class="form-inline">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend"><span class="input-group-text">Date</span></div>
                                                    <input type="date" class="form-control col-md-10"
                                                           id="application_date_field"/>
                                                    <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                             aria-hidden="true"></i></button>
                                        </div>
                                                </div>
                                            </form>
                                            <br>
                                            <table>
                                                <tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/application.png") }}"></td>
                                                    <td>- Application only</td>
                                                </tr>
                                                {{--<tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/application-assessment.png") }}">
                                                    </td>
                                                    <td>- Application and assessment</td>
                                                </tr>--}}
                                                <tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/application-emptying.png") }}"></td>
                                                    <td>- Application and emptying service</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/application-feedback.png") }}"></td>
                                                    <td style="vertical-align: top;">- Application, emptying
                                                        service and feedback
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/application-sludge-collection.png") }}"></td>
                                                    <td style="vertical-align: top;">- Application, emptying
                                                        service, sludge collection and feedback
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                               </div>
                            <!-- find emptied applications that have not reached to treatment-plants -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Emptied Applications that have not reached to Treatment Plant">
                            <a id="applications_not_tp" class="btn btn-default collapse-control collapsed" role="button"
                               data-toggle="collapse" href="#collapse_applications_not_tp" aria-expanded="false"
                               aria-controls="collapse_find_tax_due_buildings"><i class="fa-solid fa-calendar-xmark"></i>Emptied Applications not reached to Treatment Plant</a>
                        </span>
                                    <div class="collapse" id="collapse_applications_not_tp">
                                        <div class="card">
                                            <div class="card-body">
                                            <form role="form" class="form-inline"
                                                  name="find_application_not_tp_yearmonth"
                                                  id="find_application_not_tp_yearmonth" style="margin-bottom: 15px">
                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <!--
                                                        <div class="input-group-addon">Year</div>-->
                                                        <select name="applicaion_not_tp_year" class="form-control col-md-4"
                                                                id="applicaion_not_tp_year">
                                                            <option value="">Year</option>

                                                                @foreach($pickDateResults as $unique)
                                                                    <option value= "{{ $unique->date1 }}" > {{ $unique->date1 }}</option>
                                                                @endforeach
                                                        </select>
                                                        <!--<div class="input-group-addon">Month</div>-->
                                                        <select name="application_not_tp_month" class="form-control col-md-4"
                                                                id="application_not_tp_month">
                                                            <option value="">Month</option>
                                                            @for ($mm=1; $mm<=12; $mm++)
                                                                <option value="{{ $mm }}">{{ $mm }}</option>
                                                            @endfor
                                                        </select>
                                                        <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                                 aria-hidden="true"></i></button>
                                            </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <form role="form" name="application_not_tp_date_form"
                                                  id="application_not_tp_date_form" class="form-inline">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1">Date</span></div>
                                                    <input type="date" class="form-control col-md-6"
                                                           id="application_not_tp_date_field"/>
                                                    <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                             aria-hidden="true"></i></button>
                                        </div>
                                                </div>
                                            </form>
                                            <br>
                                            <table id="service_providers_legends">
                                                {{--@foreach($service_providers as $key=>$service_provider)
                                                    <tr>
                                                        <td style="vertical-align: top;"><img
                                                                    src="{{ asset("/img/marker-".$markerColors[$key].".png") }}"></td>
                                                        <td> - {{$service_provider}}</td>
                                                    </tr>
                                                @endforeach--}}
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <!-- find containment proposed to be emptied-->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Containments proposed to be emptied">
                            <a id="containments_proposed_to_be_emptied" class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                               href="#collapse_proposed_emptying_containments" aria-expanded="false"
                               aria-controls="collapse_proposed_emptying_containments"><i class="fa fa-square"
                                                                                          aria-hidden="true"></i>Containments proposed to be emptied</a>
                        </span>
                                    <div class="collapse" id="collapse_proposed_emptying_containments">
                                        <div class="card">
                                           <div class="card-body">
                                            <form class="form-inline" name="proposed_emptying_days_form"
                                                  id="proposed_emptying_days_form" style="margin-bottom: 15px">
                                                    <div class="form-group ">
                                                        <div class="input-group input-group-sm ">
                                                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1">Next</span></div>
                                                            <input type="text" class="form-control col-md-1"
                                                                   id="proposed_emptying_days">
                                                            <div class="input-group-append"><span class="input-group-text">Days</span></div>
                                                            <div class="input-group-append">

                                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                                                 aria-hidden="true"></i></button>

                                                            </div>
                                                        </div>
                                                    </div>
                                            </form>
                                            <form class="form-inline" name="proposed_emptying_week_form"
                                                  id="proposed_emptying_week_form" style="margin-bottom: 15px">
                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Next Week</span>
                                                        </div>
                                                        <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                                 aria-hidden="true"></i></button>
                                            </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <form role="form" name="proposed_emptying_date_form"
                                                  id="proposed_emptying_date_form">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend"><span class="input-group-text">Date</span></div>
                                                    <input type="date" class="form-control"
                                                           id="proposed_emptying_date"/>
                                                    <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                                             aria-hidden="true"></i></button>
                                        </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <!-- feedback chart -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Feedback of Custom Boundary">
                            <a href="#" id="feedback_control" class="btn btn-default map-control"><i
                                        class="fa fa-list-alt"></i>Feedback Chart ( FSM Service Quality )</a>
                        </span>

                            </div>
                        </div>
                            </div>
                        @endcan
                        @can('General Map Tools')
                        <!-- General Tools -->
                        <span data-toggle="tooltip" data-placement="bottom" title="General Tools">
                            <a id="generaltools_control" class="btn btn-default collapse-control" role="button"
                            data-toggle="collapse" href="#collapse_general_tools" aria-expanded="false"
                            aria-controls="collapse_general_tools"><i class="fa-brands fa-servicestack"></i>General Tools</a>
                         </span>
                        <div class="collapse" id="collapse_general_tools">
                            <div class="card">
                                <div class="card-body">
                                <!-- Find Building By structure -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Buildings by Structure Type">
                                        <a class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                                        href="#collapse_building_structype_filter" aria-expanded="false"
                                        aria-controls="collapse_building_structype_filter"><i class="fa fa-building"></i>Buildings by Structure Type</a>
                                     </span>
                                    <div class="collapse" id="collapse_building_structype_filter">
                                        <div class="card">
                                            <div class="card-body">
                                            <div id="building_structype_checkbox_container">

                                                    @foreach($pickStructureResults as $structype)

                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox"  name="{{ $structype->id }}"   value= "{{ $structype->id }}" /> {{ $structype->type }}
                                                                </label>
                                                            </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            <!-- Building by Tax Payments -->

                                <span data-toggle="tooltip" data-placement="bottom"
                                      title="Property Tax Collection ISS">
                            <a class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                               href="#collapse_building_tax_status" aria-expanded="false"
                               aria-controls="collapse_building_tax_status"><i class="fa fa-building"></i>Property Tax Collection ISS</a>
                        </span>
                                <div class="collapse" id="collapse_building_tax_status">
                                    <div class="card">
                                        <div class="card-body">
                                        <form role="form" name="building_tax_payment_form"
                                              id="building_tax_payment_form">
                                            <div id="building_tax_payment_checkbox_container">
                                                @foreach($dueYears as $key => $val)
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="{{$key}}" value="{{$val}}"/>
                                                            {{$val}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!--          <button type="submit" class="btn btn-default">Filter</button>-->
                                            <button type="button" class="btn btn-default"
                                                    id="building_tax_payment_clear_button">Clear
                                            </button>
                                            @can('Export in General Map Tools')
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    Export <span class="caret"></span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                    <a href="#" class="dropdown-item" id="export_building_tax_filter_csv">CSV</a>
                                                    <a href="#" class="dropdown-item" id="export_building_tax_filter_kml">KML</a>
                                                    <a href="#" class="dropdown-item" id="export_building_tax_filter_shp">Shape File</a>

                                                </div>
                                            </div>
                                            @endcan
                                        </form>
                                    </div>
                                </div>
                                </div>
                                <!-- water supply payments -->
                                <span data-toggle="tooltip" data-placement="bottom"
                                      title="Buildings by Water Supply ISS">
                            <a class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                               href="#collapse_water_supply_status" aria-expanded="false"
                               aria-controls="collapse_building_tax_status"><i class="fa fa-building"></i>Water Supply ISS</a>
                        </span>
                                <div class="collapse" id="collapse_water_supply_status">
                                    <div class="card">
                                        <div class="card-body">
                                        <form role="form" name="water_supply_payment_form"
                                              id="water_supply_payment_form">
                                            <div id="water_supply_payment_checkbox_container">
                                                @foreach($dueYears as $key => $val)
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="{{$key}}" value="{{$val}}"/>
                                                            {{$val}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-default"
                                                    id="water_supply_payment_clear_button">Clear
                                            </button>
                                            @can('Export in General Map Tools')
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    Export <span class="caret"></span>
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                    <a href="#" class="dropdown-item"  id="export_water_supply_filter_csv">CSV</a>
                                                    <a href="#" class="dropdown-item"  id="export_water_supply_filter_kml">KML</a>
                                                    <a href="#" class="dropdown-item"  id="export_water_supply_filter_shp">Shape File</a>
                                                </div>
                                            </div>
                                            @endcan
                                        </form>
                                    </div>
                                </div>
                               </div>
                               </div>
                            </div>
                        </div>
                        @endcan
                        <!-- Data Export Tools -->
                        @can('Data Export Map Tools')
                        <span data-toggle="tooltip" data-placement="bottom" title="Data Export">
                <a id="dataexporttools_control" class="btn btn-default collapse-control" role="button"
                   data-toggle="collapse" href="#collapse_data_export_tools" aria-expanded="false"
                   aria-controls="collapse_data_export_tools"><i class="fa-solid fa-file-export"></i>Data Export Tools</a>
                </span>
                        <div class="collapse" id="collapse_data_export_tools">
                            <div class="card">
                               <div class="card-body">
                                <!-- filter by wards -->

                                    <span data-toggle="tooltip" data-placement="bottom" title="Filter by Wards">
                            <a class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                               href="#collapse_ward_filter" aria-expanded="false"
                               aria-controls="collapse_ward_filter"><i class="fa fa-map"></i>Filter by Wards</a>
                        </span>
                                    <div class="collapse" id="collapse_ward_filter">
                                        <div class="card">
                                                <div class="card-body">
                                            <form role="form" name="ward_form" id="ward_form">
                                                <div class="form-group ">
                                                    <label for="ward" >Wards</label>
                                                    {!! Form::select('ward', $wards,null,
                                        ['id' => 'ward', 'multiple' => true, 'style' => 'width: 100%'])
                                        !!}
                                                </div>
                                                <div class="form-group ">
                                                    <label for="ward_overlay" >Overlay</label>
                                                    <select id="ward_overlay" style="width: 100%" >
                                                        <option value="">Select a layer</option>
                                                    </select>
                                                </div>
                                                <button type="submit"  id="ward_filter" class="btn btn-default">Filter</button>
                                                <button type="button" class="btn btn-default" id="ward_clear_button">
                                                    Clear
                                                </button>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Export <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="#" id="export_ward_filter_csv">CSV</a>
                                                        <a class="dropdown-item" href="#" id="export_ward_filter_kml">KML</a>
                                                        <a class="dropdown-item" href="#" id="export_ward_filter_shp">Shape File

                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                               </div>
                            <!-- filter by tax zones(Commented - Might be needed in the future) -->

                                    {{--<span data-toggle="tooltip" data-placement="bottom" title="Filter by Tax Zones">
                            <a class="btn btn-default collapse-control" role="button" data-toggle="collapse"
                               href="#collapse_tax_zone_filter" aria-expanded="false"
                               aria-controls="collapse_tax_zone_filter"><i
                                        class="fa fa-money"></i>Filter by Tax Zones</a>
                        </span>
                                    <div class="collapse" id="collapse_tax_zone_filter">
                                        <div class="card">
                                            <div class="card-body">
                                            <form role="form" name="tax_zone_form" id="tax_zone_form">
                                                <div class="form-group">
                                                    <label for="tax_zone">Tax Zones</label>
                                                    {!! Form::select('tax_zone',
                                        $taxZones,
                                        null,
                                        ['id' => 'tax_zone', 'multiple' => true, 'style' => 'width: 100%'])
                                        !!}
                                                </div>
                                                <div class="form-group">
                                                    <label for="tax_zone_overlay">Overlay</label>
                                                    <select id="tax_zone_overlay" style="width: 100%">
                                                        <option value="">Select a layer</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-default">Filter</button>
                                                <button type="button" class="btn btn-default"
                                                        id="tax_zone_clear_button">Clear
                                                </button>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        Export <span class="caret"></span>
                                                    </button>

                                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="#" id="export_tax_zone_filter_csv">CSV</a>
                                                        <a class="dropdown-item" href="#" id="export_tax_zone_filter_kml">KML</a>
                                                        <a class="dropdown-item" href="#" id="export_tax_zone_filter_shp">Shape File</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    </div>--}}

                            <!-- export data set -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Export Data Set of Custom Boundary">
                            <a href="#" id="export_control" class="btn btn-default map-control"><i class="fa-solid fa-file-export"></i>Export Data Set</a>
                        </span>

                                <!-- Building Owner Information -->
                                <span data-toggle="tooltip" data-placement="bottom"
                                      title="Building Owner Information">
                            <a href="#" id="update_tax_zone" class="btn btn-default map-control"><i
                                        class="fa fa-database"></i>Building Owner Information</a>
                        </span>

                                </div>
                            </div>
                        </div>

                        @endcan
                        <!-- Decision Tools -->
                        @can('Decision Map Tools')
                        <span data-toggle="tooltip" data-placement="bottom" title="Decision Tools">
                            <a id="decisiontools_control" class="btn btn-default collapse-control" role="button"
                               data-toggle="collapse" href="#collapse_decision_tools" aria-expanded="false"
                               aria-controls="collapse_decision_tools"><i class="fa-solid fa-calendar-check"></i>Decision Tools</a>
                        </span>
                        <div class="collapse" id="collapse_decision_tools">
                            <div class="card">
                              <div class="card-body">
                                <!-- find tax due buildings(Commented - Might be needed in the future) -->

                                    <span data-toggle="tooltip" data-placement="bottom" title="Tax Due Buildings">
                            <a id="duebuildings_control" class="btn btn-default collapse-control collapsed"
                               role="button" data-toggle="collapse" href="#collapse_find_tax_due_buildings"
                               aria-expanded="false" aria-controls="collapse_find_tax_due_buildings"><i class="fa-solid fa-building-circle-exclamation"></i>Tax Due Buildings</a>
                        </span>
                                    <div class="collapse" id="collapse_find_tax_due_buildings">
                                        <div class="card">
                                            <div class="card-body">
                                            <form role="form" name="tax_due_buildings_form" id="tax_due_buildings_form">
                                                <div class="form-group">
                                                    <label for="ward_tax_due">Wards</label>
                                                    {!! Form::select('ward',
                                        $wards,
                                        null,
                                        ['id' => 'ward_tax_due', 'multiple' => true, 'style' => 'width: 100%'])
                                        !!}
                                                </div>
                                                {{-- <div class="form-group">
                                                    <label for="tax_zone_tax_due">Tax Zones</label>
                                                   {!! Form::select('tax_zone',
                                        $taxZones,
                                        null,
                                        ['id' => 'tax_zone_tax_due', 'multiple' => true, 'style' => 'width: 100%'])
                                        !!}
                                                </div> --}}
                                                <button type="submit" class="btn btn-default">Filter</button>
                                                <button type="button" class="btn btn-default"
                                                        id="wardtaxzone_clear_button">Clear
                                                </button>

                                            </form>

                                            <table>
                                                <tr>
                                                    <td style="vertical-align: top;"><img
                                                                src="{{ asset("/img/building-green.png") }}"></td>
                                                    <td> - Tax Due</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <!-- find sewer potential buildings -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Find buildings potential to connect to the sewer">
                            <a href="#" id="drainpotential_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Sewers Potential Buildings</a>
                        </span>
                            <span data-toggle="tooltip" data-placement="bottom" title="buildings connect to sewer">
                            <a href="#" id="drainbuildings_control" class="btn btn-default map-control"><i class="fa fa-building"></i>Buildings to Sewer</a>
                        </span>


                            <!-- find buildings connecet to road -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="buildings connect to Road">
                            <a href="#" id="roadbuildings_control" class="btn btn-default map-control"><i
                                        class="fa fa-building"></i>Buildings to Road</a>
                        </span>

                            <!-- show dem profile -->

                                   {{-- <span data-toggle="tooltip" data-placement="bottom"
                                          title="Show DEM profile">
                             <a class="btn btn-default map-control" id="dem_profile"><i class="fa fa-bar-chart"
                                                                                        aria-hidden="true"></i>&nbsp; Show DEM profile</a>
                                        <!--<a href="#" id="roadbuildings_control" class="btn btn-default map-control"><i class="fa fa-building"></i>Find Buildings to Road</a>-->
                        </span> --}}

                        </span>
                            <span data-toggle="tooltip" data-placement="bottom" title="Hard to reach buildings">
                            <a href="#" id="road_inaccessible_control" class="btn btn-default map-control"><i class="fa-brands fa-buffer"  title="Hard to Reach Building"></i>Hard to Reach Buildings</a>
                        </span>

                        <span data-toggle="tooltip" data-placement="bottom" title="Building close to water bodies">
                            <a href="#" id="waterbody_inaccessible_control" class="btn btn-default map-control"><i class="fa-solid fa-water"></i>Building Close to Water Bodies</a>
                        </span>

                        <span data-toggle="tooltip" data-placement="bottom" title="Buildings Connected to Community Toilets">
                            <a href="#" id="ptct_network" class="btn btn-default map-control" ><i class="fa-solid fa-bezier-curve"></i>Buildings Connected to Community Toilets</a>
                        </span>
                            <!-- area population -->

                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Estimate population within custom boundary">
                            <a href="#" id="areapopulation_control" class="btn btn-default map-control"
                               data-toggle="tooltip"
                               data-placement="bottom" title="Calculate population in an area "><i class="fa fa-bars"
                                                                                  aria-hidden="true"></i>Area Population</a>
                        </span>
                        <span data-toggle="tooltip" data-placement="bottom"
                           title="Generate Information of Buildings and Containment of Custom Boundary Buffer Filter">
                             <a href="#" id="report_control_summary_buffer" class="btn btn-default map-control"><i
                             class="fa fa-list-alt"></i>Summary Information Buffer Filter</a>
                     </span>
                                    <!-- water bodies buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by water bodies buffer boundary">
                                        <a href="#" id="buildingswaterbodies_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Water Bodies Buffer Summary Information</a>
                                    </span>
                                    <!-- by wards -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by wards">
                                    <a href="#" id="buildingswards_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Wards Summary Information</a>
                                    </span>
                                    <!-- road buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by road buffer boundary">
                                    <a href="#" id="buildingsroads_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Road Buffer Summary Information</a>
                                    </span>
                                    <!-- point buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by point buffer boundary">
                                    <a href="#" id="pointbuffer_control" class="btn btn-default map-control"><i
                                                class="fa fa-building"></i>Point Buffer Summary Information</a>
                                    </span>
                            </div>
                        </div>
                        </div>
                        @endcan
                        <!-- Summary Information -->
                        {{--  @can('Summary Information Map Tools')
                        <span data-toggle="tooltip" data-placement="bottom" title="Summary Information">
                            <a id="summaryinformation_control" class="btn btn-default collapse-control" role="button"
                               data-toggle="collapse" href="#collapse_summary_information" aria-expanded="false"
                               aria-controls="collapse_summary_information"><i class="fa-solid fa-building-circle-arrow-right"></i>Summary Information</a>
                        </span>
                        <div class="collapse" id="collapse_summary_information">
                            <div class="card">
                              <div class="card-body">
                                    <!-- summary all -->
                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="Generate Information of Buildings and Containment of Custom Boundary Buffer Filter">
                                        <a href="#" id="report_control_summary_buffer" class="btn btn-default map-control"><i
                                            class="fa fa-list-alt"></i>Summary Information Buffer Filter</a>
                                    </span>
                                    <!-- water bodies buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by water bodies buffer boundary">
                                        <a href="#" id="buildingswaterbodies_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Water Bodies Buffer Summary Information</a>
                                    </span>
                                    <!-- by wards -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by wards">
                                    <a href="#" id="buildingswards_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Wards Summary Information</a>
                                    </span>
                                    <!-- road buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by road buffer boundary">
                                    <a href="#" id="buildingsroads_control" class="btn btn-default map-control"><i class="fa-solid fa-building"></i>Road Buffer Summary Information</a>
                                    </span>
                                    <!-- point buffer -->
                                            <span data-toggle="tooltip" data-placement="bottom"
                                                title="Generate information of building and containment by point buffer boundary">
                                    <a href="#" id="pointbuffer_control" class="btn btn-default map-control"><i
                                                class="fa fa-building"></i>Point Buffer Summary Information</a>
                                    </span>
                                        <!-- point buffer -->
                                                            <!--                                    <span data-toggle="tooltip" data-placement="bottom"
                                            title="Generate information of building and containment by inacceble to road">
                                            <a href="#" id="road_inaccessible_control" class="btn btn-default map-control"><i
                                                        class="fa fa-building"></i>Vacutug Inaccessible buildings Summary Information</a>
                                            </span>-->
                            </div>
                        </div>
                        @endcan
                    </div> --}}
                </div>

                <div class="row main-row"></div>


            </div><!-- /.content-wrapper -->
            <div class="col-md-3 sidebar sidebar-top-left" style="display: none;">
                <div class="panel-group sidebar-body map__" id="accordion-left">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#analysis">
                                    <i class="fa fa-list-alt"></i>
                                    Spatial Analysis
                                </a>
                                <span class="pull-right slide-submenu">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                  </span>

                            </h4>
                        </div>
                        <div id="analysis" class="panel-collapse collapse in">
                            <div class="panel-body list-group">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 sidebar sidebar-top-right" style="display: none;">
                <div class="panel-group sidebar-body map__" id="accordion-left">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#layers">
                                    <i class="fa fa-list-alt"></i>
                                    Layers
                                </a>
                                <span class="pull-right slide-submenu">
                  <i class="glyphicon glyphicon-chevron-right"></i>
                </span>

                            </h4>
                        </div>
                        <div id="layers" class="panel-collapse collapse in">
                            <div class="panel-body list-group" id="layerswitcher">

                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="col-md-6 sidebar-bottom-left">
                <div class="panel-group sidebar-body map__" style="display: none;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#featureinfo-collapse">
                                    <i class="fa fa-table"></i>
                                    Info
                                </a>
                                <span class="pull-right slide-submenu">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                  </span>

                            </h4>
                        </div>
                        <div id="featureinfo-collapse" class="panel-collapse collapse in">
                            <div class="panel-body list-group">

                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="mini-submenu mini-submenu-top-left pull-left">
                <i class="glyphicon glyphicon-tasks"></i>
            </div>
            <div class="mini-submenu mini-submenu-top-right pull-right">
                <i class="glyphicon glyphicon-list-alt"></i>
            </div>
            <div class="mini-submenu mini-submenu-bottom-left pull-left" style="display: none;" hidden>
                <i class="fa fa-table"></i>
            </div>
        </div>
    </div>




    <footer class="main-footer" style="position:fixed;right:0;bottom:0;width: 100%;z-index:35;padding:7px;height:35px;">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline ">
        	<strong>Developed by:</strong> <a href="http://www.innovativesolution.com.np">@lang('public.waterborne.insol')</a>
    	</div>
        <strong> Base IMIS <i class="fa-regular fa-copyright"> </i>  2022-2024 by <a href="http://www.innovativesolution.com.np">
    @lang('public.waterborne.insol')</a> is licensed under <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/?ref=chooser-v1">CC BY-NC-SA 4.0 </a>
</strong>

        <!-- Default to the left -->
        <div id="footer-content">
            <div id="output"></div>
        </div>

    </footer>




    <div class="modal fade" id="print_modal" tabindex="-1" role="dialog" aria-labelledby="print_modal_label">
        <div class="modal-dialog modal-sm print_modal_dialog ui-draggable draggable" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="print_modal_label">Print Map</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">×</span>
</button>
                </div>

                    <div class="modal-body">
                        <div class="form-group required">
                            <label class="control-label">Title</label>
                            <input type="text" class="form-control" id="print_map_title"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" rows="3" id="print_map_description"></textarea>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Scale</label>
                        <select class="form-control" id="print_scale">
                            <option value="500" selected>1:500</option>
                            <option value="1000">1:1000</option>
                            <option value="2000">1:2000</option>
                            <option value="5000">1:5000</option>
                            <option value="10000">1:10000</option>
                            <option value="20000">1:20000</option>
                            <option value="25000">1:25,000</option>
                            <option value="50000">1:50,000</option>1:75,000
                            <option value="75000">1:75,000</option>
                            <option value="100000">1:100,000</option>
                        </select>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Paper Size</label>
                        <select class="form-control " id="print_paper_size">
                            <option value="A4" selected>A4</option>
                            <option value="A3">A3</option>
                        </select>
                        </div>
                        <div class="form-group">
                        <label class="control-label">DPI</label>
                        <select class="form-control " id="print_dpi">
                            <option value="75" >75</option>
                            <option value="150" selected>150</option>
                            <option value="200" >200</option>
                        </select>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Orientation</label>
                        <select class="form-control " id="box_orientation">
                            <!--<option value="portrait" selected>Portrait</option>-->
                            <option value="landscape" >Landscape</option>
                        </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info" id="print_map_fish">Print</button>
                    </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="coordinate_search_modal" tabindex="-1" role="dialog"
         aria-labelledby="print_modal_label">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content draggable">
                <div class="modal-header">

                    <h4 class="modal-title" id="print_modal_label">Locate point by coordinate</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">×</span>
</button>
                </div>
                <form name="latlong_form" id="latlong_form">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" class="form-control" id="point_longitude" placeholder="85.372873" value="85.372873"/>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" class="form-control" id="point_latitude" placeholder="27.636295" value="27.636295"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="emptying_containments_modal" tabindex="-1" role="dialog"
         aria-labelledby="emptying_containments_modal_label">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="emptying_containments_modal_label">Containments to be emptied</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="proposed_emptying_modal" tabindex="-1" role="dialog"
         aria-labelledby="proposed_emptying_modal_label">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DEMModal" tabindex="-1" role="dialog" aria-labelledby="DEMModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content draggable">
                <div class="modal-header">
                    <h5 class="modal-title" id="DEMModalLabel">Elevation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <canvas id="DEMChart" width="100" height="100"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="feedback-popup" class="ol-popup" style="display: none;">
        <div id="feedback-popup-content"></div>
        <button id="feedback-popup-closer" type="button" class="btn btn-default float-right xol-popup-closer">Close</button>
    </div>
    <!-- Modal -->
    <div id="popup-drain-potential" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-drain-potential">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Buffer Distance(m)</label>
                            <input type="text" class="form-control col-md-4" id="buffer-distance" placeholder="" value="">

                        <input type="hidden" id="drain-code" value=""/>
                        <input type="hidden" id="drain-long" value=""/>
                        <input type="hidden" id="drain-lat" value=""/>
                        <div class="col-md-4">
                        <button type="submit" class="btn btn-info">Get Buildings</button>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="popup-waterbodies-buildings" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-waterbodies-buildings">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Buffer Distance(m)</label>
                           <input type="text" class="form-control col-md-4" id="buffer-distance-waterbodies" placeholder=""
                                                         value="">

                            <div class="col-md-4">
                            <input type="hidden" id="water-body-code" value=""/>
                            <input type="hidden" id="water-body-long" value=""/>
                            <input type="hidden" id="water-body-lat" value=""/>
                            <button type="submit" class="btn btn-info">Get Information</button>
                            </div>
                        </div>
                    </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
        </div>
    <div id="popup-polygon-buffer" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-buffer-polygon">
                        <div class="form-group row">

                            <label class="col-form-label col-md-4">Buffer
                                Distance(m)</label>

                                <input type="hidden" id="polygon-geom" value=""/>
                        <input type="hidden" id="polygon-coordinates" value=""/>
                                <input type="text" class="form-control  col-md-4" id="buffer-distance-polygon" placeholder=""
                                       value="">


                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">Get Information</button>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="popup-road-buildings" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-road-buildings">
                        <div class="form-group row">
                             <label class="col-form-label col-md-4">Buffer Distance(m)</label>

                            <input type="text" class="form-control col-md-4" id="buffer-distance-road" placeholder="" value="">

                        <input type="hidden" id="road-code" value=""/>
                        <input type="hidden" id="road-long" value=""/>
                        <input type="hidden" id="road-lat" value=""/>
                        <div class="col-md-4">
                        <button type="submit" class="btn btn-info">Get Information</button>
                        </div>
                         </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="popup-point-buffer" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-point-buffer-buildings">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Buffer Distance(m)</label>
                             <input type="text" class="form-control col-md-4" id="buffer-distance-point" placeholder="" value="">


                        <div class="col-md-4">
                            <input type="hidden" id="point-buffer-long-pos" value=""/>
                        <input type="hidden" id="point-buffer-lat-pos" value=""/>
                        <input type="hidden" id="point-buffer-long" value=""/>
                        <input type="hidden" id="point-buffer-lat" value=""/>
                        <button type="submit" class="btn btn-info">Get Information</button>
                        </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="road-inaccessible-input-form" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content draggable">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form-road-inaccessible">
                        <div class="form-group row">
                            <label for="buffer-distance-polygon" class="control-label col-md-4">Road Width(meters)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="road-width" placeholder=""
                                       value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="buffer-distance-polygon" class="control-label col-md-4">Vacutug Pipe
                                Range(feets)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="vacutug-range" placeholder=""
                                       value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="offset-md-4 col-md-4">
                                <button type="submit" class="btn btn-default">Get Information</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        // Google Map
        var gmap;
        var markerColors = ['green', 'light-blue', 'orange', 'pink', 'purple', 'red', 'yellow', 'blue','gold'];

        function initMap() {
            gmap = new google.maps.Map(document.getElementById('gmap'), {
                disableDefaultUI: true,
                keyboardShortcuts: false,
                draggable: false,
                disableDoubleClickZoom: true,
                scrollwheel: false,
                streetViewControl: false,
                // center: {lat: 22.8456, lng: 89.5403},
                // zoom: 12
            });
        }
    </script>
@stop
@push('scripts')
<!--    <script src="{{ asset ('/old/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset ('/old/js/html2canvas.js') }}"></script>-->


    <script type="text/javascript">
        $(document).ready(function () {
            //Information popup tab pane switching

            new autoComplete({
                cache: false,
                minChars: 1,
                selector: '#search_keyword_text',
                source: function(term, response){
                let layer = $('#search_layer_select').val();
                $.getJSON('{{ url("maps/search-auto-complete") }}/' + layer + '/' + term, function(data){ response(data); });
                }
            });
            new autoComplete({
                cache: false,
                minChars: 1,
                selector: '#building_value_text',
                source: function(term, response){
                let layer = $('#building_field_select').val();
                $.getJSON('{{ url("maps/search-auto-complete") }}/' + layer + '/' + term, function(data){ response(data); });
                }
            });

            $('#ward, #tax_zone, #watlog_overlay, #ward_tax_due, #tax_zone_tax_due').multipleSelect({
                filter: true
            });

            function displayAjaxLoader() {
                if ($('.ajax-modal').length == 0) {
                    $('body').append('<div class="ajax-modal"><div class="ajax-modal-content"><div class="loader"></div></div></div>');
                }
            }

            function removeAjaxLoader() {
                $('.ajax-modal').remove();
            }

            function displayAjaxError() {
                displayAjaxErrorModal('An error occurred');
            }

            function displayAjaxErrorModal(message) {
                if ($('.ajax-modal').length > 0) {
                    var html = '<div class="ajax-modal-message">';
                    html += '<span>' + message + '</span>';
                    html += '<a href="#" class="ajax-modal-close-btn"><i class="fa fa-times"></i></a>';
                    html += '</div>';

                    $('.ajax-modal-content').html(html);
                }
            }

            $('body').on('click', 'a.ajax-modal-close-btn', function (e) {
                e.preventDefault();
                removeAjaxLoader();
            });

            $('#map-right-sidebar-toggle').click(function (e) {
                e.preventDefault();
                var sidebar = $('#map-right-sidebar');
                if (sidebar.css('right') == '0px') {
                    sidebar.animate({right: sidebar.outerWidth() * -1});
                } else {
                    sidebar.animate({right: 0});
                }
            });

            $(".sidebar-top-left .slide-submenu").on("click", function () {
                var i = $(this);
                i.closest(".sidebar-body").fadeOut("slide", function () {
                    $(".mini-submenu-top-left").fadeIn();
                    applyMargins();
                })
            });

            $(".mini-submenu-top-left").on("click", function () {
                var i = $(this);
                $(".sidebar-top-left .sidebar-body").fadeIn("slide");
                i.hide();
                applyMargins();
            });

            $(".sidebar-top-right .slide-submenu").on("click", function () {
                var i = $(this);
                i.closest(".sidebar-body").fadeOut("slide", function () {
                    $(".mini-submenu-top-right").fadeIn();
                    applyMargins();
                });
            });

            $(".mini-submenu-top-right").on("click", function () {
                var i = $(this);
                $(".sidebar-top-right .sidebar-body").fadeIn("slide");
                i.hide();
                applyMargins();
            });

            $(".sidebar-bottom-left .slide-submenu").on("click", function () {
                var i = $(this);
                i.closest(".sidebar-body").fadeOut("slide", function () {
                    $(".mini-submenu-bottom-left").fadeIn();
                    applyMargins();
                })
            });

            $(".mini-submenu-bottom-left").on("click", function () {
                var i = $(this);
                $(".sidebar-bottom-left .sidebar-body").fadeIn("slide");
                i.hide();
                applyMargins();
            });

            $(window).on("resize", applyMargins);

            $('#next_emptying_date').focus(function () {
                $(this).blur();
            });


            $('#application_date_field').focus(function () {
                $(this).blur();
            });

            $('#application_not_tp_date_field').focus(function () {
                $(this).blur();
            });

            $('#proposed_emptying_date').focus(function () {
                $(this).blur();
            });

            var layer = '{{ Input::get('layer') }}';
            var field = '{{ Input::get('field') }}';
            var val = '{{ Input::get('val') }}';
            var action = '{{ Input::get('action') }}';
            var currentControl = '';
            // Geoserver workspace name from constants
            var workspace = '<?php echo Config::get("constants.GEOSERVER_WORKSPACE"); ?>';
            // URL of GeoServer
            var gurl = "<?php echo Config::get("constants.GEOSERVER_URL"); ?>/";
            // URL of WMS
            var gurl_wms = gurl + 'wms';
            // URL of WFS
            var gurl_wfs = gurl + 'wfs';
            // Authentication Keys
            var authkey = '<?php echo Config::get("constants.AUTH_KEY"); ?>';
            // URL of GeoServer Legends
            var gurl_legend = gurl_wms + "?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&BBOX=84.883118,27.149589,85.896606,28.071980&LAYER=";

            var dragAndDropInteraction = new ol.interaction.DragAndDrop({
                formatConstructors: [
                    ol.format.GPX,
                    ol.format.GeoJSON,
                    ol.format.IGC,
                    ol.format.KML,
                    ol.format.TopoJSON
                ]
            });
            // OpenLayers Map
            var map = new ol.Map({
                controls: ol.control.defaults().extend([new ol.control.ScaleLine()]),
                interactions: ol.interaction.defaults({
                    altShiftDragRotate: false,
                    dragPan: false,
                    rotate: false,
                    // mouseWheelZoom: false,
                    doubleClickZoom: false
                }).extend([new ol.interaction.DragPan({kinetic: null}), dragAndDropInteraction]),
                target: 'olmap',
                view: new ol.View({
                    // center: ol.proj.transform([89.5403, 22.8456], 'EPSG:4326', 'EPSG:3857'),
                    // zoom: 12,
                    minZoom: 13,
                    maxZoom: 21,
                    extent: ol.proj.transformExtent([85.2624, 27.6956, 85.4572, 27.5901], 'EPSG:4326', 'EPSG:3857')
                })
            });

            // Base Layers Object
            var bLayer = {
                osm: {name: 'OpenStreetMap', type: 'osm'},
                bing_aerial: {name: 'Bing Aerial', type: 'bing', imagerySet: 'Aerial'},
                bing_aerial_labels: {name: 'Bing Aerial with Labels', type: 'bing', imagerySet: 'AerialWithLabels'},
                //bing_road: {name: 'Bing Road', type: 'bing', imagerySet: 'Road'},
                //bingbing_road_collins_bart: {name: 'Bing Collins Bart', type: 'bing', imagerySet: 'collinsBart'},
                //bing_ordnance_survey: {name: 'Bing Ordnance Survey', type: 'bing', imagerySet: 'ordnanceSurvey'},
                google_streets: {name: 'Google Streets', type: 'google', mapType: 'roadmap'},
                google_hybrid: {name: 'Google Hybrid', type: 'google', mapType: 'hybrid'},
                google_satellite: {name: 'Google Satellite', type: 'google', mapType: 'satellite'},
                google_terrain: {name: 'Google Terrain', type: 'google', mapType: 'terrain'}
            };

            // HTML for Base Layers select box
            var html = '';
            // Looping through Base Layers Object
            $.each(bLayer, function (key, value) {
                var layer;
                if (value.type == 'stamen') {
                    // Creating layer object
                    layer = new ol.layer.Tile({
                        visible: false,
                        source: new ol.source.Stamen({
                            layer: value.layer
                        })
                    });
                } else if (value.type == 'osm') {
                    // Creating layer object
                    layer = new ol.layer.Tile({
                        visible: false,
                        source: new ol.source.OSM()
                    });
                } else if (value.type == 'bing') {
                    // Creating layer object
                    layer = new ol.layer.Tile({
                        visible: false,
                        source: new ol.source.BingMaps({
                            key: 'AlonC1vgjxPDct_RRLBn9Yyq41IXw5otDfbJRDfbNZQRblZC1Eum9BmE-zs499ED',
                            imagerySet: value.imagerySet
                        })
                    });
                }

                // if layer object has been created for the current Base Layer
                if (layer) {
                    // Adding layer to OpenLayers Map
                    map.addLayer(layer);
                    // Assigning layer to layer property of the current Base Layer
                    bLayer[key].layer = layer;

                }

                // select box option for the current Base Layer
                html += '<option value="' + key + '">' + value.name + '</option>'
            });


            // adding HTML to select box
            $('#base_layer_select').append(html);

            // Handler for Base Layer select box change
            $('#base_layer_select').change(function () {
                var selected = $(this).val();
                if (selected && bLayer[selected]) { // if selected option is not 'None' or selected option exists in Base Layers Object
                    if (bLayer[selected].type == 'google') { // if selected option is Google Map
                        // Hide all Base Layers
                        $.each(bLayer, function (key, value) {
                            if (bLayer[key].layer) {
                                bLayer[key].layer.setVisible(false);
                            }
                        });
                        // Set Google Map Type
                        gmap.setMapTypeId(bLayer[selected].mapType);
                        // Set centre and zoom of Google Map to those of OpenLayers Map
                        onCenterChanged();
                        onResolutionChanged();
                        // Add handler to OpenLayers view centre change and zoom change events
                        map.getView().on('change:center', onCenterChanged);
                        map.getView().on('change:resolution', onResolutionChanged);
                        // Make Google Map visible
                        $('#gmap').css('visibility', 'visible');
                        // Add handler to window resize
                        $(window).on('resize', onWindowResize);
                    } else { // if seleceted option is OpenStreetMap or Bing Map
                        // Make Google Map invisible
                        $('#gmap').css('visibility', 'hidden');
                        // Show selected Base Layer and hide other Base Layers
                        $.each(bLayer, function (key, value) {
                            if (bLayer[key].layer) {
                                bLayer[key].layer.setVisible(key == selected);
                            }
                        });
                        // Remove handler from OpenLayers view centre change and zoom change events
                        map.getView().un('change:center', onCenterChanged);
                        map.getView().un('change:resolution', onResolutionChanged);
                        // Remove handler to window resize
                        $(window).off('resize', onWindowResize);
                    }
                } else { // if selected option is 'None' or selected options does not exists in Base Layers Object
                    // Make Google Map invisible
                    $('#gmap').css('visibility', 'hidden');
                    // Hide all Base Layers
                    $.each(bLayer, function (key, value) {
                        if (bLayer[key].layer) {
                            bLayer[key].layer.setVisible(false);
                        }
                    });
                    // Remove handler from OpenLayers view centre change and zoom change events
                    map.getView().un('change:center', onCenterChanged);
                    map.getView().un('change:resolution', onResolutionChanged);
                    // Remove handler to window resize
                    $(window).off('resize', onWindowResize);
                }
            });

            // Handler for OpenLayers view centre change event
            function onCenterChanged() {
                // Get centre of OpenLayers Map
                var center = ol.proj.transform(map.getView().getCenter(), 'EPSG:3857', 'EPSG:4326');
                // Set centre of Google Map to that of OpenLayers Map
                gmap.setCenter(new google.maps.LatLng(center[1], center[0]));
            }

            // Handler for OpenLayers view zoom change event
            function onResolutionChanged() {
                // Set zoom of Google Map to that of OpenLayers map
                gmap.setZoom(map.getView().getZoom());
            }

            // Handler for window resize
            function onWindowResize() {
                google.maps.event.trigger(gmap, 'resize');
                // Set centre and zoom of Google Map to those of OpenLayers Map
                onCenterChanged();
                onResolutionChanged();
            }

            // Set Stamen Toner to Base Layer select box
            $('#base_layer_select').val('')

            // Trigger Base Layer select box change
            $('#base_layer_select').trigger('change');

            // Filters Object
            var mFilter = {
                ward: '',
                // toilet: '',
                buildings_layer_structure_type: '',
                buildings_tax_status_layer: '',
                buildings_water_payment_status_layer: '',
            };

            // Overlays Object
            var mLayer = {

                citypolys_layer: {
                    name: ' Municipality',
                    styles: {},
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },
                wardboundary_layer: {
                    name: 'Ward Boundary',
                    styles: {},
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },
                @can('Containments Map Layer')
                containments_layer: {
                    name: 'Containments',
                    styles: {
                        containments_layer_none: {
                            name: 'None',
                            clipLegend: true,
                            showCount: true
                        },

                        containments_layer_type: {
                            name: 'Type',
                            clipLegend: true,
                            showCount: true,
                        },

                        containments_outlet_connection: {
                            name: 'Outlet Connection',
                            clipLegend: true,
                            showCount: true,
                        },

                        containments_layer_emptied_status: {
                            name: 'Emptied Status',
                            clipLegend: true,
                            showCount: true
                        },
                        containments_layer_no_of_times_emptied: {
                            name: 'Times Emptied',
                            clipLegend: true,
                            showCount: true
                        },
                        containments_period_from_construction: {
                            name: 'Year of Construction',
                            clipLegend: true,
                            showCount: true
                        },
                        /**
                         *containments_last_emptied_year uses a SQL View made in the GeoServer
                         * */
                        containments_last_emptied_year: {
                            name: 'Last Emptied Year',
                            clipLegend: true,
                            showCount: true
                        },

                        containments_layer_location: {
                            name: 'Location',
                            clipLegend: false,
                            showCount: true
                        }

                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan

                @can('Buildings Map Layer')
                buildings_layer: {
                    name: 'Building',
                    styles: {
                        buildings_layer_none: {
                            name: 'None',
                            clipLegend: true,
                            showCount: true
                        },
                        buildings_layer_structure_type: {
                            name: 'Structure Type',
                            clipLegend: false,
                            showCount: true
                        },
                        buildings_layer_flrcount: {
                            name: 'Floor Count',
                            clipLegend: false,
                            showCount: true
                        },
                        buildings_layer_functional_use: {
                            name: 'Building Use',
                            clipLegend: true,
                            showCount: true
                        },

                        buildings_layer_building_associated_to: {
                            name: 'Associated Building',
                            clipLegend: true,
                            showCount: true
                        },
                        buildings_layer_toilet: {
                            name: 'Toilet',
                            clipLegend: true,
                            showCount: true
                        },

                        buildings_layer_toilet_connection: {
                            name: 'Toilet Connection',
                            clipLegend: true,
                            showCount: true
                        },
                        buildings_layer_water_source: {
                            name: 'Water Source',
                            clipLegend: false,
                            showCount: true
                        },
                        buildings_layer_well_presence: {
                            name: 'Well Presence',
                            clipLegend: false,
                            showCount: true
                        },

                        low_income_houses: {
                            name: 'Low Income Houses',
                            clipLegend: false,
                            showCount: true
                        },
                        building_construction_year: {
                            name: 'Year of Construction',
                            clipLegend: false,
                            showCount: true
                        },

                        solid_waste_management_service: {
                            name: 'SWM Service',
                            clipLegend: false,
                            showCount: true
                        },
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @if(auth()->user()->can('Tax Payment Status Buildings Map Layer') || auth()->user()->can('Water Payment Status Map Layer'))
                @can('Tax Payment Status Buildings Map Layer')
                buildings_tax_status_layer: {
                    name: 'Property Tax Collection ISS',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @can('Water Payment Status Map Layer')
                buildings_water_payment_status_layer: {
                    name: 'Water Supply ISS',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @endif
                @can('Treatment Plants Map Layer')
                treatmentplants_layer: {
                    name: 'Treatment Plants',
                    styles: {
                        treatmentplants_layer_none: {
                        name: 'None',
                        clipLegend: true,
                        showCount: true
                    },
                    treatmentplants_layer_status: {
                        name: 'Status',
                        clipLegend: true,
                        showCount: true
                    },
                    treatmentplants_layer_type: {
                        name: 'Type',
                        clipLegend: true,
                        showCount: true
                    },
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @can('Roads Map Layer')
                roadlines_layer: {
                    name: 'Road Network',
                    styles: {
                        roadlines_layer_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: false
                        },
                        roadlines_layer_hierarchy: {
                            name: 'Hierarchy',
                            clipLegend: false,
                            showCount: false
                        },
                        roadlines_layer_width: {
                            name: 'Carrying Width',
                            clipLegend: false,
                            showCount: false
                        },
                        roadlines_layer_surface_type: {
                            name: 'Surface Type',
                            clipLegend: false,
                            showCount: false,
                        },

                    },
                    clipLegend: false,
                    showCount: false,
                    filters: [],
                },
                @endcan
                @can('Sewers Line Map Layer')
                sewerlines_layer: {
                    name: 'Sewer Network',
                    styles: {
                        sewer_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: false
                        },
                        sewerlines_layer_size: {
                            name: 'Sewer Diameter (mm)',
                            clipLegend: false,
                            showCount: false
                        },
                        sewerlines_layer_length: {
                            name: 'Sewer Length(m)',
                            clipLegend: false,
                            showCount: false
                        },
                    },
                    clipLegend: false,
                    showCount: false,
                    filters: [],
                },
                @endcan
                @can('WaterSupply Network Map Layer')
                watersupply_network_layer: {
                    name: 'Water Supply Network',
                    styles: {
                        watersupply_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: true
                        },
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @can('Drains Map Layer')
                    drains_layer: {
                    name: 'Drain Network',
                    styles: {
                        drains_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: true
                        },
                        drain_type: {
                            name: 'Surface Type',
                            clipLegend: false,
                            showCount: false
                        },
                        drain_covertype : {
                            name: 'Cover Type',
                            clipLegend: false,
                            showCount: false
                        },
                    },
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },
                @endcan

                @can('Places Map Layer')
                places_layer: {
                    name: 'Places',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan

                /*@hasanyrole('Super Admin|Municipality Admin')
        jhe_contour: {
            name: 'Contour',
            styles: {},
            clipLegend: false,
            showCount: true,
            filters: [],
        },
        @endhasanyrole*/
                /*@hasanyrole('Super Admin|Municipality Admin')
        jhe_taxzone: {
            name: 'Tax Zone',
            styles: {
                jhe_taxzone_none: {
                    name: 'None',
                    clipLegend: false,
                    showCount: true
                },
                jhe_taxzone_bldgtaxpdprprtn: {
                    name: 'Percentage of tax paid',
                    clipLegend: true,
                    showCount: true
                },
                jhe_taxzone_wtrpmntprprtn: {
                    name: 'Percentage of watersupply payment paid',
                    clipLegend: true,
                    showCount: true
                }
            },
            clipLegend: false,
            showCount: true,
            filters: [],
        },
        @endhasanyrole*/
        @can('Water Body Map Layer')
        waterbodys_layer: {
            name: 'Water Bodies',
            styles: {},
            clipLegend: true,
            showCount: true,
            filters: [],
        },
        @endcan

        @can('Water Samples Map Layer')
                water_samples_layer: {
                    name: 'Water Samples',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan

                @can('Land Use Map Layer')
                landuses_layer: {
                    name: 'Land Use',
                    styles: {},
                    clipLegend: true,
                    showCount: false,
                    filters: [],
                },
                @endcan
                @can('Wards Map Layer')
                wards_layer: {
                    name: 'Ward Wise Info',
                    styles: {
                        wards_layer_none: {
                            name: 'None',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_build: {
                            name: 'No. of Buildings',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_rcc_framed: {
                            name: 'No. of RCC Framed',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_wooden_mud: {
                            name: 'No. of Wooden/Mud',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_load_bearing: {
                            name: 'No. of Load Bearing',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_cgi_sheet: {
                            name: 'No. of CGI Sheet',
                            clipLegend: true,
                            showCount: true
                        },
                        /*wards_layer_no_pucca: {
                            name: 'No. of Pucca Buildings',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_katcha: {
                            name: 'No. of Katcha Buildings',
                            clipLegend: true,
                            showCount: true
                        },*/
                        wards_layer_no_build_directly_to_sewerage_network: {
                            name: 'No. of Building Connected to Sewerage Network',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_contain: {
                            name: 'No. of Containments',
                            clipLegend: true,
                            showCount: true
                        },
                        // wards_layer_no_pit: {
                        //     name: 'No. of Pits',
                        //     clipLegend: true,
                        //     showCount: true
                        // },
                        // wards_layer_no_septic_tank_without_soak_pit: {
                        //     name: 'No. of Septic Tanks Without Soak Away Pit',
                        //     clipLegend: true,
                        //     showCount: true
                        // },
                        // wards_layer_no_holding_tank: {
                        //     name: 'No. of Holding Tanks',
                        //     clipLegend: true,
                        //     showCount: true
                        // },



                        wards_layer_no_pit_holding_tank: {
                            name: 'No.of Pit/Holding Tank',
                            clipLegend: true,
                            showCount: true
                        },

                wards_layer_no_septic_tank: {
                            name: 'No.of Septic Tank',
                            clipLegend: true,
                            showCount: true
                        },
                        /*wards_layer_no_septic_tank_connected_to_sewarage_network: {
                            name: 'No. of Septic Tanks Connected to Sewarage Networks',
                            clipLegend: true,
                            showCount: true
                        },*/
                        wards_layer_total_rdlen: {
                            name: 'Total Length of Roads(km)',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_bldgtaxpdprprtn: {
                            name: 'Tax Paid %',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_wtrpmntprprtn: {
                            name: 'Watersupply Payment Paid %',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_population_served: {
                            name: 'Population Served',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_household_served: {
                            name: 'Household Served',
                            clipLegend: true,
                            showCount: true
                        },
                        wards_layer_no_emptying: {
                            name: 'No. of Containments Emptied',
                            clipLegend: true,
                            showCount: true
                        },

                        // wards_layer_contain_build_ratio: {
                        //     name: 'Containment Building Ratio',
                        //     clipLegend: true,
                        //     showCount: true
                        // }
                        wards_layer_no_emptying: {
                            name: 'No. of Emptying Requests',
                            clipLegend: true,
                            showCount: true
                        },
                        // wards_layer_no_septic_tank_with_soak_pit: {
                        //     name: 'No. of Septic Tank With Soak Away Pit',
                        //     clipLegend: false,
                        //     showCount: true
                        // },
                    },
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @can('Summarized Grids Map Layer')
                grids_layer: {
                    name: 'Summarized Grids (0.5 km)',
                    styles: {
                        grids_layer_no_build: {
                    name: 'No. of Buildings',
                    clipLegend: true,
                    showCount: true
                },
                /*grids_layer_no_pucca: {
                    name: 'No. of Pucca',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_katcha: {
                    name: 'No. of Katcha',
                    clipLegend: true,
                    showCount: true
                },*/
                grids_layer_no_contain: {
                    name: 'No. of Containments',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_rcc_framed: {
                    name: 'No. of RCC Framed',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_wooden_mud: {
                    name: 'No. of Wooden/Mud',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_load_bearing: {
                    name: 'No. of Load Bearing',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_cgi_sheet: {
                    name: 'No. of CGI Sheet',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_build_directly_to_sewerage_network: {
                    name: 'No. of Building Connected to Sewerage Network',
                    clipLegend: true,
                    showCount: true
                },
                // grids_layer_no_pit: {
                //     name: 'No. of Pits',
                //     clipLegend: true,
                //     showCount: true
                // },
                // grids_layer_no_septic_tank_without_soak_pit: {
                //     name: 'No. of Septic Tanks Without Soak Away Pit',
                //     clipLegend: true,
                //     showCount: true
                // },
                // grids_layer_no_holding_tank: {
                //     name: 'No. of Holding Tank',
                //     clipLegend: true,
                //     showCount: true
                // },
                /*grids_layer_no_septic_tank_connected_to_sewarage_network: {
                    name: 'No. of Septic Tanks Connected to Sewarage Networks',
                    clipLegend: true,
                    showCount: true
                },*/


                grids_layer_no_pit_holding_tank: {
                            name: 'No.of Pit/Holding Tank',
                            clipLegend: true,
                            showCount: true
                        },
                grids_layer_no_septic_tank: {
                            name: 'No.of Septic Tank',
                            clipLegend: true,
                            showCount: true
                        },

                grids_layer_total_rdlen: {
                    name: 'Total Length of Roads(km)',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_bldgtaxpdprprtn: {
                    name: 'Tax Paid %',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_wtrpmntprprtn: {
                    name: 'Watersupply Payment Paid %',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_population_served: {
                    name: 'Population Served',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_household_served: {
                    name: 'Household Served',
                    clipLegend: true,
                    showCount: true
                },
                grids_layer_no_emptying: {
                    name: 'No. of Emptying Requests',
                    clipLegend: true,
                    showCount: true
                },
                // grids_layer_no_septic_tank_with_soak_pit: {
                //             name: 'No. of Septic Tank With Soak Away Pit',
                //             clipLegend: true,
                //             showCount: true
                //         },
                        // grids_layer_contain_build_ratio: {
                        //     name: 'Containment Building Ratio',
                        //     clipLegend: true,
                        //     showCount: true
                        // }

                        /*grids_layer_sum_pop: {
                            name: 'Sum Pop',
                            clipLegend: true,
                            showCount: true
                        },
                        grids_layer_sum_cont: {
                            name: 'Sum Cont',
                            clipLegend: true,
                            showCount: true
                        },
                        grids_layer_sum_well: {
                            name: 'Sum Well',
                            clipLegend: true,
                            showCount: true
                        },
                        grids_layer_den_cont: {
                            name: 'Den Cont',
                            clipLegend: true,
                            showCount: true
                        },
                        grids_layer_den_well: {
                            name: 'Den Well',
                            clipLegend: true,
                            showCount: true
                        },
                        grids_layer_sum_unseal: {
                            name: 'Sum Unseal',
                            clipLegend: true,
                            showCount: true
                        },*/
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan


                @can('Sanitation System Map Layer')
                sanitation_system_layer: {
                    name: 'Sanitation System',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                {{-- @can('Hotspot Identifications Map Layer')
                    waterborne_hotspots_layer:{
                        name: 'Hotspot Identifications',
                    styles:{
                        waterborne_hotspots_layer_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: true
                        },
                        waterborne_hotspots_layer_yearly: {
                            name: 'Yearly',
                            clipLegend: false,
                            showCount: true
                        },
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan --}}
               {{--  @can('FSM Campaigns Map Layer')
                fsm_campaigns:{
                    name: 'FSM Campaigns',
                    styles:{
                        fsm_campaigns: {
                            name: 'None',
                            clipLegend: false,
                            showCount: true
                        },
                        fsm_campaigns_yearly: {
                            name: 'Yearly',
                            clipLegend: true,
                            showCount: true
                        },
                        fsm_campaigns_supported_by: {
                            name: 'Supported By',
                            clipLegend: true,
                            showCount: true
                        },
                    },
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },
                @endcan --}}
                @can('CT/PT General Information Map Layer')
                toilets_layer: {
                    name: 'Toilets PT/CT',
                    styles: {
                        toilets_layer_none: {
                        name: 'None',
                        clipLegend: false,
                        showCount: true
                    },
                    toilets_layer_type: {
                        name: 'Toilet Type',
                        clipLegend: false,
                        showCount: true
                    },
                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan
                @can('Public Health Map Layer')
                   waterborne_hotspots_layer:{
                        name: 'Waterborne Hotspots',
                    styles:{
                        waterborne_hotspots_layer_none: {
                            name: 'None',
                            clipLegend: false,
                            showCount: true
                        },
                        infected_disease: {
                            name: 'Infected Disease',
                            clipLegend: false,
                            showCount: true
                        },


                    },
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                },
                @endcan

                low_income_communities_layer: {
                    name: 'Low Income Community',
                    styles: {},
                    clipLegend: false,
                    showCount: true,
                    filters: [],
                },

            {{-- jhe_populations: {
                name: 'Population',
                styles: {},
                clipLegend: true,
                showCount: true,
                filters: [],
            },--}}
            {{-- dem_profiles: {
                    name: 'Digital Elevation Model',
                    styles: {},
                    clipLegend: true,
                    showCount: true,
                    filters: [],
                }, --}}
            };

            // Array of layers that should be visable only when zoom is greater than 14
            var conditionalLayers = ['buildings_layer', 'containments_layer', 'places_layer', 'jhe_taxzone', 'jhe_drainln', 'jhe_contour'];

            // Add filter to layer
            function addFilterToLayer(layer, filter) {
                if (mLayer.hasOwnProperty(layer) && mFilter.hasOwnProperty(filter)) {
                    var index = mLayer[layer].filters.indexOf(filter);
                    if (index == -1) {
                        mLayer[layer].filters.push(filter);
                    }
                }
            }

            // Remove filter from layer
            function removeFilterFromLayer(layer, filter) {
                if (mLayer.hasOwnProperty(layer) && mFilter.hasOwnProperty(filter)) {
                    var index = mLayer[layer].filters.indexOf(filter);
                    if (index != -1) {
                        mLayer[layer].filters.splice(index, 1);
                    }
                }
            }

            addFilterToLayer('buildings_layer', 'buildings_layer_structure_type');
            addFilterToLayer('buildings_tax_status_layer', 'buildings_tax_status_layer');
            addFilterToLayer('buildings_water_payment_status_layer', 'buildings_water_payment_status_layer');

            // HTML for Overlays checkboxes
            var html = '';
            // HTML for Export Overlays checkboxes
            var exportOverlayCheckboxesHTML = '';

            // HTML for Feature Info Overlay select options
            var featureInfoOverlayOptionsHTML = '';
            // HTML for Ward Filter Overlays select options
            var wardOverlayOptionsHTML = '';
            // HTML for Tax Zone Filter Overlays select options
            var taxZoneOverlayOptionsHTML = '';
            // HTML for Water Logging Area Filter Overlays select options
            var watlogOverlayOptionsHTML = '';
            // HTML for Export Overlays select options
            var exportOverlayOptionsHTML = '';

            // Looping through Overlays Object and creating layer object
            $.each(mLayer, function (key, value) {
                // creating layer object
                var layer = new ol.layer.Image({
                    visible: false,
                    source: new ol.source.ImageWMS({
                        url: gurl_wms,
                        params: {
                            'LAYERS': workspace + ':' + key,
                            'TILED': true,
                            'CQL_FILTER': 'deleted_at is NULL',
                        },
                        serverType: 'geoserver',
                        crossOrigin: 'anonymous'
                    })
                });

                // Setting name to layer
                layer.set('name', key);

                // Assigning layer to layer property of the current Overlay
                mLayer[key].layer = layer;

                // Adding layer to OpenLayers Map
                map.addLayer(layer);

                // Adding HTML for the current Overlay checkbox
                var checkboxHTML = '<div class="checkbox">';
                checkboxHTML += '<label>';
                checkboxHTML += '<input type="checkbox" name="' + key + '" value="' + key + '" />';
                checkboxHTML += value.name;
                checkboxHTML += '</label>';
                checkboxHTML += '</div>';

                // Adding checkbox HTML to Overlay checkboxes
                html += checkboxHTML;

                // Adding checkbox HTML to Export Overlay checkboxes
                if (['grids_layer', 'citypolys_layer', 'wards_layer'].indexOf(key) == -1) {
                    exportOverlayCheckboxesHTML += checkboxHTML;
                }

                // Adding HTML for the current Overlay style select container
                html += '<div id="' + key + '_overlay_style_select_container" style="display:none;padding-left:15px;">';
                var keys = Object.keys(value.styles);
                if (keys.length > 0) { // if the current Overlay has styles
                    html += '<select name="' + key + '">';
                    for (var i = 0; i < keys.length; i++) {
                        html += '<option value="' + keys[i] + '">' + value.styles[keys[i]].name + '</option>';
                    }
                    html += '</select>';
                }
                html += '</div>';

                // Adding HTML for the current Overlay legend container
                html += '<div id="' + key + '_overlay_legend_container" style="padding-left:15px;">';
                html += '</div>';

                // Adding HTML for the current Overlay option in Feature Info Overlay Select
                featureInfoOverlayOptionsHTML += '<option value="' + key + '">' + value.name + '</option>';

                // Adding HTML for the current Overlay option in Ward Filter Overlay Select
                if (['citypolys_layer', 'wards_layer', 'jhe_contour'].indexOf(key) == -1) {
                    if(key == 'roads_width_zoom_layer'){
                        key_new = 'roadlines_layer';
                    } else {
                        key_new = key;
                    }
                    wardOverlayOptionsHTML += '<option value="' + key_new + '">' + value.name + '</option>';
                }
                // Adding HTML for the current Overlay option in Tax Zone Filter Overlay Select
                if (['grids_layer', 'citypolys_layer', 'jhe_taxzone', 'jhe_contour', 'treatmentplants_layer', 'wards_layer'].indexOf(key) == -1) {
                    taxZoneOverlayOptionsHTML += '<option value="' + key + '">' + value.name + '</option>';
                }
                // Adding HTML for the current Overlay option in Water Logging Area Filter Overlay Select
                if (['citypolys_layer', 'waterbodys_layer', 'jhe_contour'].indexOf(key) == -1) {
                    watlogOverlayOptionsHTML += '<option value="' + key + '">' + value.name + '</option>';
                }

                // Adding HTML for the current Overlay option in Export Overlay Select
                if (['grids_layer', 'citypolys_layer', 'wards_layer'].indexOf(key) == -1) {
                    if(key == 'roads_width_zoom_layer'){
                        key_new = 'roadlines_layer';
                    } else {
                        key_new = key;
                    }
                    exportOverlayOptionsHTML += '<option value="' + key_new + '">' + value.name + '</option>';
                }
            });

            // Set Overlays checkboxes HTML to container
            $('#overlay_checkbox_container').html(html);

            // Set Export Overlays checkboxes HTML to container
            $('#export_overlay_checkbox_container').html(exportOverlayCheckboxesHTML);

            // Set Overlays options HTML to select boxes
            $('#feature_info_overlay').append(featureInfoOverlayOptionsHTML);
            $('#ward_overlay').append(wardOverlayOptionsHTML);
            $('#tax_zone_overlay').append(taxZoneOverlayOptionsHTML);
            $('#watlog_overlay').html(watlogOverlayOptionsHTML);
            $('#export_overlay').append(exportOverlayOptionsHTML);

            // Reload multiple select
            $('#watlog_overlay').multipleSelect('refresh');

            // Handler for Overlays checkbox change
            $('#overlay_checkbox_container').on('change', 'input[type=checkbox]', function () {
                // Get name attribute of the changed Overlay checkbox
                var key = $(this).attr('name');

                if ($(this).is(':checked')) { // if the Overlay checkbox is checked
                    // Make the Overlay layer visible in map
                    if (conditionalLayers.indexOf(key) == -1) {
                        mLayer[key].layer.setVisible(true);
                    } else {
                        mLayer[key].layer.setVisible(map.getView().getZoom() > 14);
                    }
                    // Show Ovelay style select container
                    $('#' + key + '_overlay_style_select_container').show();

                    if (Object.keys(mLayer[key].styles).length > 0) { // if the current Overlay has styles
                        // Trigger Overlay style select change
                        $('#overlay_checkbox_container select[name=' + key + ']').change();
                    } else { // if the current Overlay does not have styles
                        // Set legend image HTML for the Overlay
                        var html = '<img class="'+(mLayer[key].clipLegend ? 'clip-legend' : '')+'" src="' + gurl_legend + workspace + ':' + key + (mLayer[key].showCount ? '&LEGEND_OPTIONS=countMatched:TRUE;fontName:Lucida Bright;fontAntiAliasing:true;forceTitles:off;' : '') + '&STYLE=' + workspace + ':' + key + '" />';
                        // Set HTML to the Overlay container
                        $('#' + key + '_overlay_legend_container').html(html);}
                } else { // if the Overlay checkbox is unchecked
                    // Make the Overlay layer invisible in map
                    mLayer[key].layer.setVisible(false);
                    // Hide Ovelay style select container
                    $('#' + key + '_overlay_style_select_container').hide();
                    // Set empty HTML to the Overlay container
                    $('#' + key + '_overlay_legend_container').html('');
                }

                //Update Get information layer select dropdown
                var html = '<option value="">Select a layer</option>';

                $.each(mLayer, function (key, value) {
                    if (value.layer.getVisible()) {
                        html += '<option value="' + key + '">' + value.name + '</option>';
                    }
                });

                $('#feature_info_overlay').html(html);
                $('#feature_info_overlay').val('');
            });

            // Handler for Overlays style select change
            $('#overlay_checkbox_container').on('change', 'select', function () {

                // Get name attribute of the changed Overlay style select
                if(($(this).attr('name') == 'buildings_layer') && ($(this).val() == 'buildings_layer_water_payment_status'))
                {
                    var key = 'buildings_water_supply_payment_status';
                    var style = 'buildings_water_supply_payment_status';
                }

                // Get name attribute of the changed Overlay style select
                if(($(this).attr('name') == 'buildings_tax_status_layer'))
                {
                    var key = 'buildings_tax_status_layer';
                    var style = 'buildings_tax_status_layer';
                }

                // Get name attribute of the changed Overlay style select
                if(($(this).attr('name') == 'buildings_water_payment_status_layer'))
                {
                    var key = 'buildings_water_payment_status_layer';
                    var style = 'buildings_water_payment_status_layer';
                }

                var key = $(this).attr('name');

                // Get selected style
                var style = $(this).val();
                // Set selected style to parameters
                mLayer[key].layer.get('source').updateParams({STYLES: workspace + ':' + style});
                // Set legend image HTML for the Overlay with selected style
                    var html = '<img class="' +
                        (mLayer[key].styles[style].clipLegend ? 'clip-legend' : '')
                        + '" src="' + gurl_legend + workspace + ':' + key +
                        (mLayer[key].styles[style].showCount ?
                            '&LEGEND_OPTIONS=countMatched:TRUE;fontName:Lucida Bright;fontAntiAliasing:true;forceTitles:off;' +((style==='roadlines_layer_surface_type' )?'hideEmptyRules:on;':'')
                            : '')
                        + '&STYLE=' + workspace + ':' + style + '" />';

                $('#' + key + '_overlay_legend_container').html(html);
            });

            // Handler for
            $('#overlay_checkbox_container').on('change', 'select[name=buildings]', function () {

                // Get name attribute of the changed Overlay style select
                if ($(this).val() == 'buildings_layer_tax_status') {

                    mFilter.buildings_layer_tax_status = 'building_associated_to IS NULL';
                    updateAllCQLFiltersParams();
                    showLayer('buildings_layer');


                } else {
                    mFilter.buildings_layer_tax_status = '';
                    updateAllCQLFiltersParams();
                    showLayer('buildings_layer');
                }
                if ($(this).val() == 'buildings_layer_water_payment_status') {

                    mFilter.buildings_layer_water_payment_status = 'building_associated_to IS NULL';
                    updateAllCQLFiltersParams();
                    showLayer('buildings_layer');


                } else {
                    mFilter.buildings_layer_water_payment_status = '';
                    updateAllCQLFiltersParams();
                    showLayer('buildings_layer');
                }

            });
            // Check citypl, building and contain Overlay checkbox
            showLayer('buildings_layer');
            showLayer('wardboundary_layer');
            showLayer('citypolys_layer');
            showLayer('roads_width_zoom_layer');


            // Check Overlay checkbox
            function showLayer(layer) {
                var elem = $('#overlay_checkbox_container input[type=checkbox][name=' + layer + ']');
                if (!elem.is(':checked')) { // if the checkbox is not checked
                    // Trigger checkbox click event
                    elem.click();
                }
            }

            // Uncheck Overlay checkbox
            function hideLayer(layer) {
                var elem = $('#overlay_checkbox_container input[type=checkbox][name=' + layer + ']');
                if (elem.is(':checked')) { // if the checkbox is checked
                    // Trigger checkbox click event
                    elem.click();
                }
            }


            // Change visible of conditional layers based on zoom level
            map.getView().on('change:resolution', function () {
                $.each(conditionalLayers, function (key, value) {
                    var elem = $('#overlay_checkbox_container input[type=checkbox][name=' + value + ']');
                    if (elem.is(':checked')) { // if the checkbox is checked
                        mLayer[value].layer.setVisible(map.getView().getZoom() > 14);
                    }
                });
            });

            // Extra Overlays Object
            var eLayer = {};

            // Add extra overlay to Extra Overlays Object
            function addExtraLayer(key, name, layer) {
                // adding as property of Extra Overlays Object
                eLayer[key] = {name: name, layer: layer};

                // Adding layer to OpenLayers Map
                map.addLayer(layer);


            }
            function validateForm() {
                var ward = document.getElementById('ward');
                var ward_overlay = document.getElementById('ward_overlay');

                if (ward.value == '' || ward_overlay.value == '') {
                    alert('Please select a ward and an overlay.');
                    return false;
                }

                return true;
            }

            // Disable all controls
            function disableAllControls() {
                map.removeInteraction(draw);
                map.removeInteraction(drag);
                map.un('pointermove', pointerMoveHandler);
                map.un('pointermove', hoverOnLayerHandler);
                map.un('pointermove', hoverOnDrainHandler);
                map.un('pointermove', hoverOnWaterBodiesHandler);
                map.un('pointermove', hoverOnWardsBuildingHandler);
                map.un('pointermove', hoverOnRoadsHandler);
                map.un('pointermove', hoverOnContainmentHandler);
                map.un('pointermove', hoverOnBuildingContainmentHandler);
                map.un('pointermove', hoverOnRoadBuildingHandler);
                map.getViewport().removeEventListener('mouseout', mouseOutHandler);
                createMeasureTooltip();
                createHelpTooltip();
                map.un('singleclick', displayFeatureInformation);
                map.un('singleclick', findNearestRoad);
                map.un('singleclick', displayCoordinateInformation);
                map.un('singleclick', displayDrainBuildings);
                map.un('singleclick', displayDrainPotentialBuildings);
                map.un('singleclick', displayWaterBodiesBuildings);
                map.un('singleclick', displayContainmentToBuildings);
                map.un('singleclick', displayBuildingToContainment);
                map.un('singleclick', displayAssociatedToMainBuilding);
                map.un('singleclick', displayRoadBuildings);
                map.un('singleclick', displayRoadsBuildings);
                map.un('singleclick', displayPopupPointBuffer);
                map.un('singleclick', displayWardsBuildings);
                map.un('singleclick', displayBuildingsToPTCT);
                $('#add-road-inaccessible-box').hide();
                $('#add-waterbody-inaccessible-box').hide();
                $('#add-road-tool-box').hide();
                // map.removeInteraction(removeDrawnRoads);
//               resetAddRoadTool();
                // if ( $('#add-road-tool-box').hide()) {
                //     map.removeLayer(vectorLayer);
                //     map.removeLayer(drawLayer);
                //      map.removeInteraction(roadDrawInteraction);
                // }

                if (eLayer.measure) {
                    eLayer.measure.layer.getSource().clear();
                }
                if (eLayer.report_polygon) {
                    eLayer.report_polygon.layer.getSource().clear();
                }
                if (eLayer.export_polygon) {
                    eLayer.export_polygon.layer.getSource().clear();
                }
                if (eLayer.export_tax_polygon) {
                    eLayer.export_tax_polygon.layer.getSource().clear();
                }
                map.removeOverlay(staticMeasureTooltip);
                $('#layer-select-box').hide();
            }

            // Add handler to zoom in button click
            $('#zoomin_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                if (map.getView().getZoom() < map.getView().getMaxZoom()) {
                    map.getView().setZoom(map.getView().getZoom() + 1);
                }
            });

            // Add handler to zoom out button click
            $('#zoomout_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                if (map.getView().getZoom() > map.getView().getMinZoom()) {
                    map.getView().setZoom(map.getView().getZoom() - 1);
                }
            });

            // Add handler to zoom city button click
            $('#zoomfull_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                zoomToCity();
            });

            // Add handler to navigate button click
            $('#pan_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
            });

            // Add handler to info button click
            $('#identify_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                var html = '<option value="">Select a layer</option>';

                $.each(mLayer, function (key, value) {
                    if (value.layer.getVisible()) {
                        html += '<option value="' + key + '">' + value.name + '</option>';
                    }
                });

                $('#feature_info_overlay').html(html);
                $('#feature_info_overlay').val('');
                if (currentControl == 'identify_control') {
                    $('#layer-select-box').hide();
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    $('#layer-select-box').show();
                    currentControl = 'identify_control';
                    $('#identify_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnLayerHandler);
                    map.on('singleclick', displayFeatureInformation);
                }
            });

            // Add handler to info button click
            $('#coordinate_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'coordinate_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'coordinate_control';
                    $('#coordinate_control').addClass('map-control-active');
                    map.on('singleclick', displayCoordinateInformation);
                }
            });

            // Add handler to point buffer button click
            $('#pointbuffer_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'pointbuffer_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'pointbuffer_control';
                    $('#pointbuffer_control').addClass('map-control-active');
                    map.on('singleclick', displayPopupPointBuffer);
                }
            });

            // Add handler to Road Inaccessible button click
            $('#road_inaccessible_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                displayAjaxLoader();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'road_inaccessible_control') {
                    currentControl = '';
                    removeAjaxLoader();
                    $('#add-road-inaccessible-box').hide();
                } else {
                    currentControl = 'road_inaccessible_control';
                    $('#road_inaccessible_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnRoadsHandler);
                    removeAjaxLoader();
                    $('#add-road-inaccessible-box').show();
                }

            });

             $('#add_road_inaccessible_submit_btn').click(function(e){
                e.preventDefault();
                roadInaccessiblePopupOverlay.setPosition(undefined);
                if (eLayer.summary_road_inaccessible) {
                    eLayer.summary_road_inaccessible.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('summary_road_inaccessible', 'Summary Road Inaccessible', layer);
                }

                if (eLayer.road_inaccessible_buildings) {
                    eLayer.road_inaccessible_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('road_inaccessible_buildings', 'Road Inaccessbile Buildings', layer);
                }
                displayAjaxLoader();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    }
                });
                $.ajax({
                    url: '{{url('/maps/road-inaccessible-buildings')}}',
                    type: 'post',
                    data: {
                        'road_width' : $('#road_width').val(),
                        'hose_length' : $('#hose_length').val(),
                        'road_width_unit' : $('#road_width_unit').val(),
                        'hose_length_unit' : $('#hose_length_unit').val(),
                    },
                    success: function (Response) {
                        removeAjaxLoader();
                        map.getView().setCenter(ol.proj.transform([85.3617, 27.6470], 'EPSG:4326', 'EPSG:3857'));
                        map.getView().setZoom(14);
                        $('#add-road-inaccessible-errors').empty();
                        $('#road_width_report').val($('#road_width').val());
                        $('#road_width_unit_report').val($('#road_width_unit').val());
                        $('#road_hose_length_report').val($('#hose_length').val());
                        $('#road_hose_length_unit_report').val($('#hose_length_unit').val());
                        roadInaccessiblePopupContent.innerHTML = Response['popContentsHtml'];
                        roadInaccessiblePopupOverlay.setPosition(map.getView().getCenter());
                        $(roadInaccessiblePopupContainer).show();
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.road_inaccessible_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.summary_road_inaccessible.layer.getSource().addFeature(featureP);

                    },
                    error: function (data) {
                        $('#add-road-inaccessible-errors').empty();
                        let html = '<ul class="alert alert-danger">';
                        if (data.responseText) {
                            Object.values(JSON.parse(data.responseText).errors).forEach(function (error) {
                                html += "<li>" + error[0] + "</li>";
                            });
                        }else{
                            html+= "<li>Error</li>";
                        }
                        html+='</ul';
                        $('#add-road-inaccessible-errors').append(html);
                        $('#add-road-inaccessible-errors').focus();
                        removeAjaxLoader();
                    }
                });
            });

            // Add handler to Waterbody Inaccessible button click
            $('#waterbody_inaccessible_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                displayAjaxLoader();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'waterbody_inaccessible_control') {
                    currentControl = '';
                    removeAjaxLoader();
                    $('#add-waterbody-inaccessible-box').hide();
                } else {
                    currentControl = 'waterbody_inaccessible_control';
                    $('#waterbody_inaccessible_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnRoadsHandler);
                    removeAjaxLoader();
                    $('#add-waterbody-inaccessible-box').show();
                }

            });



            $('#add_waterbody_inaccessible_submit_btn').click(function(e){
                e.preventDefault();
                waterbodyInaccessiblePopupOverlay.setPosition(undefined);
                if (eLayer.summary_waterbody_inaccessible) {
                    eLayer.summary_waterbody_inaccessible.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('summary_waterbody_inaccessible', 'Summary Waterbody Inaccessible', layer);
                }

                if (eLayer.waterbody_inaccessible_buildings) {
                    eLayer.waterbody_inaccessible_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('waterbody_inaccessible_buildings', 'Waterbody Inaccessbile Buildings', layer);
                }
                displayAjaxLoader();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    }
                });
                $.ajax({
                    url: '{{url('/maps/waterbody-inaccessible-buildings')}}',
                    type: 'post',
                    data: {
                        'hose_length' : $('#waterbody_hose_length').val(),
                        'hose_length_unit' : $('#waterbody_hose_length_unit').val(),
                    },
                    success: function (Response) {
                        removeAjaxLoader();
                        showLayer('waterbodys_layer');
                        map.getView().setCenter(ol.proj.transform([85.3617, 27.6470], 'EPSG:4326', 'EPSG:3857'));
                        map.getView().setZoom(14);
                        $('#add-waterbody-inaccessible-errors').empty();
                        $('#waterbody_hose_length_report').val($('#waterbody_hose_length').val());
                        $('#waterbody_hose_length_unit_report').val($('#waterbody_hose_length_unit').val());
                        waterbodyInaccessiblePopupContent.innerHTML = Response['popContentsHtml'];
                        waterbodyInaccessiblePopupOverlay.setPosition(map.getView().getCenter());
                        $(waterbodyInaccessiblePopupContainer).show();
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.waterbody_inaccessible_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.summary_waterbody_inaccessible.layer.getSource().addFeature(featureP);

                    },
                    error: function (data) {
                        $('#add-waterbody-inaccessible-errors').empty();
                        let html = '<ul class="alert alert-danger">';
                        if (data.responseText) {
                            Object.values(JSON.parse(data.responseText).errors).forEach(function (error) {
                                html += "<li>" + error[0] + "</li>";
                            });
                        }else{
                            html+= "<li>Error</li>";
                        }
                        html+='</ul';
                        $('#add-waterbody-inaccessible-errors').append(html);
                        $('#add-waterbody-inaccessible-errors').focus();
                        removeAjaxLoader();
                    }
                });
            });
            // Add handler to length measure button click
            $('#linemeasure_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'linemeasure_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'linemeasure_control';
                    addMeasureControl('length');
                    $('#linemeasure_control').addClass('map-control-active');
                }
            });

            // Add handler to area measure button click
            $('#polymeasure_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'polymeasure_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'polymeasure_control';
                    addMeasureControl('area');
                    $('#polymeasure_control').addClass('map-control-active');
                }
            });


             //*Add road tool start*

            var currentAddRoadControl='',
                vectorSource,
                vectorLayer,
                drawSource,
                drawLayer,
                selectInteraction,
                roadDrawInteraction,
                roadSnapInteraction,
                roadDrawnSnapInteraction,
                undoInteraction,
                modifyInteraction,
                originalRoadFeature,
                modifiedRoadFeature,
                hasModification = false;
            $('#add_road_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                displayAjaxLoader();
                var allLayers = map.getLayers().getArray();
                $('.map-control').removeClass('map-control-active');
                if (currentControl === 'add_road_control') {
                    $('#add-road-tool-box').hide();
                    currentControl='';
                    resetAddRoadTool();
                    removeAjaxLoader();
                    disableAllControls();
                }else {
                    currentControl = 'add_road_control';
                    $('#add-road-tool-box').show();
                    vectorSource = new ol.source.Vector({
                    url: '<?php echo Config::get("constants.GEOSERVER_URL"); ?>/ows?service=WFS&' +
                            'version=1.1.0&request=GetFeature&typeName=<?php echo Config::get("constants.GEOSERVER_WORKSPACE"); ?>:roadlines_layer&&CQL_FILTER=deleted_at is null&' +
                            'SRS=EPSG:4326&outputFormat=json&authkey=9499949e-6318-4ffd-8384-ed94c5d84770',

                        format: new ol.format.GeoJSON(),
                    });
                    vectorLayer = new ol.layer.Vector({
                        background: '#1a2b39',
                        source: vectorSource,
                        name: 'add-roads-layer'
                    });
                    drawSource = new ol.source.Vector({format: new ol.format.GeoJSON()});
                    drawLayer = new ol.layer.Vector({
                        background: '#1a2b39',
                        source: drawSource,
                        name: 'add-roads-draw-layer'
                    });
                    if (!allLayers.includes('add-roads-layer')) {
                        map.addLayer(vectorLayer);
                    }else{
                        removeAjaxLoader();
                    }
                    if (!allLayers.includes('add-roads-draw-layer')) {
                        map.addLayer(drawLayer);
                    }
                    var sourceEventListener = vectorSource.on('change', function(e) {
                        if (vectorSource.getState() === 'ready') {
                            vectorSource.un('change', sourceEventListener);
                            removeAjaxLoader();
                        }
                    });
                }
            });

            // Add draw,snap & undo interactions.
            $('#add_road_start_control').click(function (e) {
                e.preventDefault();
                hideAddRoadForm();
                //   map.removeInteraction(roadDrawInteraction);
                if (currentAddRoadControl !== 'Add Road'){
                    currentAddRoadControl = 'Add Road';
                    addRoadDrawInteractions();
                }
            });

            //Undo the last drawn point.
            $('#add_road_undo_last_point_control').click(function (e) {
                e.preventDefault();
                hideAddRoadForm();
                roadDrawInteraction?.removeLastPoint();
            });

            //Undo the entire drawn line.
            $('#add_road_undo_control').click(function (e) {
                e.preventDefault();
                hideAddRoadForm();
                undoInteraction?.undo();
            });

            //Redo the drawing that was undoed
            $('#add_road_redo_control').click(function (e) {
                e.preventDefault();
                hideAddRoadForm();
                undoInteraction?.redo();
            });

            //Remove the drawn lines.
            $('#add_road_delete_control').click(function (e) {
                e.preventDefault();
                hideAddRoadForm();
                removeDrawnRoads();
            });

            //Modify the drawn roads
            $('#add_road_edit_control').click(function(e){
                hideAddRoadForm();
                if (currentAddRoadControl === 'Modify Road'){
                    currentAddRoadControl='';
                    removeAllAddRoadInteractions();
                }else {
                    if (currentAddRoadControl === 'Add Road') {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Roads added would be lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No!',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                removeAllAddRoadInteractions();
                                removeDrawnRoads();
                                currentAddRoadControl='Modify Road';
                                addRoadModifyInteractions();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                //do nothing
                            }
                        });
                    }else{
                        currentAddRoadControl='Modify Road';
                        addRoadModifyInteractions();
                    }
                }
            });

            //save the added and modified road
            $('#add_road_submit_control').click(function (e) {
                e.preventDefault();
                if (currentAddRoadControl === 'Add Road'){
                    let features = drawLayer.getSource().getFeatures();
                    if(features){
                        if(features.length < 1 ){
                            Swal.fire({
                                title: 'Error',
                                text : `Please draw a roadline before saving!`,
                                icon: "warning",
                            });
                        }else{
                            $('.add-road-form').slideToggle();
                        }
                    }
                } else if (currentAddRoadControl === 'Modify Road'){
                    //If we want to provide all fields update form
                    /*$('.add-road-form').slideDown();*/
                    //If we want to provide geom only update
                    hideAddRoadForm();
                    if (hasModification){
                        //If we want to provide all fields update form**********
                        /*displayAjaxLoader();
                        var road = modifiedRoadFeature.getProperties();
                        $('#roadnam').val(road.roadnam);
                        $('#roadhier').val(road.roadhier);
                        $('#rdsurf').val(road.rdsurf);
                        $('#rdlen').val(road.rdlen);
                        $('#rdwidth').val(road.rdwidth);
                        $('#rdcarwdth').val(road.rdcarwdth);

                        let lineFormat = new ol.format.WKT();
                        let geom = lineFormat.writeGeometry(modifiedRoadFeature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            }
                        });
                        $.ajax({
                            url: '{{url('/roadlines/update-road')}}', //May need new route and controller here
                            type: 'post',
                            data: {
                                'roadnam' : $('#roadnam').val(),
                                'roadhier' : $('#roadhier').val(),
                                'rdsurf' : $('#rdsurf').val(),
                                'rdlen' : $('#rdlen').val(),
                                'rdwidth' : $('#rdwidth').val(),
                                'rdcarwdth' : $('#rdcarwdth').val(),
                                "geom" : geom
                            },
                            success: function (data) {
                                $('#add-road-errors').empty();
                                $('.add-road-form').slideUp();
                                Swal.fire({
                                    title: 'Success!',
                                    text: "Road updated successfully.",
                                    icon: 'success',
                                    confirmButtonText: 'OK!',
                                });
                                resetAddRoadTool();
                                $('#add_road_control').trigger("click");
                                removeAjaxLoader();
                            },
                            error: function (data) {
                                $('#add-road-errors').empty();
                                let html = '<ul class="alert alert-danger">';
                                if (data.responseText) {
                                    Object.values(JSON.parse(data.responseText).errors).forEach(function (error) {
                                        html += "<li>" + error[0] + "</li>";
                                    });
                                }else{
                                    html+= "<li>Error</li>";
                                }
                                html+='</ul';
                                $('#add-road-errors').append(html);
                                $('#add-road-errors').focus();
                                removeAjaxLoader();
                            }
                        });*/
                        //All fields update End **********

                        //If we want to provide geom only update++++++++++

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "The changes made will be saved",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No!',
                            reverseButtons: true
                        }).then((result) => {
                            displayAjaxLoader();
                            if (result.isConfirmed) {
                                let lineFormat = new ol.format.WKT();
                                let geom = lineFormat.writeGeometry(modifiedRoadFeature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                        'Accept': 'application/json'
                                    }
                                });
                                $.ajax({
                                    url: '{{url('/utilityinfo/roadlines/update-road-geom')}}', //May need new route and controller here
                                    type: 'post',
                                    data: {
                                        'roadcd' : modifiedRoadFeature.getProperties().code,
                                        "geom" : geom
                                    },
                                    success: function (data) {
                                        /*$('#add-road-errors').empty();
                                        $('.add-road-form').slideUp();
                                        Swal.fire({
                                            title: 'Success!',
                                            text: "Road updated successfully.",
                                            icon: 'success',
                                            confirmButtonText: 'OK!',
                                        });
                                        resetAddRoadTool();
                                        $('#add_road_control').trigger("click");*/
                                        removeAjaxLoader();
                                        Swal.fire(
                                            'Saved!',
                                            'The changes have been saved.',
                                            'success',
                                        );
                                    },
                                    error: function (data) {
                                        /*$('#add-road-errors').empty();
                                        let html = '<ul class="alert alert-danger">';
                                        if (data.responseText) {
                                            Object.values(JSON.parse(data.responseText).errors).forEach(function (error) {
                                                html += "<li>" + error[0] + "</li>";
                                            });
                                        }else{
                                            html+= "<li>Error</li>";
                                        }
                                        html+='</ul';
                                        $('#add-road-errors').append(html);
                                        $('#add-road-errors').focus();*/
                                        removeAjaxLoader();
                                        Swal.fire(
                                            'Error',
                                            'There was an error!',
                                            'error'
                                        )
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                removeAjaxLoader();
                                Swal.fire(
                                    'Cancelled',
                                    'The changes have been removed.',
                                    'error'
                                )
                            }
                        });

                        //Geom only update end++++++++++

                    } else {
                        Swal.fire({
                            title: 'Nothing to save!',
                            icon: "warning",
                        });
                    }

                } else {
                    hideAddRoadForm();
                    Swal.fire({
                        title: 'Nothing to save!',
                        icon: "warning",
                    });
                }

            });

            //submit the details about road
            $('#add_road_submit_btn').click(function(e){
                e.preventDefault();
                displayAjaxLoader();
                let lineFormat = new ol.format.WKT();
                let geom = lineFormat.writeGeometry(drawSource.getFeatures()[drawSource.getFeatures().length-1].getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    }
                });
                $.ajax({
                    url: '{{url('/utilityinfo/roadlines/add-road')}}',
                    type: 'post',
                    data: {
                        'name' : $('#name').val(),
                        'hierarchy' : $('#hierarchy').val(),
                        'surface_type' : $('#surface_type').val(),
                        'length' : $('#length').val(),
                        'carrying_width' : $('#carrying_width').val(),
                        'right_of_way' : $('#right_of_way').val(),
                        "geom" : geom
                    },
                    success: function (data) {
                        $('#add-road-errors').empty();
                        $('.add-road-form').slideUp();
                        Swal.fire({
                            title: 'Success!',
                            text: "Road(s) added successfully.",
                            icon: 'success',
                            confirmButtonText: 'OK!',
                        });
                        resetAddRoadTool();
                        $('#add_road_control').trigger("click");
                        removeAjaxLoader();
                    },
                    error: function (data) {
                        $('#add-road-errors').empty();
                        let html = '<ul class="alert alert-danger">';
                        if (data.responseText) {
                            Object.values(JSON.parse(data.responseText).errors).forEach(function (error) {
                                html += "<li>" + error[0] + "</li>";
                            });
                        }else{
                            html+= "<li>Error</li>";
                        }
                        html+='</ul';
                        $('#add-road-errors').append(html);
                        $('#add-road-errors').focus();
                        removeAjaxLoader();
                    }
                });
            });

            function hideAddRoadForm(){
                $('.add-road-form').slideUp();
            }
            function addRoadDrawInteractions() {
                map.removeInteraction(modifyInteraction);
                map.removeInteraction(selectInteraction);
                roadDrawInteraction = new ol.interaction.Draw({
                    source: drawSource,
                    type: "MultiLineString",
                });
                roadSnapInteraction = new ol.interaction.Snap({source: vectorSource});
                roadDrawnSnapInteraction = new ol.interaction.Snap({source: drawSource});
                undoInteraction = new ol.interaction.UndoRedo();
                map.addInteraction(roadDrawInteraction);
                map.addInteraction(roadSnapInteraction);
                map.addInteraction(roadDrawnSnapInteraction);
                map.addInteraction(undoInteraction);
                roadDrawInteraction.on("drawstart",function(e){
                    hideAddRoadForm();
                    removeDrawnRoads();
                });
            }
            function addRoadModifyInteractions() {
                map.removeInteraction(roadDrawInteraction);
                selectInteraction = new ol.interaction.Select({
                    layers: [drawLayer,vectorLayer],
                    style: new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: [191, 17, 183, 1],
                            width: 5
                        })
                    })
                });
                modifyInteraction = new ol.interaction.ModifyFeature({
                    features: selectInteraction.getFeatures()
                });
                undoInteraction = new ol.interaction.UndoRedo();
                map.addInteraction(modifyInteraction);
                map.addInteraction(selectInteraction);
                roadSnapInteraction = new ol.interaction.Snap({source: vectorSource});
                roadDrawnSnapInteraction = new ol.interaction.Snap({source: drawSource});
                map.addInteraction(roadSnapInteraction);
                map.addInteraction(roadDrawnSnapInteraction);
                map.addInteraction(undoInteraction);

                selectInteraction.on('select',function (e) {
                    if (e.selected.length>0){
                        if (e.selected[0]!==e.deselected[0]){
                            if (!hasModification){
                                originalRoadFeature = e.selected[0];
                            } else{
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "Previous changes may be lost.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No!',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let undoCount = undoInteraction.getStack().length;
                                        for(let i=0;i<undoCount;i++) {
                                            undoInteraction.undo();
                                            if (i===undoCount-1){
                                                undoInteraction.clear();
                                            }
                                        }
                                        hasModification=false;
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        selectInteraction.getFeatures().clear();
                                        selectInteraction.getFeatures().push(modifiedRoadFeature);
                                    }
                                });
                            }
                        }
                    }
                })

                modifyInteraction.on('modifyend', function(e) {
                    hasModification = true;
                    modifiedRoadFeature = e.features[0];
                });
                undoInteraction.clear();
            }
            function removeDrawnRoads(){
                drawLayer.getSource().clear();
            }
            function removeAllAddRoadInteractions(){
                map.removeInteraction(roadDrawInteraction);
                map.removeInteraction(roadSnapInteraction);
                map.removeInteraction(roadDrawnSnapInteraction);
                map.removeInteraction(undoInteraction);
                map.removeInteraction(modifyInteraction);
                map.removeInteraction(selectInteraction);
            }
            function clearAddRoadlayers(){
                if (drawSource){
                    drawSource.clear();
                }
                map.removeLayer(vectorLayer);
                map.removeLayer(drawLayer);
            }
            function resetAddRoadTool(){
                currentAddRoadControl='';
                removeAllAddRoadInteractions();
                clearAddRoadlayers();
            }

            //*Add road tool end*
            // variable for storing draw interaction
            var draw;

            var wgs84Sphere = new ol.Sphere(6378137);

            /**
             * Currently drawn feature.
             * @type {ol.Feature}
             */
            var sketch;

            /**
             * The help tooltip element.
             * @type {Element}
             */
            var helpTooltipElement;

            /**
             * Overlay to show the help messages.
             * @type {ol.Overlay}
             */
            var helpTooltip;

            /**
             * The measure tooltip element.
             * @type {Element}
             */
            var measureTooltipElement;

            /**
             * Overlay to show the measurement.
             * @type {ol.Overlay}
             */
            var measureTooltip;

            /**
             * Overlay to show the static measurement.
             * @type {ol.Overlay}
             */
            var staticMeasureTooltip;

            /**
             * Message to show when the user is drawing a polygon.
             * @type {string}
             */
            var continuePolygonMsg = 'Click to continue drawing the polygon';

            /**
             * Message to show when the user is drawing a line.
             * @type {string}
             */
            var continueLineMsg = 'Click to continue drawing the line';

            /**
             * Handle pointer move.
             * @param {ol.MapBrowserEvent} evt The event.
             */
            var pointerMoveHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                /** @type {string} */
                var helpMsg = 'Click to start drawing';

                if (sketch) {
                    var geom = (sketch.getGeometry());
                    if (geom instanceof ol.geom.Polygon) {
                        helpMsg = continuePolygonMsg;
                    } else if (geom instanceof ol.geom.LineString) {
                        helpMsg = continueLineMsg;
                    }
                }

                helpTooltipElement.innerHTML = helpMsg;
                helpTooltip.setPosition(evt.coordinate);

                helpTooltipElement.classList.remove('hidden');
            };

            /**
             * Format length output.
             * @param {ol.geom.LineString} line The line.
             * @return {string} The formatted length.
             */
            var formatLength = function (line) {
                var length;

                var coordinates = line.getCoordinates();
                length = 0;
                var sourceProj = map.getView().getProjection();
                for (var i = 0, ii = coordinates.length - 1; i < ii; ++i) {
                    var c1 = ol.proj.transform(coordinates[i], sourceProj, 'EPSG:4326');
                    var c2 = ol.proj.transform(coordinates[i + 1], sourceProj, 'EPSG:4326');
                    length += wgs84Sphere.haversineDistance(c1, c2);
                }

                var output;
                if (length > 100) {
                    output = (Math.round(length / 1000 * 100) / 100) + ' ' + 'km';
                } else {
                    output = (Math.round(length * 100) / 100) + ' ' + 'm';
                }
                return output;
            };

            /**
             * Format area output.
             * @param {ol.geom.Polygon} polygon The polygon.
             * @return {string} Formatted area.
             */
            var formatArea = function (polygon) {
                var area;

                var sourceProj = map.getView().getProjection();
                var geom = /** @type {ol.geom.Polygon} */(polygon.clone().transform(
                    sourceProj, 'EPSG:4326'));
                var coordinates = geom.getLinearRing(0).getCoordinates();
                area = Math.abs(wgs84Sphere.geodesicArea(coordinates));

                var output;
                if (area > 10000) {
                    output = (Math.round(area / 1000000 * 100) / 100) + ' ' + 'km<sup>2</sup>';
                } else {
                    output = (Math.round(area * 100) / 100) + ' ' + 'm<sup>2</sup>';
                }
                return output;
            };

            var mouseOutHandler = function () {
                helpTooltipElement.classList.add('hidden');
            }

            // Add measure control to map
            function addMeasureControl(measureType) {
                var type = (measureType == 'area' ? 'Polygon' : 'LineString');

                map.on('pointermove', pointerMoveHandler);
                map.getViewport().addEventListener('mouseout', mouseOutHandler);
                if (helpTooltipElement) {
                    helpTooltipElement.classList.remove('hidden');
                }

                if (!eLayer.measure) {
                    var measureLayer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            fill: new ol.style.Fill({
                                color: 'rgba(255, 255, 255, 0.2)'
                            }),
                            stroke: new ol.style.Stroke({
                                color: '#ffcc33',
                                width: 2
                            }),
                            image: new ol.style.Circle({
                                radius: 7,
                                fill: new ol.style.Fill({
                                    color: '#ffcc33'
                                })
                            })
                        })
                    });

                    addExtraLayer('measure', 'Measure', measureLayer);
                }

                draw = new ol.interaction.Draw({
                    source: eLayer.measure.layer.getSource(),
                    type: /** @type {ol.geom.GeometryType} */ (type),
                    style: new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'rgba(255, 255, 255, 0.2)'
                        }),
                        stroke: new ol.style.Stroke({
                            color: 'rgba(0, 0, 0, 0.5)',
                            lineDash: [10, 10],
                            width: 2
                        }),
                        image: new ol.style.Circle({
                            radius: 5,
                            stroke: new ol.style.Stroke({
                                color: 'rgba(0, 0, 0, 0.7)'
                            }),
                            fill: new ol.style.Fill({
                                color: 'rgba(255, 255, 255, 0.2)'
                            })
                        })
                    })
                });
                map.addInteraction(draw);

                createMeasureTooltip();
                createHelpTooltip();

                var listener;
                draw.on('drawstart',
                    function (evt) {
                        eLayer.measure.layer.getSource().clear();
                        map.removeOverlay(staticMeasureTooltip);

                        // set sketch
                        sketch = evt.feature;

                        /** @type {ol.Coordinate|undefined} */
                        var tooltipCoord = evt.coordinate;

                        listener = sketch.getGeometry().on('change', function (evt) {
                            var geom = evt.target;
                            var output;
                            if (geom instanceof ol.geom.Polygon) {
                                output = formatArea(geom);
                                tooltipCoord = geom.getInteriorPoint().getCoordinates();
                            } else if (geom instanceof ol.geom.LineString) {
                                output = formatLength(geom);
                                tooltipCoord = geom.getLastCoordinate();
                            }
                            measureTooltipElement.innerHTML = output;
                            measureTooltip.setPosition(tooltipCoord);
                        });
                    }, this);

                draw.on('drawend',
                    function () {
                        // showExtraLayer('measure');
                        measureTooltipElement.className = 'tooltip tooltip-static';
                        measureTooltip.setOffset([0, -7]);
                        // copy reference of measureTooltip object to staticMeasureTooltip so that it can be referenced later
                        staticMeasureTooltip = measureTooltip;
                        // unset sketch
                        sketch = null;
                        // unset tooltip so that a new one can be created
                        measureTooltipElement = null;
                        createMeasureTooltip();
                        ol.Observable.unByKey(listener);
                    }, this);
            }

            /**
             * Creates a new help tooltip
             */
            function createHelpTooltip() {
                if (helpTooltipElement) {
                    helpTooltipElement.parentNode.removeChild(helpTooltipElement);
                }
                helpTooltipElement = document.createElement('div');
                helpTooltipElement.className = 'tooltip hidden';
                helpTooltip = new ol.Overlay({
                    element: helpTooltipElement,
                    offset: [15, 0],
                    positioning: 'center-left',
                    stopEvent: false
                });
                map.addOverlay(helpTooltip);
            }

            /**
             * Creates a new measure tooltip
             */
            function createMeasureTooltip() {
                if (measureTooltipElement) {
                    measureTooltipElement.parentNode.removeChild(measureTooltipElement);
                }
                measureTooltipElement = document.createElement('div');
                measureTooltipElement.className = 'tooltip tooltip-measure';
                measureTooltip = new ol.Overlay({
                    element: measureTooltipElement,
                    offset: [0, -15],
                    positioning: 'bottom-center',
                    stopEvent: false
                });
                map.addOverlay(measureTooltip);
            }

            // Add handler to print button click
            $('#print_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                // printMap();
                $('#print_title_text').val('');
                $('#print_comment_text').val('');
                if (!($('#print_modal .modal.in').length)) {
                    $('#print_modal .modal-dialog').css({
                      top: 90,
                      left: 20
                    });
                  }

                  $('#print_modal').modal({
                    backdrop: false,
                    show: true
                  });

//                  $('#print_modal .modal-dialog').draggable({
//                    handle: ".modal-header"
//                  });
                  //$('#print_modal').modal('show');
                  $('#print_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                let print_map_title = $("#print_map_title").val();
                let print_map_description = $("#print_map_description").val();
                let print_scale = 500;
                let print_paper_size = $("#print_paper_size").val();
                let print_dpi = $("#print_dpi").val();
                let orientation = $("#box_orientation").val();

                //**calculate coordinates of rectangular print box */
                var dimension = getHeightWidthPrintBox(print_scale, print_paper_size, print_dpi, orientation);
                var center = map.getView().getCenter();

                var x1 = ( center[0] - ( dimension['W'] / 2 ) );
                var y1 = ( center[1] - ( dimension['H'] / 2) );
                var x2 = ( center[0] + ( dimension['W'] / 2) )
                var y2 = ( center[1] - ( dimension['H'] / 2) );
                var x3 = ( center[0] + ( dimension['W'] / 2) )
                var y3 = ( center[1] + ( dimension['H'] / 2) );
                var x4 = ( center[0] - ( dimension['W'] / 2) )
                var y4 = ( center[1] + ( dimension['H'] / 2) );

                var ring = [
                                [x1,y1], [x2,y2],
                                [x3,y3], [x4,y4],
                                [x1,y1]
                           ];


                if (eLayer.print_box_layer) {
                    eLayer.print_box_layer.layer.getSource().clear();
                } else {
                   var layer = new ol.layer.Vector({
                                    // visible: false,
                                    source: new ol.source.Vector()
                                });
                    addExtraLayer('print_box_layer', 'Print Box', layer);
                }

                var feature = new ol.Feature({
                    geometry: new ol.geom.Polygon([ring])
                });

                var style = new ol.style.Style({
                            fill: new ol.style.Fill({
                                color: [0, 0, 255, 0.3]
                            }),
                            stroke: new ol.style.Stroke({
                                color: [255, 160, 25, 1],
                                width: 0.6,
                            }),
                             zIndex:999999,
                        });
                feature.setStyle(style);
                eLayer.print_box_layer.layer.getSource().addFeature(feature);
                var control = new ol.interaction.Translate({
                    features: new ol.Collection([feature])
                });
                map.addInteraction(control);
            });

            //Add handler to find coordinate click
            $('#getpointbycoordinates_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                // printMap();
                //$('#print_title_text').val('');
                //$('#print_comment_text').val('');
                $('#coordinate_search_modal').modal('show');
            });

            $('#print_modal').on('hidden.bs.modal', function () {
                if (eLayer.print_box_layer) {
                   eLayer.print_box_layer.layer.getSource().clear();
               }
               $("#print_map_title").val("");
               $("#print_map_description").val("");
               $("#print_scale").val("500");
               $("#print_paper_size").val("A4");
               $("#print_dpi").val("150");
               $("#box_orientation").val("landscape");
           });
            // Add handler to print form submit
            $("#print_map_fish").on("click", function() {


                var base_layer_select = $("#base_layer_select").val();
                //var projection1 = new ol.proj.Projection({ code: 'EPSG:4326', units: 'degrees', axisOrientation: 'neu' });
                //var baseLayer = new ol.layer.Tile({ projection : projection1, source: new ol.source.OSM(), isBaseLayer: true });

                if (base_layer_select == 'stamen') {
                    // Creating layer object
                    var baseLayer = new ol.layer.Tile({
                        isBaseLayer: true,
                        source: new ol.source.Stamen({
                            layer: toner
                        })
                    });
                } else if (base_layer_select == 'osm') {
                    // Creating layer object
                   var baseLayer = new ol.layer.Tile({
                        isBaseLayer: true,
                        source: new ol.source.OSM()
                    });
                } else if (base_layer_select == 'bing') {
                    // Creating layer object
                   var baseLayer = new ol.layer.Tile({
                        isBaseLayer: true,
                        source: new ol.source.BingMaps({
                            key: 'AlonC1vgjxPDct_RRLBn9Yyq41IXw5otDfbJRDfbNZQRblZC1Eum9BmE-zs499ED',
                            imagerySet: value.imagerySet
                        })
                    });
                }



        ///****mapfish print
                let print_map_title = $("#print_map_title").val();
                let print_map_description = $("#print_map_description").val();
                let print_scale = parseInt($("#print_scale").val());
                let print_paper_size = $("#print_paper_size").val();
                let box_orientation = $("#box_orientation").val();
                if(print_paper_size == "A3")
                {

                    var mapWidth = 1092;
                    var mapHeight = 674.31;
                    var mapHeader = 20;

                }
                else{
                    var mapWidth = 756.21;
                    var mapHeight = 436.8;
                    var mapHeader = 16;

                }

                let print_dpi = $("#print_dpi").val();
                let map_print_center = $('#map-print-polygon-center').val();
                if (!print_map_title) {
                    Swal.fire({
                        title: `Please provide a title!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                }


                let alllayers = [];
                $.each(mLayer, function (key, value) {
                    if (value.layer.getVisible()) {
                        if(key == "roads_width_zoom_layer"){
                            key = "roadlines_layer";
                        }
                        alllayers.push(key);
                    }
                });

                let allstyles = [];
                var multistylelayers = ["containments_layer", "buildings_layer", "wards_layer", "grids_layer", "roadlines_layer","toilets_layer", "sewerlines_layer", "dengue_wards_layer", "waterborne_hotspots_layer"];
                $.each(mLayer, function (key, value) {

                    if (value.layer.getVisible()) {
                        if (multistylelayers.indexOf(key) > -1) {
                            var style = $('#' + key + '_overlay_style_select_container').find(":selected").val();
                        }
                        else{
                            if(key == "roads_width_zoom_layer")
                            {
                                var style = "roadlines_layer_width";
                            }
                            else{
                            var style = key;
                        }
                        }
                        allstyles.push(style);
                    }
                });

                // debugger;

                var printBounds = eLayer.print_box_layer.layer.getSource().getExtent();
                var printBoundsTransform = ol.proj.transformExtent(printBounds,'EPSG:3857','EPSG:4326');
                var HIS = moment().format('LTS');
                var date = moment().format('L');
                var fulldate = moment().format('LLLL');
                var LAYOUT_NAME = print_paper_size + " Portrait";

                var json = {

                    mapWidth : mapWidth,
                    mapHeight : mapHeight,
                    mapHeader : mapHeader,

                    HIS : HIS,
                    date : date,
                    fulldate : fulldate,
                    mapTitle: print_map_title,
                    description: print_map_description,
                    //dpi: print_dpi,
                    //scale: print_scale,
                    layout: LAYOUT_NAME,
                    orientation: box_orientation,
                    srs: "EPSG:4326",
                    units: "degrees",
                    geodetic: false,
                    outputFilename: "malahaxmi_map",
                    outputFormat: "pdf",
                    pageSize: print_paper_size,
                    layers: [
                        {
                            type: "WMS",
                            layers: alllayers,
                            baseURL: gurl_wms + "?authkey=" + authkey,
                            format: "image/jpeg",
                            styles: allstyles,
                        }

                    ],
                    pages: [
                        {

                            bbox: printBoundsTransform,
                            mapTitle: print_map_title,
                            scale: print_scale,
                            dpi: print_dpi,
                            geodetic: false,
                            strictEpsg4326: false,
                        }
                    ]
                };

//                async function getFile(e) {
//                    e.preventDefault();
//                    await fetch("<?php echo Config::get("constants.GEOSERVER"); ?>/pdf/print.pdf?authkey=" + authkey, {
//                        method: "POST",
//                        body: JSON.stringify(json)
//                    })
//                        .then(function(resp) {
//                            return resp.blob();
//                        })
//                        .then(function(blob) {
//                            return download(blob, "malahaxmi_map.pdf");
//                        });
//                }
//                getFile(event);


                getDevices = async () => {
                            try {
                                const fetchResponse = await fetch(encodeURI(`<?php echo Config::get("constants.GEOSERVER"); ?>/pdf/print.pdf?authkey`+ authkey + `&spec=` + JSON.stringify(json), { mode: 'no-cors' }));
                                const blob = await fetchResponse.blob();
                                const link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = "IMIS-Map-" + moment().format('YYYYMMDD_HHmmss') + ".pdf";
                                link.click();
                                return e;
                            } catch (e) {
                                return e;
                            }

                        }
                        getDevices();

                $('#print_modal').modal('hide');

                ///****mapfish print

                //printMap(title, comment);

                return false;
            });

            // Add handler to print form submit
            $('#latlong_form').submit(function () {
                var lat = $('#point_latitude').val().trim();
                var long = $('#point_longitude').val().trim();
                //
                displayPointByCoordinates(lat, long);
                //printMap(title, comment);
                $('#coordinate_search_modal').modal('hide');
                return false;
            });

            function displayPointByCoordinates(lat, long) {
                if (eLayer.selected_pointcoordinate) {
                    eLayer.selected_pointcoordinate.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('selected_pointcoordinate', 'Selected Point Coordinate', layer);
                }

                // showExtraLayer('selected_pointcoordinate');

                var feature = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(long), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857'))
                });

                var style = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: '{{ url("/")}}/img/containment-green.png'
                    })
                });

                feature.setStyle(style);

                eLayer.selected_pointcoordinate.layer.getSource().addFeature(feature);

                map.getView().setCenter(ol.proj.transform([parseFloat(long), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857'));
            }

            function displayPointBufferByCoordinates(lat, long) {
                if (eLayer.selected_pointbuffercoordinate) {
                    eLayer.selected_pointbuffercoordinate.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('selected_pointbuffercoordinate', 'Selected Point Buffer Coordinate', layer);
                }

                // showExtraLayer('selected_pointbuffercoordinate');

                var feature = new ol.Feature({
                    geometry: new ol.geom.Point([parseFloat(long), parseFloat(lat)])
                });

                var style = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: '{{ url("/")}}/img/containment-green.png'
                    })
                });

                feature.setStyle(style);

                eLayer.selected_pointbuffercoordinate.layer.getSource().addFeature(feature);

            }

            // Add handler to remove markers button click
            $('#removemarkers_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                currentControl = '';
                // $('#pan_control').addClass('map-control-active');
                $.each(eLayer, function (key, value) {
                    value.layer.getSource().clear();
                });
                map.removeOverlay(staticMeasureTooltip);
            });

            function printMap(title, comment) {
                var mapWidth = $('#map').width();
                var mapHeight = $('#map').height();
                var body = $('body'),
                    mapContainer = $('div#map'),
                    mapContainerParent = mapContainer.parent(),

                    printContainer = $('<div>');

                body.prepend(printContainer);
                // printContainer.css('margin-left','-210px').append(mapContainer);
                printContainer.append(mapContainer);

                var content = body.children()
                    .not('script')
                    .not(printContainer)
                    .detach();

                var script_html = '<head>';
                script_html += '<style type="text/css">';
                script_html += 'body { margin: 0; }';
                script_html += ' ';
                script_html += '.print-table { height: 100%; }';
                script_html += ' ';
                script_html += '.print-logo, .print-title { display: inline-block; vertical-align: middle; }';
                script_html += ' ';
                script_html += '.print-logo { margin-right: 20px; }';
                script_html += ' ';
                script_html += '.print-title { font-size: 30px; font-weight: bold; color: #065d2c; }';
                script_html += ' ';
                script_html += '.print-date { margin-top: 20px; font-size: 20px; /*text-align: right;*/ }';
                script_html += ' ';
                script_html += '.print-map-title { margin-top: 40px; font-size: 24px; font-weight: bold; text-align: center; }';
                script_html += ' ';
                script_html += '.print-map-comment { margin-top: 20px; font-size: 20px; }';
                script_html += ' ';
                script_html += '@page { size: landscape; }';
                script_html += '@media print { .page-break { page-break-after: always; }  img { width: 100%; page-break-inside: avoid; } }';
                script_html += '</style>';
                script_html += '</head>';

                script_html += '<table class="print-table">';

                script_html += '<thead>';
                script_html += '<tr>';
                script_html += '<td>';

                script_html += '<div class="print-header">';
                script_html += '<div class="print-logo">';
                script_html += '<img src="{{ asset("/img/logo-imis.png") }}" />';
                script_html += '</div>';
                script_html += '<div class="print-title">';
                script_html += 'IMIS  Municipality';
                script_html += '</div>';
                script_html += '</div>';

                script_html += '<div class="print-date">';
                script_html += 'Date: ' + moment().format('YYYY-MM-DD');
                script_html += '</div>';

                if (title) {
                    script_html += '<div class="print-map-title">';
                    script_html += title;
                    script_html += '</div>';
                }

                if (comment) {
                    script_html += '<div class="print-map-comment">';
                    script_html += comment;
                    script_html += '</div>';
                }

                script_html += '</td>';
                script_html += '</tr>';
                script_html += '</thead>';

                script_html += '<tbody>';
                script_html += '<tr>';
                script_html += '<td>';
                script_html += printContainer.wrap('<div/>').parent().html();
                script_html += '\r\n</td>';
                script_html += '</tr>';
                script_html += '<tr>';
                script_html += '<td>';
                script_html += '</td>';
                script_html += '</tr>';
                script_html += '</tbody>';
                script_html += '</table>';
                script_html += '<div class="page-break"></div>';
                script_html += '\r\n<img class="print-legends" src="{{ asset("/img/map-legends/legends.PNG") }}">\r\n';
                script_html += '<div class="page-break"></div>';
                script_html += '\r\n<img class="print-legends" src="{{ asset("/img/map-legends/legends-3.PNG") }}">';
                $(this).unwrap('<div/>');


                var win = window.open();
                win.document.write(script_html);
                win.document.getElementById('map-right-sidebar').remove();

                win.document.getElementsByClassName('ol-overlaycontainer-stopevent')[0].remove();

                win.document.getElementById('map').style.position = 'relative';
                win.document.getElementById('map').style.width = mapWidth;
                win.document.getElementById('map').style.height = mapHeight;

                win.document.getElementsByTagName('canvas')[0].style.width = 'auto';
                win.document.getElementsByTagName('canvas')[0].style.height = 'auto';

                var oldCanvas = document.getElementsByTagName('canvas')[0];
                var newCanvas = win.document.getElementsByTagName('canvas')[0];
                var context = newCanvas.getContext('2d');

                context.drawImage(oldCanvas, 0, 0);

                //win.document.write('<img src="{{ asset("/pdf/legends-1.png") }}">\n');
                //win.document.write('<img src="{{ asset("/pdf/legends-2.png") }}">');

                if ((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1) {
                    alert('Opera');
                } else if (navigator.userAgent.indexOf("Chrome") != -1) {
                    setTimeout(function () {

                        win.document.close();
                        win.focus();
                        win.print();
                        win.close();


                    }, 2000);
                } else if (navigator.userAgent.indexOf("Safari") != -1) {
                    setTimeout(function () {

                        win.document.close();
                        win.focus();
                        win.print();
                        win.close();

                    }, 2000);
                } else if (navigator.userAgent.indexOf("Firefox") != -1) {
                    win.close();
                } else {
                    setTimeout(function () {

                        win.document.close();
                        win.focus();
                        win.print();
                        win.close();
                        //win.close();
                        //return true;
                    }, 6000);
                }


                body.prepend(content);
                mapContainerParent.prepend(mapContainer);
                printContainer.remove();


            }

            // Add handler to nearest road button click
            $('#nearestroad_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'nearestroad_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'nearestroad_control';
                    $('#nearestroad_control').addClass('map-control-active');
                    map.on('singleclick', findNearestRoad);
                }
            });

            // Add handler to due buildings button click
            $('#duebuildings_control').click(function (e) {
                if ($('#duebuildings_control').attr("class").split(/\s+/).includes("collapsed")) {
                    e.preventDefault();
                    disableAllControls();
                    $('.map-control').removeClass('map-control-active');
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                    displayDueBuildings();
                }
            });

            // Add handler to applications not reached treatment plant button click
            $('#applications_not_tp').click(function (e) {
                if ($('#applications_not_tp').attr("class").split(/\s+/).includes("collapsed")) {
                    e.preventDefault();
                    disableAllControls();
                    $('.map-control').removeClass('map-control-active');
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                    displayApplicationNotTP();
                }else{
                    if (eLayer.application_notTP_markers) {
                        eLayer.application_notTP_markers.layer.getSource().clear();
                    }
                    if (eLayer.application_NotTPDate_containment_markers) {
                        eLayer.application_NotTPDate_containment_markers.layer.getSource().clear();
                    }
                    if (eLayer.application_NotTP_containment_markers) {
                        eLayer.application_NotTP_containment_markers.layer.getSource().clear();
                    }
                    $('#application_not_tp_date_field').val('');
                    $('#application_not_tp_month').val('');
                    $('#applicaion_not_tp_year').val('');
                }
            });
            // Add handler to application containments button click
            $('#applicationcontainments_control').click(function (e) {
                if ($('#applicationcontainments_control').attr("class").split(/\s+/).includes("collapsed")) {
                    e.preventDefault();
                    disableAllControls();
                    $('.map-control').removeClass('map-control-active');
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                    displayApplicationContainments();
                }else{
                    e.preventDefault();
                    disableAllControls();
                    if (eLayer.application_containment_markers) {
                        eLayer.application_containment_markers.layer.getSource().clear();
                    }
                }
            });
            // Add handler to containments proposed to be emptied
            $('#containments_proposed_to_be_emptied').click(function(e) {
                    if (!$('#containments_proposed_to_be_emptied').attr("class").split(/\s+/).includes("collapsed")) {
                        if (eLayer.proposed_emptying_containments) {
                            eLayer.proposed_emptying_containments.layer.getSource().clear();
                        }
                    }
                });


            function getHeightWidthPrintBox(print_scale, print_paper_size, print_dpi, orientation){
                if(print_dpi == 300)
                {
                var print_dpi_x = 1 / 300; //inches per pixel.
                    print_dpi_x  = 1 / 300 * 25.4; //mm/inch
                    print_dpi_x  = 0.0846 ; //mm
                    print_dpi_x = 0.000085; //meters
                }

                if(print_dpi == 150)
                {
                var print_dpi_x = 1 / 150; //inches per pixel.
                    print_dpi_x  = 1 / 150 * 25.4; //mm/inch
                    print_dpi_x  = 0.17 ; //mm
                    print_dpi_x = 0.00017; //meters
                }

                if(print_dpi == 75)
                {
                var print_dpi_x = 1 / 75; //inches per pixel.
                    print_dpi_x  = 1 / 75 * 25.4; //mm/inch
                    print_dpi_x  = 0.34 ; //mm
                    print_dpi_x = 0.00034; //meters
                }

                if(print_paper_size == 'A4')
                {

                   var W=297;
                   var H=210;
                   var image_width_mm = W - ( 2 * 10 )  //277 mm
                   var image_height_mm = H - ( 2 * 10 ) - 30; //160 mm
                }

                if(print_paper_size == 'A3')
                {
                    var W=420;
                    var H=297;
                    var image_width_mm = W - ( 2 * 10 )  //400 mm
                    var image_height_mm = H - ( 2 * 10 ) - 30; //247 mm
                }

                var map_realwidth_m = ( print_scale * image_width_mm ) / 1000;
                var map_realheight_m = ( print_scale * image_height_mm )  / 1000;

                var dimension = new Array();
                 if(orientation == 'landscape'){
                 	dimension['W'] = map_realwidth_m;
                 	dimension['H'] = map_realheight_m;
                }
                else {

                    dimension['H'] = map_realwidth_m;
                    dimension['W'] = map_realheight_m;
                }

                return dimension;
            }


            /**
             * Elements that make up the popup for report.
             */
            var reportPopupContainer = document.getElementById('report-popup');
            var reportPopupContent = document.getElementById('report-popup-content');
            var reportPopupCloser = document.getElementById('report-popup-closer');


            /**
             * Create an overlay to anchor the popup to the map.
             */
            var reportPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: reportPopupContainer,
                autoPan: true,
                dragging: false,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(reportPopupContainer).show();

            map.addOverlay(reportPopupOverlay);


            /**
             * Add a click handler to hide the popup for report.
             * @return {boolean} Don't follow the href.
             */
            reportPopupCloser.onclick = function () {
                reportPopupOverlay.setPosition(undefined);
                reportPopupCloser.blur();
                return false;
            };

            /**
             * Elements that make up the popup for feedback.
             */
            var feedbackPopupContainer = document.getElementById('feedback-popup');
            var feedbackPopupContent = document.getElementById('feedback-popup-content');
            var feedbackPopupCloser = document.getElementById('feedback-popup-closer');


            /**
             * Create an overlay to anchor the popup to the map.
             */
            var feedbackPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: feedbackPopupContainer,
                autoPan: true,
                dragging: false,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(feedbackPopupContainer).show();

            map.addOverlay(feedbackPopupOverlay);


            /**
             * Add a click handler to hide the popup for feedback.
             * @return {boolean} Don't follow the href.
             */
            feedbackPopupCloser.onclick = function () {
                feedbackPopupOverlay.setPosition(undefined);
                feedbackPopupCloser.blur();
                return false;
            };


            /**
             * Elements that make up the popup for Drain Potential Buildings Summary.
             */
            var drainPotentialSummaryPopupContainer = document.getElementById('drain-potentialSummary-popup');
            var drainPotentialSummaryuPopupContent = document.getElementById('drain-potentialSummary-popup-content');
            var drainPotentialSummaryPopupCloser = document.getElementById('drain-potentialSummary-popup-closer');

            /**
             * Create an overlay to anchor the Drain Potential Buildings Summary popup to the map.
             */
            var drainPotentialSummaryPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: drainPotentialSummaryPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(drainPotentialSummaryPopupContainer).show();

            map.addOverlay(drainPotentialSummaryPopupOverlay);

            /**
             * Add a click handler to hide the  popup for drain potential summary.
             * @return {boolean} Don't follow the href.
             */
            @can('Decision Map Tools')
            drainPotentialSummaryPopupCloser.onclick = function () {
                drainPotentialSummaryPopupOverlay.setPosition(undefined);
                drainPotentialSummaryPopupCloser.blur();
                return false;
            };
            @endcan
            /**
             * Elements that make up the popup for water body.
             */
            var waterBodyPopupContainer = document.getElementById('water-body-popup');
            var waterBodyPopupContent = document.getElementById('water-body-popup-content');
            var waterBodyPopupCloser = document.getElementById('water-body-popup-closer');

            /**
             * Create an overlay to anchor the water body popup to the map.
             */
            var waterBodyPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: waterBodyPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(waterBodyPopupContainer).show();

            map.addOverlay(waterBodyPopupOverlay);

            /**
             * Add a click handler to hide the  popup for water body.
             * @return {boolean} Don't follow the href.
             */
            @can('Summary Information Map Tools')
            waterBodyPopupCloser.onclick = function () {
                waterBodyPopupOverlay.setPosition(undefined);
                waterBodyPopupCloser.blur();
                return false;
            };
            @endcan
            /**
             * Elements that make up the popup for ward buildings.
             */
            var wardBuildingsPopupContainer = document.getElementById('ward-buildings-popup');
            var wardBuildingsPopupContent = document.getElementById('ward-buildings-popup-content');
            var wardBuildingsPopupCloser = document.getElementById('ward-buildings-popup-closer');

            /**
             * Create an overlay to anchor the ward buildings popup to the map.
             */
            var wardBuildingsPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: wardBuildingsPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(wardBuildingsPopupContainer).show();

            map.addOverlay(wardBuildingsPopupOverlay);

            /**
             * Add a click handler to hide the  popup for ward buildings.
             * @return {boolean} Don't follow the href.
             */
            @can('Summary Information Map Tools')
            wardBuildingsPopupCloser.onclick = function () {
                wardBuildingsPopupOverlay.setPosition(undefined);
                wardBuildingsPopupCloser.blur();
                return false;
            };
            @endcan
            /**
             * Elements that make up the popup for buffer polygon.
             */
            var bufferPolygonPopupContainer = document.getElementById('buffer-polygon-popup');
            var bufferPolygonPopupContent = document.getElementById('buffer-polygon-popup-content');
            var bufferPolygonPopupCloser = document.getElementById('buffer-polygon-popup-closer');

            /**
             * Create an overlay to anchor the buffer polygon popup to the map.
             */
            var bufferPolygonPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: bufferPolygonPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(bufferPolygonPopupContainer).show();

            map.addOverlay(bufferPolygonPopupOverlay);

            /**
             * Add a click handler to hide the  popup for polygon buffer.
             * @return {boolean} Don't follow the href.
             */
            @can('Summary Information Map Tools')
            bufferPolygonPopupCloser.onclick = function () {
                bufferPolygonPopupOverlay.setPosition(undefined);
                bufferPolygonPopupCloser.blur();
                return false;
            };
            @endcan

            /**
             * Elements that make up the popup for road.
             */
            var roadPopupContainer = document.getElementById('road-popup');
            var roadPopupContent = document.getElementById('road-popup-content');
            var roadPopupCloser = document.getElementById('road-popup-closer');
            /**
             * Create an overlay to anchor the road building popup to the map.
             */
            var roadPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: roadPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(roadPopupContainer).show();

            map.addOverlay(roadPopupOverlay);


            /**
             * Add a click handler to hide the  popup for road.
             * @return {boolean} Don't follow the href.
             */
            @can('Summary Information Map Tools')
            roadPopupCloser.onclick = function () {
                roadPopupOverlay.setPosition(undefined);
                roadPopupCloser.blur();
                return false;
            };
            @endcan
            // Addded by Abhisan March 16
            var dragPan;
            map.getInteractions().forEach(function (interaction) {
                if (interaction instanceof ol.interaction.DragPan) {
                    dragPan = interaction;
                }
            });

            /*roadPopupContainer.addEventListener('mousedown', function (evt) {
                dragPan.setActive(false);
                roadPopupOverlay.set('dragging', true);

            });

            map.on('pointerdrag', function (evt) {
                if (roadPopupOverlay.get('dragging') === true) {
                    roadPopupOverlay.setPosition(evt.coordinate);

                }
            });*/

            //End addition by Abhisan
            /**
             * Elements that make up the popup for feature info.
             */
            var popupMarkerContainer = document.getElementById('popup-marker');
            var popupMarkerContent = document.getElementById('popup-marker-content');
            var popupMarkerCloser = document.getElementById('popup-marker-closer');


            /**
             * Create an overlay to anchor the popup to the map.
             */
            var popupMarkerOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: popupMarkerContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(popupMarkerContainer).show();

            map.addOverlay(popupMarkerOverlay);

            /**
             * Add a click handler to hide the popup.
             * @return {boolean} Don't follow the href.
             */
            popupMarkerCloser.onclick = function () {
                popupMarkerOverlay.setPosition(undefined);
                popupMarkerCloser.blur();
                return false;
            };
            /**
             * Elements that make up the popup for buildings to road.
             */
            var buildingsToRoadPopupContainer = document.getElementById('buildings-road-popup');
            var buildingsToRoadPopupContent = document.getElementById('buildings-road-popup-content');
            var buildingsToRoadPopupCloser = document.getElementById('buildings-road-popup-closer');

            /**
             * Create an overlay to anchor the buffer polygon popup to the map.
             */
            var buildingsToRoadPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: buildingsToRoadPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(buildingsToRoadPopupContainer).show();

            map.addOverlay(buildingsToRoadPopupOverlay);

            /**
             * Add a click handler to hide the  popup for polygon buffer.
             * @return {boolean} Don't follow the href.
             */
            @can('Export in Decision Map Tools')
            buildingsToRoadPopupCloser.onclick = function () {
                buildingsToRoadPopupOverlay.setPosition(undefined);
                buildingsToRoadPopupCloser.blur();
                return false;
            };
            @endcan
            // display popup on click
            map.on('click', function (evt) {
                var feature = map.forEachFeatureAtPixel(evt.pixel,
                    function (feature) {
                        return feature;
                    });
                if (feature && feature.get('application_id')) {
                    var coordinate = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var innerHTML = 'Application ID : ' + feature.get('application_id');
                    if (feature.get('house_number')) {
                        innerHTML += '<br>House Number : ' + feature.get('house_number');
                    }
                    if (feature.get('service_provider')) {
                        innerHTML += '<br>Service Provider : ' + feature.get('service_provider');
                    }
                    if (feature.get('application_date')) {
                        innerHTML += '<br>Application Date : ' + feature.get('application_date');
                    }
                    if (feature.get('emptying_date')) {
                        innerHTML += '<br>Emptying Date : ' + feature.get('emptying_date');
                    }
                    popupMarkerContent.innerHTML = innerHTML;
                    popupMarkerOverlay.setPosition(evt.coordinate);
                }
            });


            /**
             * Elements that make up the popup for point-buffer.
             */
            var pointBufferPopupContainer = document.getElementById('point-buffer-popup');
            var pointBufferPopupContent = document.getElementById('point-buffer-popup-content');
            var pointBufferPopupCloser = document.getElementById('point-buffer-popup-closer');

            /**
             * Create an overlay to anchor the pointBuffer building popup to the map.
             */
            var pointBufferPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: pointBufferPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(pointBufferPopupContainer).show();

            map.addOverlay(pointBufferPopupOverlay);

            /**
             * Add a click handler to hide the  popup for pointBuffer.
             * @return {boolean} Don't follow the href.
             */
            @can('Summary Information Map Tools')
            pointBufferPopupCloser.onclick = function () {
                pointBufferPopupOverlay.setPosition(undefined);
                pointBufferPopupCloser.blur();
                return false;
            };
            @endcan

            var drag;

            /**
             * @constructor
             */
            var Drag = function () {

                ol.interaction.Pointer.call(this, {
                    handleDownEvent: Drag.prototype.handleDownEvent,
                    handleDragEvent: Drag.prototype.handleDragEvent,
                    handleMoveEvent: Drag.prototype.handleMoveEvent,
                    handleUpEvent: Drag.prototype.handleUpEvent
                });

                /**
                 * @type {ol.Pixel}
                 * @private
                 */
                this.coordinate_ = null;

                /**
                 * @type {string|undefined}
                 * @private
                 */
                this.cursor_ = 'pointer';

                /**
                 * @type {ol.Feature}
                 * @private
                 */
                this.feature_ = null;

                /**
                 * @type {string|undefined}
                 * @private
                 */
                this.previousCursor_ = undefined;

                this.layer = '';

                this.dragged_ = false;

            };
            ol.inherits(Drag, ol.interaction.Pointer);


            /**
             * @param {ol.MapBrowserEvent} evt Map browser event.
             * @return {boolean} `true` to start the drag sequence.
             */
            Drag.prototype.handleDownEvent = function (evt) {
                var map = evt.map;
                var layer = this.layer;

                var feature = map.forEachFeatureAtPixel(evt.pixel,
                    function (feature) {
                        if (eLayer[layer] && eLayer[layer].layer.getSource().getFeatures().indexOf(feature) != -1) {
                            return feature;
                        }
                    });

                if (feature) {
                    this.coordinate_ = evt.coordinate;
                    this.feature_ = feature;
                }

                return !!feature;
            };


            /**
             * @param {ol.MapBrowserEvent} evt Map browser event.
             */
            Drag.prototype.handleDragEvent = function (evt) {
                if (this.layer == 'export_polygon') {
                    $('#export-popup-closer').click();
                }

                this.dragged_ = true;

                var deltaX = evt.coordinate[0] - this.coordinate_[0];
                var deltaY = evt.coordinate[1] - this.coordinate_[1];

                var geometry = /** @type {ol.geom.SimpleGeometry} */
                    (this.feature_.getGeometry());
                geometry.translate(deltaX, deltaY);

                this.coordinate_[0] = evt.coordinate[0];
                this.coordinate_[1] = evt.coordinate[1];
            };


            /**
             * @param {ol.MapBrowserEvent} evt Event.
             */
            Drag.prototype.handleMoveEvent = function (evt) {
                if (this.cursor_) {
                    var map = evt.map;
                    var layer = this.layer;

                    var feature = map.forEachFeatureAtPixel(evt.pixel,
                        function (feature) {
                            if (eLayer[layer] && eLayer[layer].layer.getSource().getFeatures().indexOf(feature) != -1) {
                                return feature;
                            }
                        });
                    var element = evt.map.getTargetElement();
                    if (feature) {
                        if (element.style.cursor != this.cursor_) {
                            this.previousCursor_ = element.style.cursor;
                            element.style.cursor = this.cursor_;
                        }
                    } else if (this.previousCursor_ !== undefined) {
                        element.style.cursor = this.previousCursor_;
                        this.previousCursor_ = undefined;
                    }
                }
            };


            /**
             * @return {boolean} `false` to stop the drag sequence.
             */
            Drag.prototype.handleUpEvent = function () {
                if (this.dragged_) {
                    if (this.layer == 'export_polygon') {
                        displayExportPopup(this.feature_.getGeometry());
                    }
                    this.dragged_ = false;
                }

                this.coordinate_ = null;
                this.feature_ = null;
                return false;
            };

            // Add handler to feedback information tool button click
            $('#feedback_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'feedback_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'feedback_control';
                    $('#feedback_control').addClass('map-control-active');

                    if (!eLayer.feedback_report_polygon) {
                        var reportPolygonLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });

                        addExtraLayer('feedback_report_polygon', 'Feedback Report Polygon', reportPolygonLayer);
                    }

                    // map.removeInteraction(draw);
                    draw = new ol.interaction.Draw({
                        source: eLayer.feedback_report_polygon.layer.getSource(),
                        type: 'Polygon'
                    });

                    draw.on('drawstart', function (evt) {
                        eLayer.feedback_report_polygon.layer.getSource().clear();
                        reportPopupOverlay.setPosition(undefined);
                    });
                    draw.on('drawend', function (evt) {
                        // showExtraLayer('feedback_report_polygon');
                        var format = new ol.format.WKT();
                        var geom = format.writeGeometry(evt.feature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                        $('#report-export-geom').val(geom);
                        displayAjaxLoader();
                        var url = '{{ url("maps/feedback-report") }}';
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {geom: geom, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                if (data['values'] != "") {
                                    var values = data['values'];
                                    var colors = data['colors'];
                                    var borderColor = data['borderColor'];
                                    var hoverBackgroundColor = data['hoverBackgroundColor'];
                                    var hoverBorderColor = data['hoverBorderColor'];
                                    var labels = data['labels'];
                                    var labels2 = data['labels2'];
                                    var values2 = data['values2'];
                                    var labels3 = data['labels3'];
                                    var values3 = data['values3'];
                                    var labels4 = data['labels4'];
                                    var values4 = data['values4'];
                                    var labels5 = data['labels5'];
                                    var values5 = data['values5'];
                                    var labels6 = data['labels6'];
                                    var values6 = data['values6'];
                                    var labels7 = data['labels7'];
                                    var values7 = data['values7'];
                                    var colors7 = data['colors7'];
                                    var uniqueContainCodeEmptiedCount = data['uniqueContainCodeEmptiedCount'];
                                    var feedbackCount = data['feedbackCount'];

                                    var html = '<div style="min-width: 400px;">';
                                                html += '<p class="h4 text-center">Feedbacks Chart</p>';
                                                html += '<div class="row"><div class="col-md-12"><ul class="list-group"><li class="list-group-item d-flex justify-content-between align-items-center">No. of containment emptied <span class="badge badge-primary badge-pill">'+uniqueContainCodeEmptiedCount+'</span></li><li class="list-group-item d-flex justify-content-between align-items-center">No. of feedbacks : <span class="badge badge-primary badge-pill">'+feedbackCount+'</span></li></ul></div></div>';
                                                html += '<div class="row">';
                                                html += '<div class="col-md-6"><canvas id="pie-chart" width="200" height="200"></canvas></div>';
                                                html += '<div class="col-md-6"><canvas id="pie-chart4" width="200" height="200"></canvas></div>';
                                                html += '</div>';
                                                html += '</div>';
                                                feedbackPopupContent.innerHTML = html;
                                    var chart = new Chart(document.getElementById("pie-chart"), {
                                        type: 'doughnut',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: "FSM quality",
                                                backgroundColor: colors,
                                                data: values
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: true,
                                                text: 'FSM Service Quality Satisfaction'
                                            }
                                        }
                                    });

                                    var chart = new Chart(document.getElementById("pie-chart4"), {
                                        type: 'doughnut',
                                        data: {
                                            labels: labels7,
                                            datasets: [{
                                                label: "Sanitation Workers Wearing PPE",
                                                backgroundColor: colors7,
                                                data: values7
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: true,
                                                text: 'Sanitation Workers Wearing PPE'
                                            }
                                        }
                                    });

                                } else {
                                    feedbackPopupContent.innerHTML = "No feedback data available.";
                                }
                                removeAjaxLoader();

                                feedbackPopupOverlay.setPosition(evt.feature.getGeometry().getInteriorPoint().getCoordinates());
                                $('#feedback_control').removeClass('map-control-active');
                                currentControl = '';
                                map.removeInteraction(draw);
                            },
                            error: function (data) {
                                displayAjaxError();
                            }
                        });
                    });

                    map.addInteraction(draw);
                }
            });

            // Add handler to report_control_summary_buffer information tool button click
            $('#report_control_summary_buffer').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'report_control_summary_buffer') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'report_control_summary_buffer';
                    $('#report_control_summary_buffer').addClass('map-control-active');

                    if (!eLayer.report_polygon_buffer) {
                        var reportPolygonBufferLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector(),
                            style: new ol.style.Style({
                                stroke: new ol.style.Stroke({
                                    color: '#0000FF',
                                    width: 3
                                }),
                            })
                        });


                        addExtraLayer('report_polygon_buffer', 'Report Polygon Buffer', reportPolygonBufferLayer);
                    }

                    // map.removeInteraction(draw);
                    draw = new ol.interaction.Draw({
                        source: eLayer.report_polygon_buffer.layer.getSource(),
                        type: 'Polygon'
                    });

                    draw.on('drawstart', function (evt) {
                        eLayer.report_polygon_buffer.layer.getSource().clear();
                        reportPopupOverlay.setPosition(undefined);
                    });
                    draw.on('drawend', function (evt) {
                        var format = new ol.format.WKT();
                        var geom = format.writeGeometry(evt.feature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                        $('#buffer-distance-polygon').val('');

                        $('#polygon-geom').val(geom);
                        $('#polygon-coordinates').val(evt.feature.getGeometry().getInteriorPoint().getCoordinates());
                        $('#report_control_summary_buffer').removeClass('map-control-active');
                        currentControl = "";
                        map.removeInteraction(draw);
                        $('#popup-polygon-buffer').modal('show');
                        $(bufferPolygonPopupContainer).hide();
                        bufferPolygonPopupOverlay.setPosition(evt.feature.getGeometry().getInteriorPoint().getCoordinates());
                    });

                    map.addInteraction(draw);
                }
            });
            //report_control_summary_buffer
            ///DEM Chart
            // Add handler to road building button click
            $('#dem_profile').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'dem_profile') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'dem_profile';
                    $('#dem_profile').addClass('map-control-active');
                    //map.on('pointermove', hoverOnPopulationsHandler);

                    var DEMChart = document.getElementById("DEMChart");
                    const mapDraw = document.getElementById("dem_profile");
                    let myChart;

                    draw = new ol.interaction.Draw({
                        type: "LineString",
                        maxPoints: 2
                    });
                    map.addInteraction(draw);
                    draw.on("drawend", function (e) {
                        var format = new ol.format.WKT();
                        var geom = format.writeGeometry(
                            e.feature
                                .getGeometry()
                                .clone()
                                .transform("EPSG:3857", "EPSG:4326")
                        );
                        displayAjaxLoader();
                        var url = '{{ url("get_DEM_line_geom") }}';
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {geom: geom, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                let elevation = new Array();
                                for (let i = 0; i < data.length; i++) {
                                    elevation[i] = data[i];
                                }

                                let labelForChart = new Array();
                                for (let i = 0; i < elevation.length; i++) {
                                    labelForChart[i] = i * 12; //pixel size
                                }
                                var speedData = {
                                    labels: labelForChart,
                                    datasets: [
                                        {
                                            label: "Elevation",
                                            data: elevation,
                                            borderColor: "#3e95cd"
                                        }
                                    ]
                                };
                                myChart = new Chart(DEMChart, {
                                    type: "line",
                                    data: speedData
                                });
                                $("#DEMModal").modal();
                                //map.removeInteraction(draw);
                                removeAjaxLoader();
                            }
                        });

                    });
                }
            });

            $("#DEMModal").on("hidden.bs.modal", function (event) {
                myChart.destroy();
            });


// Add handler to Population information tool button click
            $('#areapopulation_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'areapopulation_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'areapopulation_control';
                    $('#areapopulation_control').addClass('map-control-active');

                    if (!eLayer.areapopulation_polygon) {
                        var populationPolygonLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });

                        addExtraLayer('areapopulation_polygon', 'Population Polygon', populationPolygonLayer);
                    }

                    // map.removeInteraction(draw);
                    draw = new ol.interaction.Draw({
                        source: eLayer.areapopulation_polygon.layer.getSource(),
                        type: 'Polygon'
                    });

                    draw.on('drawstart', function (evt) {
                        eLayer.areapopulation_polygon.layer.getSource().clear();
                        populationPopupOverlay.setPosition(undefined);
                    });
                    draw.on('drawend', function (evt) {
                        // showExtraLayer('areapopulation_polygon');
                        var format = new ol.format.WKT();
                        var geom = format.writeGeometry(evt.feature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));

                        $('#population-export-geom').val(geom);
                        displayAjaxLoader();
                        var url = '{{ url("maps/area-population-Polygon-sum") }}';
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {geom: geom, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                removeAjaxLoader();
                                populationPopupContent.innerHTML = data;
                                populationPopupOverlay.setPosition(evt.feature.getGeometry().getInteriorPoint().getCoordinates());
                            },
                            error: function (data) {
                                displayAjaxError();
                            }
                        });
                        $('#areapopulation_control').removeClass('map-control-active');
                        currentControl = "";
                        map.removeInteraction(draw);
                    });

                    map.addInteraction(draw);
                }
            });
            // Handler for building structype checkbox change
            $('#building_structype_checkbox_container').on('change', 'input[type=checkbox]', function () {

                    var checkedList = [];
                    $('#building_structype_checkbox_container input[type=checkbox]:checked').each(function () {
                        checkedList.push("structure_type_id = '" + $(this).attr('name') + "'");
                    });

                    if (checkedList.length > 0) {
                        mFilter.buildings_layer_structure_type = '(' + checkedList.join(' OR ') + ')';
                    } else {
                        mFilter.buildings_layer_structure_type = '';
                    }
                    updateAllCQLFiltersParams();
                    showLayer('buildings_layer');
                    });


            // Handler for building tax payment checkbox change
            $('#building_tax_payment_checkbox_container').on('change', 'input[type=checkbox]', function () {

                var checkedList = [];
                $('#building_tax_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                    checkedList.push("due_year = '" + $(this).attr('name') + "'");
                });

                if (checkedList.length > 0) {
                    mFilter.buildings_tax_status_layer = '(' + checkedList.join(' OR ') + ')';
                } else {
                    mFilter.buildings_tax_status_layer = '';
                }
                updateAllCQLFiltersParams();
                showLayer('buildings_tax_status_layer');
            });

            // Handler for water supply payment checkbox change
            $('#water_supply_payment_checkbox_container').on('change', 'input[type=checkbox]', function () {
                var checkedList = [];
                $('#water_supply_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                     checkedList.push("due_year = '" + $(this).attr('name') + "'");
                });
                if (checkedList.length > 0) {
                    mFilter.buildings_water_payment_status_layer = '(' + checkedList.join(' OR ') + ')';
                } else {
                    mFilter.buildings_water_payment_status_layer = '';
                }

                updateAllCQLFiltersParams();
                showLayer('buildings_water_payment_status_layer');
            });
            $('#print_scale').on('change', function() {

              let orientation = $("#box_orientation").val();
              let print_scale = parseInt($("#print_scale").val());
              let print_dpi = $("#print_dpi").val();
              let print_paper_size = $("#print_paper_size").val();

              //**calculate coordinates of rectangular print box */
              var dimension = getHeightWidthPrintBox(print_scale, print_paper_size, print_dpi, orientation);
              var center = map.getView().getCenter();
              var x1 = ( center[0] - ( dimension['W'] / 2 ) );
              var y1 = ( center[1] - ( dimension['H'] / 2) );
              var x2 = ( center[0] + ( dimension['W'] / 2) )
              var y2 = ( center[1] - ( dimension['H'] / 2) );
              var x3 = ( center[0] + ( dimension['W'] / 2) )
              var y3 = ( center[1] + ( dimension['H'] / 2) );
              var x4 = ( center[0] - ( dimension['W'] / 2) )
              var y4 = ( center[1] + ( dimension['H'] / 2) );

              var ring = [
                              [x1,y1], [x2,y2],
                              [x3,y3], [x4,y4],
                              [x1,y1]
                         ];
              if (eLayer.print_box_layer) {

                  eLayer.print_box_layer.layer.getSource().clear();

              } else {

                 var layer = new ol.layer.Vector({
                                  // visible: false,
                                  source: new ol.source.Vector()
                              });

                  addExtraLayer('print_box_layer', 'Print Box', layer);
                  }
                  var feature = new ol.Feature({
                  geometry: new ol.geom.Polygon([ring])
              });

              var style = new ol.style.Style({
                          fill: new ol.style.Fill({
                              color: [0, 0, 255, 0.3]
                          }),
                          stroke: new ol.style.Stroke({
                              color: [255, 160, 25, 1],
                              width: 0.6,
                          })
                      });

              feature.setStyle(style);
              eLayer.print_box_layer.layer.getSource().addFeature(feature);
              var control = new ol.interaction.Translate({
                  features: new ol.Collection([feature])
              });
              map.addInteraction(control);
          });

          $('#box_orientation').on('change', function() {

            //***function to calculate H and W of pring box**//
              let orientation = $("#box_orientation").val();
              let print_scale = parseInt($("#print_scale").val());
              let print_dpi = $("#print_dpi").val();
              let print_paper_size = $("#print_paper_size").val();
              var dimension = getHeightWidthPrintBox(print_scale, print_paper_size, print_dpi, orientation);
              var center = map.getView().getCenter();
              var x1 = ( center[0] - ( dimension['W'] / 2 ) );
              var y1 = ( center[1] - ( dimension['H'] / 2) );
              var x2 = ( center[0] + ( dimension['W'] / 2) )
              var y2 = ( center[1] - ( dimension['H'] / 2) );
              var x3 = ( center[0] + ( dimension['W'] / 2) )
              var y3 = ( center[1] + ( dimension['H'] / 2) );
              var x4 = ( center[0] - ( dimension['W'] / 2) )
              var y4 = ( center[1] + ( dimension['H'] / 2) );

              var ring = [
                              [x1,y1], [x2,y2],
                              [x3,y3], [x4,y4],
                              [x1,y1]
                         ];
              if (eLayer.print_box_layer) {

                  eLayer.print_box_layer.layer.getSource().clear();

              } else {


                 var layer = new ol.layer.Vector({
                                  // visible: false,
                                  source: new ol.source.Vector()
                              });

                  addExtraLayer('print_box_layer', 'Print Box', layer);
                  }
                  var feature = new ol.Feature({
                  geometry: new ol.geom.Polygon([ring])
              });

              var style = new ol.style.Style({
                          fill: new ol.style.Fill({
                              color: [0, 0, 255, 0.3]
                          }),
                          stroke: new ol.style.Stroke({
                              color: [255, 160, 25, 1],
                              width: 0.6,
                          })
                      });

              feature.setStyle(style);
              eLayer.print_box_layer.layer.getSource().addFeature(feature);
              var control = new ol.interaction.Translate({
                  features: new ol.Collection([feature])
              });
              map.addInteraction(control);


          });

          $('#print_paper_size').on('change', function() {

            //***function to calculate H and W of pring box**//
              let orientation = $("#box_orientation").val();
              let print_scale = parseInt($("#print_scale").val());
              let print_dpi = $("#print_dpi").val();
              let print_paper_size = $("#print_paper_size").val();
              var dimension = getHeightWidthPrintBox(print_scale, print_paper_size, print_dpi, orientation);
              var center = map.getView().getCenter();
              var x1 = ( center[0] - ( dimension['W'] / 2 ) );
              var y1 = ( center[1] - ( dimension['H'] / 2) );
              var x2 = ( center[0] + ( dimension['W'] / 2) )
              var y2 = ( center[1] - ( dimension['H'] / 2) );
              var x3 = ( center[0] + ( dimension['W'] / 2) )
              var y3 = ( center[1] + ( dimension['H'] / 2) );
              var x4 = ( center[0] - ( dimension['W'] / 2) )
              var y4 = ( center[1] + ( dimension['H'] / 2) );

              var ring = [
                              [x1,y1], [x2,y2],
                              [x3,y3], [x4,y4],
                              [x1,y1]
                         ];
              if (eLayer.print_box_layer) {

                  eLayer.print_box_layer.layer.getSource().clear();

              } else {

                 var layer = new ol.layer.Vector({
                                  // visible: false,
                                  source: new ol.source.Vector()
                              });

                  addExtraLayer('print_box_layer', 'Print Box', layer);
                  }
                  var feature = new ol.Feature({
                  geometry: new ol.geom.Polygon([ring])
              });

              var style = new ol.style.Style({
                          fill: new ol.style.Fill({
                              color: [0, 0, 255, 0.3]
                          }),
                          stroke: new ol.style.Stroke({
                              color: [255, 160, 25, 1],
                              width: 0.6,
                          })
                      });

              feature.setStyle(style);
              eLayer.print_box_layer.layer.getSource().addFeature(feature);
              var control = new ol.interaction.Translate({
                  features: new ol.Collection([feature])
              });
              map.addInteraction(control);
          });

            /**
             * Elements that make up the popup for export.
             */
            var exportPopupContainer = document.getElementById('export-popup');
            var exportPopupContent = document.getElementById('export-popup-content');
            var exportPopupCloser = document.getElementById('export-popup-closer');

            /**
             * Create an overlay to anchor the popup to the map.
             */
            var exportPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: exportPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(exportPopupContainer).show();

            map.addOverlay(exportPopupOverlay);

            /**
             * Add a click handler to hide the popup for export.
             * @return {boolean} Don't follow the href.
             */
            exportPopupCloser.onclick = function () {
                exportPopupOverlay.setPosition(undefined);
                exportPopupCloser.blur();
                return false;
            };

            // Add handler to report information tool button click
            $('#export_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'export_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'export_control';
                    $('#export_control').addClass('map-control-active');
                     if (eLayer.export_polygon) {
                        eLayer.export_polygon.layer.getSource().clear();
                    } else {
                        var exportPolygonLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });

                        addExtraLayer('export_polygon', 'Export Polygon', exportPolygonLayer);
                    }

                    // map.removeInteraction(draw);
                    draw = new ol.interaction.Draw({
                        source: eLayer.export_polygon.layer.getSource(),
                        type: 'Polygon'
                    });

                    draw.on('drawstart', function (evt) {
                        eLayer.export_polygon.layer.getSource().clear();
                        exportPopupOverlay.setPosition(undefined);
                    });
                    draw.on('drawend', function (evt) {
                        displayExportPopup(evt.feature.getGeometry());
                        $('#export_control').removeClass('map-control-active');
                        currentControl = "";
                        map.removeInteraction(draw);
                    });

                    map.addInteraction(draw);
                    drag = new Drag();
                    drag.layer = 'export_polygon';
                    map.addInteraction(drag);
                }
            });

            /**
             * Elements that make up the popup for CSV export.
             */
            var exportCsvPopupContainer = document.getElementById('export-csv-popup');
            var exportCsvPopupContent = document.getElementById('export-csv-popup-content');
            var exportCsvPopupCloser = document.getElementById('export-csv-popup-closer');

            /**
             * Create an overlay to anchor the popup to the map.
             */
            var exportCsvPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: exportCsvPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(exportCsvPopupContainer).show();

            map.addOverlay(exportCsvPopupOverlay);

            /**
             * Add a click handler to hide the popup for export.
             * @return {boolean} Don't follow the href.
             */
            exportCsvPopupCloser.onclick = function () {
                exportCsvPopupOverlay.setPosition(undefined);
                exportCsvPopupCloser.blur();
                return false;
            };

            /**
             * Elements that make up the popup for population.
             */
            var populationPopupContainer = document.getElementById('population-popup');
            var populationPopupContent = document.getElementById('population-popup-content');
            var populationPopupCloser = document.getElementById('population-popup-closer');

            /**
             * Create an overlay to anchor the population popup to the map.
             */
            var populationPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: populationPopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(populationPopupContainer).show();

            map.addOverlay(populationPopupOverlay);
            /**
             * Add a click handler to hide the popup for population.
             * @return {boolean} Don't follow the href.
             */
            populationPopupCloser.onclick = function () {
                populationPopupOverlay.setPosition(undefined);
                populationPopupCloser.blur();
                return false;
            };
            /**
             * Elements that make up the popup for population.
             */
            var roadInaccessiblePopupContainer = document.getElementById('road-inaccessible-popup');
            var roadInaccessiblePopupContent = document.getElementById('road-inaccessible-popup-content');
            var roadInaccessiblePopupCloser = document.getElementById('road-inaccessible-popup-closer');
            /**
             * Create an overlay to anchor the road inaccessible popup to the map.
             */
            var roadInaccessiblePopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: roadInaccessiblePopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(roadInaccessiblePopupContainer).show();

            map.addOverlay(roadInaccessiblePopupOverlay);
            /**
             * Add a click handler to hide the popup for population.
             * @return {boolean} Don't follow the href.
             */
            roadInaccessiblePopupCloser.onclick = function () {
                roadInaccessiblePopupOverlay.setPosition(undefined);
                roadInaccessiblePopupCloser.blur();
                return false;
            };
            /**
             * Elements that make up the popup for waterbody inaccessible.
             */
            var waterbodyInaccessiblePopupContainer = document.getElementById('waterbody-inaccessible-popup');
            var waterbodyInaccessiblePopupContent = document.getElementById('waterbody-inaccessible-popup-content');
            var waterbodyInaccessiblePopupCloser = document.getElementById('waterbody-inaccessible-popup-closer');
            /**
             * Create an overlay to anchor the waterbody inaccessible popup to the map.
             */
            var waterbodyInaccessiblePopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: waterbodyInaccessiblePopupContainer,
                autoPan: true,
                stopEvent: false,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(waterbodyInaccessiblePopupContainer).show();

            map.addOverlay(waterbodyInaccessiblePopupOverlay);
            /**
             * Add a click handler to hide the popup for population.
             * @return {boolean} Don't follow the href.
             */
            waterbodyInaccessiblePopupCloser.onclick = function () {
                waterbodyInaccessiblePopupOverlay.setPosition(undefined);
                waterbodyInaccessiblePopupCloser.blur();
                return false;
            };
            // Add handler to export csv for buidins to update tax zones information tool button click
            $('#update_tax_zone').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'update_tax_zone') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'update_tax_zone';
                    $('#update_tax_zone').addClass('map-control-active');
                     if (eLayer.export_tax_polygon) {
                        eLayer.export_tax_polygon.layer.getSource().clear();
                    } else {
                        var exportPolygonLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });
                        addExtraLayer('export_tax_polygon', 'Export Polygon', exportPolygonLayer);
                    }

                    // map.removeInteraction(draw);
                    draw = new ol.interaction.Draw({
                        source: eLayer.export_tax_polygon.layer.getSource(),
                        type: 'Polygon'
                    });

                    draw.on('drawstart', function (evt) {
                        eLayer.export_tax_polygon.layer.getSource().clear();
                        exportPopupOverlay.setPosition(undefined);
                    });
                    draw.on('drawend', function (evt) {
                        var format = new ol.format.WKT();
                        var geom = format.writeGeometry(evt.feature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                        displayExportPopupTaxZone(evt.feature.getGeometry());
                        $('#building-with-owner-polygon-geom').val(geom);
                        currentControl = '';
                        $('#update_tax_zone').removeClass('map-control-active');
                        map.removeInteraction(draw);
                    });

                    map.addInteraction(draw);
                    drag = new Drag();
                    drag.layer = 'export_tax_polygon';
                    map.addInteraction(drag);
                }
            });

            // Add handler to drain building button click
            $('#drainbuildings_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'drainbuildings_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'drainbuildings_control';
                    $('#drainbuildings_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnDrainHandler);
                    map.on('singleclick', displayDrainBuildings);
                }
            });

            // Add handler to road building button click
            $('#roadbuildings_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'roadbuildings_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'roadbuildings_control';
                    $('#roadbuildings_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnRoadBuildingHandler);
                    map.on('singleclick', displayRoadBuildings);
                }
            });

            // Add handler to containment to building button click
            $('#containmentbuilding_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'containmentbuilding_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'containmentbuilding_control';
                    $('#containmentbuilding_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnContainmentHandler);
                    map.on('singleclick', displayContainmentToBuildings);

                }
            });

            // Add handler to building to containment button click
            $('#buildingcontainment_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'buildingcontainment_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'buildingcontainment_control';
                    $('#buildingcontainment_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnBuildingContainmentHandler);
                    map.on('singleclick', displayBuildingToContainment);
                }
            });

            // Add handler to building to containment button click
            $('#associatedtomain_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'associatedtomain_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'associatedtomain_control';
                    $('#associatedtomain_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnBuildingContainmentHandler);
                    map.on('singleclick', displayAssociatedToMainBuilding);
                }
            });

            // Add handler to drain potential building button click
            $('#drainpotential_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'drainpotential_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'drainpotential_control';
                    $('#drainpotential_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnDrainHandler);
                    map.on('singleclick', displayDrainPotentialBuildings);
                }
            });

            // Add handler to buildings waterbodies button click
            $('#buildingswaterbodies_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'buildingswaterbodies_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'buildingswaterbodies_control';
                    $('#buildingswaterbodies_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnWaterBodiesHandler);
                    map.on('singleclick', displayWaterBodiesBuildings);
                }
            });

            // Add handler to buildings roads button click
            $('#buildingsroads_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'buildingsroads_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'buildingsroads_control';
                    $('#buildingsroads_control').addClass('map-control-active');
                    map.on('pointermove', hoverOnRoadsHandler);
                    map.on('singleclick', displayRoadsBuildings);
                }
            });

            // Add handler to buildings ward tool button click
            $('#buildingswards_control').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'buildingswards_control') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'buildingswards_control';
                    $('#buildingswards_control').addClass('map-control-active');

                    map.on('pointermove', hoverOnWardsBuildingHandler);
                    map.on('singleclick', displayWardsBuildings);

                }
            });

            // Add handler to PTCT network button click
            $('#ptct_network').click(function (e) {
                e.preventDefault();
                disableAllControls();
                $('.map-control').removeClass('map-control-active');
                if (currentControl == 'ptct_network') {
                    currentControl = '';
                    // $('#pan_control').addClass('map-control-active');
                } else {
                    currentControl = 'ptct_network';
                    $('#ptct_network').addClass('map-control-active');
                    map.on('pointermove', hoverOnPTCTHandler);
                    map.on('singleclick', displayBuildingsToPTCT);
                }
            });

            function displayExportPopup(geometry) {
                $('#export_overlay').val('');

                $('#export-csv-btn, #export-kml-btn, #export-shape-btn').off('click');

                $('#export-csv-btn').on('click', function () {
                    openExportLink(geometry, 'CSV')
                });

                $('#export-kml-btn').on('click', function () {
                    openExportLink(geometry, 'KML')
                });

                $('#export-shape-btn').on('click', function () {
                    openExportLink(geometry, 'SHAPE-ZIP')
                });

                exportPopupOverlay.setPosition(geometry.getInteriorPoint().getCoordinates());
            }

            function displayExportPopupTaxZone(geometry) {

                $('#export-buildings-csv-btn').on('click', function () {
                    var geom = $('#building-with-owner-polygon-geom').val();
                    openBuildingsExportCsvLink(geom, 'CSV')
                });

                exportCsvPopupOverlay.setPosition(geometry.getInteriorPoint().getCoordinates());
            }


            function openExportLink(geometry, outputFormat) {
                var selectedLayer = $('#export_overlay').val();

                if (!selectedLayer) {
                    Swal.fire({
                        title: `Please select an overlay!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                var format = new ol.format.WKT();
                var geom = format.writeGeometry(geometry.clone().transform('EPSG:3857', 'EPSG:4326'));

                var exportLink = gurl_wfs + '?request=GetFeature&service=WFS&version=1.0.0&authkey=' + authkey + '&typeName=' + workspace + ':' + selectedLayer + '&CQL_FILTER=deleted_at is null AND WITHIN(geom, ' + geom + ')&outputFormat=' + outputFormat;

                if (outputFormat == 'SHAPE-ZIP') {
                    exportLink += '&format_options=filename:' + 'export_imis_' + moment().format('YYYYMMDD_HHmmss') + '.zip';
                }

                window.open(exportLink);
            }

            function openBuildingsExportCsvLink(geom, outputFormat) {

                var layers = [];
                var format = new ol.format.WKT();
                var exportLink = '{!! url("maps/export-buildings-taxzone") !!}/' + geom;

                window.open(exportLink);
            }

            var hoverOnDrainHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'sewerlines_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            var hoverOnRoadBuildingHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'roads_width_zoom_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            // Handler for hover events on containment layers
            var hoverOnContainmentHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'containments_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

             // Handler for hover events on Buildings layers
            var hoverOnBuildingContainmentHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'buildings_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            var hoverOnWaterBodiesHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'waterbodys_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };


            var hoverOnRoadsHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'roads_width_zoom_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            var hoverOnWardsBuildingHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'wards_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };


            var hoverOnPTCTHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function (layer) {
                    if (layer.get('name') == 'toilets_layer') {
                        return true;
                    }
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            // Display markers on buildings associated to drain
            function displayDrainBuildings(evt) {
                if (eLayer.selected_drains) {
                    eLayer.selected_drains.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_drains', 'Selected Roads', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.drain_buildings) {
                    eLayer.drain_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('drain_buildings', 'Drain Buildings', layer);
                }

                // showExtraLayer('drain_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':sewerlines_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                var drainCodes = [];
                                for (var i = 0; i < data.features.length; i++) {
                                    drainCodes.push(data.features[i].properties.code);
                                }

                                var url1 = '{{ url("maps/drain-buildings") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'post',
                                    data: {
                                        drain_codes: drainCodes,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function (data1) {
                                        eLayer.selected_drains.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                        if (data1 && Array.isArray(data1)) {
                                            var format = new ol.format.WKT();

                                            for (var i = 0; i < data1.length; i++) {
                                                var feature = format.readFeature(data1[i].geom, {
                                                    dataProjection: 'EPSG:4326',
                                                    featureProjection: 'EPSG:3857'
                                                });

                                                eLayer.drain_buildings.layer.getSource().addFeature(feature);
                                            }
                                        }

                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('Sewer Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                        $('#drainbuildings_control').removeClass('map-control-active');
                        currentControl = "";
                        map.un('pointermove', hoverOnDrainHandler);
                        map.un('singleclick', displayDrainBuildings);
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }


            // Display markers on buildings associated to road
            function displayRoadBuildings(evt) {
                if (eLayer.selected_road_buildings) {
                    eLayer.selected_road_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#ffff00',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_road_buildings', 'Selected Roads', layer);
                }

                // showExtraLayer('selected_road_buildings');

                if (eLayer.road_to_buildings) {
                    eLayer.road_to_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#ffff00',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('road_to_buildings', 'Road Buildings', layer);
                }


                // showExtraLayer('road_to_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':roadlines_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();

                $.ajax({
                    url: url,
                    type: 'get',

                    success: function (response) {
                        if (response && response.features && Array.isArray(response.features)) {
                            if (response.features.length > 0) {
                                var roadCodes = [];
                                for (var i = 0; i < response.features.length; i++) {
                                    roadCodes.push(response.features[i].properties.code);
                                }
                                $('#road_codes').val(roadCodes);
                                var url1 = '{{ url("maps/buildings-to-road") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'post',
                                    data: {
                                        road_codes: roadCodes,
                                        "_token": "{{ csrf_token() }}",
                                    },
                                    success: function (Response) {
                                        eLayer.selected_road_buildings.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(response));
                                        buildingsToRoadPopupContent.innerHTML = Response['popContentsHtml'];
                                        buildingsToRoadPopupOverlay.setPosition(evt.coordinate);
                                        $(buildingsToRoadPopupContainer).show();

                                        data1 = Response['buildings'];

                                        if (data1 && Array.isArray(data1)) {
                                            var format = new ol.format.WKT();

                                            for (var i = 0; i < data1.length; i++) {
                                                var feature = format.readFeature(data1[i].geom, {
                                                    dataProjection: 'EPSG:4326',
                                                    featureProjection: 'EPSG:3857'
                                                });

                                                eLayer.road_to_buildings.layer.getSource().addFeature(feature);
                                            }
                                        }


                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('Road Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                        $('#roadbuildings_control').removeClass('map-control-active');
                        currentControl = "";
                        map.un('pointermove', hoverOnRoadBuildingHandler);
                        map.un('singleclick', displayRoadBuildings);
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on buildings associated to containment
            function displayContainmentToBuildings(evt) {
                if (eLayer.selected_containment) {
                    eLayer.selected_containment.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            image: new ol.style.Icon({
                                anchor: [0.5, 1],
                                src: '{{ url("/")}}/img/containment.png'
                            })
                        })
                    });

                    addExtraLayer('selected_containment', 'Selected Containment', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.containment_buildings) {
                    eLayer.containment_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('containment_buildings', 'Containment Buildings', layer);
                }

                // showExtraLayer('containment_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':containments_layer',
                        'FEATURE_COUNT': 1
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }
                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                         var dataWms = data;
                        if (dataWms && dataWms.features && Array.isArray(dataWms.features)) {
                            if (dataWms.features.length > 0) {
                                var url1 = '{{ url("maps/containment-buildings") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'get',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        containmentId: dataWms.features[0].properties.id
                                    },
                                    success: function (data1) {

                                        eLayer.selected_containment.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(dataWms));


                                        if (data1 && Array.isArray(data1)) {
                                            var format = new ol.format.WKT();

                                            for (var i = 0; i < data1.length; i++) {
                                                var feature = format.readFeature(data1[i].geom, {
                                                    dataProjection: 'EPSG:4326',
                                                    featureProjection: 'EPSG:3857'
                                                });

                                                eLayer.containment_buildings.layer.getSource().addFeature(feature);
                                            }
                                        }

                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('Containment Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                    },
                    error: function (dataWms) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on containment associated to building
            function displayBuildingToContainment(evt) {
                if (eLayer.selected_building) {
                    eLayer.selected_building.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#ff0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_building', 'Selected Building', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.building_containments) {
                    eLayer.building_containments.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('building_containments', 'Buildingc Containments', layer);
                }

                // showExtraLayer('containment_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':buildings_layer',
                        'FEATURE_COUNT': 1
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                var url1 = '{{ url("maps/building-containment") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'get',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        bin: data.features[0].properties.bin
                                    },
                                    success: function (data1) {
                                        eLayer.selected_building.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                        for (var i = 0; i < data1.length; i++) {
                                            if (data1[i].lat && data1[i].long) {
                                                if (!eLayer.markers) {
                                                    var markerLayer = new ol.layer.Vector({
                                                        // visible: false,
                                                        source: new ol.source.Vector()
                                                    });

                                                    addExtraLayer('markers', 'Markers', markerLayer);
                                                    // showExtraLayer('markers');
                                                }

                                                var feature = new ol.Feature({
                                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data1[i].long), parseFloat(data1[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                                });

                                                var style = new ol.style.Style({
                                                    image: new ol.style.Icon({
                                                        anchor: [0.5, 1],
                                                        src: '{{ url("/")}}/img/containment-green.png'
                                                    })
                                                });

                                                feature.setStyle(style);

                                                eLayer.building_containments.layer.getSource().addFeature(feature);
                                            }
                                        }

                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('Building Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on containment associated to building
            function displayAssociatedToMainBuilding(evt) {
                if (eLayer.selected_building) {
                    eLayer.selected_building.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#ff0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_building', 'Selected Building', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.associated_to_main_building) {
                    eLayer.associated_to_main_building.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('associated_to_main_building', 'Associated to Main Buildings', layer);
                }

                // showExtraLayer('containment_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':buildings_layer',
                        'FEATURE_COUNT': 1
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                var url1 = '{{ url("maps/getassociated-mainbuilding") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'get',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        bin: data.features[0].properties.bin
                                    },
                                    success: function (data1) {
                                        eLayer.selected_building.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                        for (var i = 0; i < data1.length; i++) {
                                            if (data1[i].lat && data1[i].long) {
                                                if (!eLayer.markers) {
                                                    var markerLayer = new ol.layer.Vector({
                                                        // visible: false,
                                                        source: new ol.source.Vector()
                                                    });

                                                    addExtraLayer('markers', 'Markers', markerLayer);
                                                    // showExtraLayer('markers');
                                                }

                                                var feature = new ol.Feature({
                                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data1[i].long), parseFloat(data1[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                                });

                                                var style = new ol.style.Style({
                                                    image: new ol.style.Icon({
                                                        anchor: [0.5, 1],
                                                        src: '{{ url("/") }}/img/building-purple.png'
                                                    })
                                                });

                                                feature.setStyle(style);

                                                eLayer.associated_to_main_building.layer.getSource().addFeature(feature);
                                            }
                                        }

                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('Main Building Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }
            function displayDrainPotentialBuildings(evt) {
                if (eLayer.selected_drains) {
                    eLayer.selected_drains.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_drains', 'Selected Sewersline', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.drain_buildings) {
                    eLayer.drain_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('drain_buildings', 'Sewersline Buildings', layer);
                }

                // showExtraLayer('drain_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':sewerlines_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                            "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        if (response && response.features && Array.isArray(response.features)) {
                            if (response.features.length > 0) {
                                if (response.features.length == 1) {
                                    eLayer.selected_drains.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(response));

                                    var drainCode = response.features[0].properties.code;
                                    $('#drain-code').val(drainCode);
                                    $('#drain-long').val(evt.coordinate[0]);
                                    $('#drain-lat').val(evt.coordinate[1]);
                                    $('#buffer-distance').val('');
                                    removeAjaxLoader();
                                    $('#popup-drain-potential').modal('show');
                                } else {
                                    displayAjaxErrorModal('More than One Sewer lines Found, Please Zoom In or Select another Sewer line');
                                }
                            } else {
                                displayAjaxErrorModal('Sewer Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                        $('#drainpotential_control').removeClass('map-control-active');
                        currentControl = "";
                        map.un('pointermove', hoverOnDrainHandler);
                        map.un('singleclick', displayDrainPotentialBuildings);
                    },
                    error: function (response) {
                        displayAjaxError();
                    }
                })


                //displayAjaxLoader();
            }

            function displayWaterBodiesBuildings(evt) {
                if (eLayer.selected_waterbodies) {
                    eLayer.selected_waterbodies.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_waterbodies', 'Selected Waterbodies', layer);
                }

                // showExtraLayer('selected_waterbodies');

                if (eLayer.waterbodies_buildings) {
                    eLayer.waterbodies_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('waterbodies_buildings', 'Water Bodies Buildings', layer);
                }

                // showExtraLayer('waterbodies_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':waterbodys_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                if (data.features.length == 1) {
                                    eLayer.selected_waterbodies.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                    var waterBodyCode = data.features[0].properties.id;
                                    $('#water-body-code').val(waterBodyCode);
                                    $('#water-body-long').val(evt.coordinate[0]);
                                    $('#water-body-lat').val(evt.coordinate[1]);
                                    $('#buffer-distance-waterbodies').val('');
                                    removeAjaxLoader();
                                    $('#popup-waterbodies-buildings').modal('show');
                                } else {
                                    displayAjaxErrorModal('More than One water bodies Found, Please Zoom In or Select another Water Body');
                                }
                            } else {
                                displayAjaxErrorModal('Water Body Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }

                        $('#buildingswaterbodies_control').removeClass('map-control-active');
                        currentControl = "";
                        map.un('pointermove', hoverOnWaterBodiesHandler);
                        map.un('singleclick', displayWaterBodiesBuildings);
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                })


                //displayAjaxLoader();
            }

            function displayRoadsBuildings(evt) {
                if (eLayer.selected_roads) {
                    eLayer.selected_roads.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_roads', 'Selected Roads', layer);
                }

                // showExtraLayer('selected_roads');

                if (eLayer.roads_buildings) {
                    eLayer.roads_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('roads_buildings', 'Roads Buildings', layer);
                }

                // showExtraLayer('roads_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':roadlines_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                if (data.features.length == 1) {
                                    eLayer.selected_roads.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                    var roadCode = data.features[0].properties.code;
                                    $('#road-code').val(roadCode);
                                    $('#road-long').val(evt.coordinate[0]);
                                    $('#road-lat').val(evt.coordinate[1]);
                                    $('#buffer-distance-road').val('');
                                    removeAjaxLoader();
                                    $('#popup-road-buildings').modal('show');
                                } else {
                                    displayAjaxErrorModal('More than One Roads Found, Please Zoom In or Select another Road');
                                }
                            } else {
                                displayAjaxErrorModal('Road Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                        $('#buildingsroads_control').removeClass('map-control-active');
                        map.un('pointermove', hoverOnRoadsHandler);
                        map.un('singleclick', displayRoadsBuildings);
                        currentControl = "";
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                })


                //displayAjaxLoader();
            }


            function displayBuildingsToPTCT(evt) {

                if (eLayer.selected_ptct) {
                    eLayer.selected_ptct.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            image: new ol.style.Icon({
                                anchor: [0.5, 1],
                                src: '{{ url("/")}}/img/containment-green.png'
                            })
                        })
                    });

                    addExtraLayer('selected_ptct', 'Selected PTCT', layer);
                }

                // showExtraLayer('selected_drains');

                if (eLayer.buildings_ptct) {
                    eLayer.buildings_ptct.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#A9A9A9',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('buildings_ptct', 'PTCT Buildings', layer);
                }

                // showExtraLayer('containment_buildings');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':toilets_layer',
                        'FEATURE_COUNT': 1
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                var url1 = '{{ url("maps/buildings-toilet-network") }}';
                                $.ajax({
                                    url: url1,
                                    type: 'get',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        bin: data.features[0].properties.bin
                                    },
                                    success: function (data1) {

                                        eLayer.selected_ptct.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(data));

                                        if (data1 && Array.isArray(data1)) {
                                            var format = new ol.format.WKT();

                                            for (var i = 0; i < data1.length; i++) {

                                                var feature = format.readFeature(data1[i].geom, {
                                                    dataProjection: 'EPSG:4326',
                                                    featureProjection: 'EPSG:3857'
                                                });

                                                eLayer.buildings_ptct.layer.getSource().addFeature(feature);
                                            }
                                        } else {
                                            displayAjaxErrorModal('Buildings not found');
                                        }

                                        removeAjaxLoader();
                                    },
                                    error: function (data1) {
                                        displayAjaxError();
                                    }
                                });
                            } else {
                                displayAjaxErrorModal('PTCT not found');
                            }
                        } else {
                            displayAjaxError();
                        }
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });

            }

            function displayWardsBuildings(evt) {
                if (eLayer.selected_ward) {
                    eLayer.selected_ward.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('selected_ward', 'Selected Ward', layer);
                }

                // showExtraLayer('selected_ward');

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: {
                        'LAYERS': workspace + ':wards_layer',
                        'FEATURE_COUNT': 10
                    }
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    evt.coordinate, viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'get',

                    success: function (response) {
                        if (response && response.features && Array.isArray(response.features)) {
                            if (response.features.length > 0) {
                                if (response.features.length == 1) {
                                    eLayer.selected_ward.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(response));
                                    var ward = response.features[0].properties.ward;
                                    $('#ward_building_no').val(ward);
                                    var url1 = '{{ url("maps/ward-buildings") }}';
                                    var long = evt.coordinate[0];
                                    var lat = evt.coordinate[1];
                                    displayAjaxLoader();
                                    $.ajax({
                                        url: url1,
                                        type: 'post',
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            ward: ward,
                                        },
                                        success: function (Response) {
                                            wardBuildingsPopupContent.innerHTML = Response['popContentsHtml'];
                                            wardBuildingsPopupOverlay.setPosition([long, lat]);
                                            data1 = Response['buildings'];
                                            if (data1 && Array.isArray(data1)) {
                                                var format = new ol.format.WKT();

                                                for (var i = 0; i < data1.length; i++) {
                                                    var feature = format.readFeature(data1[i].geom, {
                                                        dataProjection: 'EPSG:4326',
                                                        featureProjection: 'EPSG:3857'
                                                    });

                                                }
                                            }

                                            removeAjaxLoader();
                                        },
                                        error: function (data1) {
                                            displayAjaxError();
                                        }
                                    });

                                    removeAjaxLoader();
                                } else {
                                    displayAjaxErrorModal('More than One wards Found, Please Zoom In or Select another Water Body');
                                }
                            } else {
                                displayAjaxErrorModal('Ward Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                        $('#buildingswards_control').removeClass('map-control-active');
                        map.un('pointermove', hoverOnWardsBuildingHandler);
                        map.un('singleclick', displayWardsBuildings);
                        currentControl = "";
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                })

                //displayAjaxLoader();
            }

            function kmlDragDropSummaryInformation(evt) {
                var format = new ol.format.WKT();
                var geom = format.writeGeometry(evt.feature.getGeometry().clone().transform('EPSG:3857', 'EPSG:4326'));
                var url1 = '{{ url("maps/ward-buildings") }}';
                var long = evt.coordinate[0];
                var lat = evt.coordinate[1];
                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        geom: geom,
                    },
                    success: function (Response) {
                        wardBuildingsPopupContent.innerHTML = Response['popContentsHtml'];
                        wardBuildingsPopupOverlay.setPosition([long, lat]);
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                            }
                        }

                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });

            }

            $("#form-drain-potential").submit(function (event) {
                event.preventDefault();

                if (eLayer.drainbuffer_polygon) {
                    eLayer.drainbuffer_polygon.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('drainbuffer_polygon', 'Point Buffer Polygon', layer);
                }
                var url1 = '{{ url("maps/drain-potential-buildings") }}';
                var distance = $('#buffer-distance').val();
                var drainCode = $('#drain-code').val();
                var long = $('#drain-long').val();
                var lat = $('#drain-lat').val();
                $('#DrainBufferCode').val(drainCode);
                $('#DBdistance').val(distance);

                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        drain_code: drainCode,
                        distance: distance

                    },
                    success: function (Response) {
                        drainPotentialSummaryuPopupContent.innerHTML = Response['popContentsHtml']
                        drainPotentialSummaryPopupOverlay.setPosition([long, lat]);
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.drain_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.drainbuffer_polygon.layer.getSource().addFeature(featureP);
                        $('#popup-drain-potential').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });

            $("#form-waterbodies-buildings").submit(function (event) {
                event.preventDefault();

                if (eLayer.wbbuffer_polygon) {
                    eLayer.wbbuffer_polygon.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('wbbuffer_polygon', 'Water Body Polygon', layer);
                }
                var url1 = '{{ url("maps/water-bodies-buildings") }}';
                var distance = $('#buffer-distance-waterbodies').val();
                var waterBodyCode = $('#water-body-code').val();
                var long = $('#water-body-long').val();
                var lat = $('#water-body-lat').val();
                $('#waterBodyCode').val(waterBodyCode);
                $('#WBdistance').val(distance);
                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        waterbody_code: waterBodyCode,
                        distance: distance

                    },
                    success: function (Response) {
                        waterBodyPopupContent.innerHTML = Response['popContentsHtml'];
                        waterBodyPopupOverlay.setPosition([long, lat]);
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.waterbodies_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.wbbuffer_polygon.layer.getSource().addFeature(featureP);
                        $('#popup-waterbodies-buildings').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });


            $("#form-buffer-polygon").submit(function (event) {

                event.preventDefault();
                if (eLayer.summary_buffer_polygon) {
                    eLayer.summary_buffer_polygon.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('summary_buffer_polygon', 'Summary Buffer Polygon', layer);
                }
                var url1 = '{{ url("maps/buffer-polygon-buildings") }}';
                var bufferDisancePolygon = $('#buffer-distance-polygon').val();
                var bufferPolygonGeom = $('#polygon-geom').val();
                var polygonCoordinates = $('#polygon-coordinates').val();
                $('#buffer_polygon_geom').val(bufferPolygonGeom);
                $('#buffer_polygon_distance').val(bufferDisancePolygon);
                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        bufferDisancePolygon: bufferDisancePolygon,
                        bufferPolygonGeom: bufferPolygonGeom
                    },
                    success: function (Response) {
                        bufferPolygonPopupContent.innerHTML = Response['popContentsHtml'];
                        $(bufferPolygonPopupContainer).show();
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.report_polygon_buffer.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.summary_buffer_polygon.layer.getSource().addFeature(featureP);
                        $('#popup-polygon-buffer').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });

            $("#form-road-buildings").submit(function (event) {
                event.preventDefault();
                if (eLayer.roadbuffer_polygon) {
                    eLayer.roadbuffer_polygon.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('roadbuffer_polygon', 'Road Buffer Polygon', layer);
                }
                var url1 = '{{ url("maps/road-buildings") }}';
                var distance = $('#buffer-distance-road').val();
                var roadCode = $('#road-code').val();
                var long = $('#road-long').val();
                var lat = $('#road-lat').val();
                $('#RDCode').val(roadCode);
                $('#RDBdistance').val(distance);
                displayAjaxLoader();

                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        road_code: roadCode,
                        distance: distance
                    },
                    success: function (Response) {
                        roadPopupContent.innerHTML = Response['popContentsHtml'];
                        roadPopupOverlay.setPosition([long, lat]);
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.roads_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.roadbuffer_polygon.layer.getSource().addFeature(featureP);

                        $('#popup-road-buildings').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });

            $("#form-point-buffer-buildings").submit(function (event) {
                event.preventDefault();
                if (eLayer.pointbuffer_polygon) {
                    eLayer.pointbuffer_polygon.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('pointbuffer_polygon', 'Point Buffer Polygon', layer);
                }
                if (eLayer.pointbuffer_buildings) {
                    eLayer.pointbuffer_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('pointbuffer_buildings', 'Point Buffer Buildings', layer);
                }


                var url1 = '{{ url("maps/point-buffer-buildings") }}';
                var distance = $('#buffer-distance-point').val();
                var long_pos = $('#point-buffer-long-pos').val();
                var lat_pos = $('#point-buffer-lat-pos').val();
                var long = $('#point-buffer-long').val();
                var lat = $('#point-buffer-lat').val();

                $('#PTB-long-csv').val(long);
                $('#PTB-lat-csv').val(lat);
                $('#PTB-distance').val(distance);

                displayPointBufferByCoordinates(lat_pos, long_pos);

                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        distance: distance,
                        long: long,
                        lat: lat,
                    },
                    success: function (Response) {
                        pointBufferPopupContent.innerHTML = Response['popContentsHtml'];
                        pointBufferPopupOverlay.setPosition([parseFloat(long_pos), parseFloat(lat_pos)]);
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.pointbuffer_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.pointbuffer_polygon.layer.getSource().addFeature(featureP);
                        $('#popup-point-buffer').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });

             $("#form-road-inaccessible").submit(function (event) {
                event.preventDefault();
                roadInaccessiblePopupOverlay.setPosition(undefined);
                if (eLayer.summary_road_inaccessible) {
                    eLayer.summary_road_inaccessible.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('summary_road_inaccessible', 'Summary Road Inaccessible', layer);
                }

                if (eLayer.road_inaccessible_buildings) {
                    eLayer.road_inaccessible_buildings.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 3
                            }),
                        })
                    });

                    addExtraLayer('road_inaccessible_buildings', 'Road Inaccessbile Buildings', layer);
                }
                var url1 = '{{ url("maps/road-inaccessible-summary-info") }}';
                var roadLength = $('#road-length').val();
                var vacutugRange = $('#vacutug-range').val();
                displayAjaxLoader();
                $.ajax({
                    url: url1,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        roadLength: roadLength,
                        vacutugRange: vacutugRange
                    },
                    success: function (Response) {
                        roadInaccessiblePopupContent.innerHTML = Response['popContentsHtml'];
                        roadInaccessiblePopupOverlay.setPosition(map.getView().getCenter());
                        $(roadInaccessiblePopupContainer).show();
                        data1 = Response['buildings'];
                        if (data1 && Array.isArray(data1)) {
                            var format = new ol.format.WKT();

                            for (var i = 0; i < data1.length; i++) {
                                var feature = format.readFeature(data1[i].geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                eLayer.road_inaccessible_buildings.layer.getSource().addFeature(feature);
                            }
                        }
                        var featureP = format.readFeature(Response['polygon'], {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });
                        eLayer.summary_road_inaccessible.layer.getSource().addFeature(featureP);
                        $('#road-inaccessible-input-form').modal('hide');
                        removeAjaxLoader();
                    },
                    error: function (data1) {
                        displayAjaxError();
                    }
                });
            });

            if (layer != '' && field != '' && val != '') {
                handleZoomToExtent(layer, field, val, true, null);

                if (layer == 'containments_layer') {
                    if (action == 'containment-buildings') {
                        displayContainmentBuildings(field, val);
                    } else if (action == 'containment-road') {
                        displayContainmentRoad(field, val);
                    }
                }

                if (layer == 'buildings_layer') {
                    if (action == 'building-road') {
                        displayBuildingRoad(field, val);
                    }
                }
            }

            setInitialZoom();

            function setInitialZoom() {
                map.getView().setCenter(ol.proj.transform([85.3617, 27.6470], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(19);
            }

            // Set map zoom to city
            // zoomToCity();

            // Set map zoom to city
            function zoomToCity() {
                map.getView().setCenter(ol.proj.transform([85.3617, 27.6470], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(12);
            }

            var hoverOnLayerHandler = function (evt) {
                if (evt.dragging) {
                    return;
                }
                var pixel = map.getEventPixel(evt.originalEvent);
                var hit = map.forEachLayerAtPixel(pixel, function () {
                    return true;
                });
                map.getTargetElement().style.cursor = hit ? 'pointer' : '';
            };

            /**
             * Elements that make up the popup for feature info.
             */
            var featureInfoPopupContainer = document.getElementById('feature-info-popup');
            var featureInfoPopupContent = document.getElementById('feature-info-popup-content');
            var featureInfoPopupCloser = document.getElementById('feature-info-popup-closer');


            /**
             * Create an overlay to anchor the popup to the map.
             */
            var featureInfoPopupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: featureInfoPopupContainer,
                autoPan: true,
                stopEvent: true,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(featureInfoPopupContainer).show();

            map.addOverlay(featureInfoPopupOverlay);

            /**
             * Add a click handler to hide the popup.
             * @return {boolean} Don't follow the href.
             */
            featureInfoPopupCloser.onclick = function () {
                featureInfoPopupOverlay.setPosition(undefined);
                featureInfoPopupCloser.blur();
                return false;
            };


            // Display information about feature
            function displayFeatureInformation(evt) {

                $('#feature_info_content').html('');
                var layer = $('#feature_info_overlay').val();
                if (layer == "buildings_layer"){
                    layer = "buildings_with_owners"
                }
                var long = evt.coordinate[0];
                var lat = evt.coordinate[1];

                if (!layer) {
                    Swal.fire({
                        title: `Please select a layer!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }

                var viewResolution = /** @type {number} */ (map.getView().getResolution());

                var params = {
                    'LAYERS': workspace + ':' + layer,
                    'FEATURE_COUNT': 1
                };


                if (layer == 'buildings_with_owners')
                {
                    params.PROPERTYNAME = 'bin,ward,road_code,estimated_area,floor_count,household_served,population_served,office_business_name,building_associated_to,well_presence_status,toilet_status,toilet_count,sewer_code,drain_code,surveyed_date,tax_code,desludging_vehicle_accessible,construction_year,distance_from_well,owner_name,owner_gender,owner_contact,functional_use_name,use_category_name,sanitation_system_technology_name,water_source_source'; }

                if (layer == 'wardboundary_layer') {
                    params.PROPERTYNAME = 'ward,area';
                }


                if (layer == 'containments_layer')
                {
                    params.PROPERTYNAME = 'id,type,location,size,pit_diameter,tank_length,tank_width,septic_criteria,construction_date,buildings_served,emptied_status,last_emptied_date,no_of_times_emptied';
                }
                if (layer == 'treatmentplants_layer') {
                    params.PROPERTYNAME = 'name,location,capacity_per_day,caretaker_name,caretaker_number,caretaker_gender,type';
                }
                if (layer == 'roadlines_layer') {
                    //removed ,rdcarwdth
                    params.PROPERTYNAME = 'code,name,hierarchy,surface_type,length,carrying_width,right_of_way';
                }

                if (layer == 'roads_width_zoom_layer') {
                    //removed ,rdcarwdth
                    params.PROPERTYNAME = 'code,name,hierarchy,surface_type,length,carrying_width';
                }
                if (layer == 'drains_layer') {
                    params.PROPERTYNAME = 'code,size,length,cover_type,surface_type,road_code';
                }
                if (layer == 'places_layer') {
                    params.PROPERTYNAME = 'name';
                }
                if (layer == 'water_samples_layer') {
                    params.PROPERTYNAME = 'sample_date,sample_location,no_of_samples_taken,water_coliform_test_result';
                }
                if (layer == 'jhe_taxzone') {
                    params.PROPERTYNAME = 'taxzoneids';
                }
                if (layer == 'waterbodys_layer') {
                    params.PROPERTYNAME = 'id,name,type';
                }
                if (layer == 'wards_layer') {
                    params.PROPERTYNAME = 'ward,area,no_build,no_contain,no_rcc_framed,no_load_bearing,no_wooden_mud,no_cgi_sheet,no_emptying,no_septic_tank_with_soak_pit';
                }
                if (layer == 'grids_layer') {
                    // removed no_pucca,no_katcha,
                    params.PROPERTYNAME = 'no_build,no_contain,total_rdlen,no_rcc_framed,no_load_bearing,no_wooden_mud,no_cgi_sheet,no_emptying,no_septic_tank_with_soak_pit';
                }
                if (layer == 'sewerlines_layer') {
                    params.PROPERTYNAME = 'code,length,location';
                }
                if (layer == 'waterborne_hotspots_layer') {
                    params.PROPERTYNAME = 'hotspot_location,ward,no_of_cases,notes,no_of_fatalities,male_cases,female_cases,other_cases,female_fatalities,male_fatalities,other_fatalities,disease';
                }
                if (layer == 'watersupply_network_layer') {
                    params.PROPERTYNAME = 'code,diameter,length,project_name,type,material_type,road_code';
                }

                if (layer == 'fsm_campaigns') {
                    params.PROPERTYNAME = 'campaign_name,start_date,end_date,project_name,supported_by,ward';
                }
                if (layer == 'sanitation_system_layer') {
                    params.PROPERTYNAME = 'id,area,type';
                }
                if (layer == 'buildings_tax_status_layer')
                {
                    params.PROPERTYNAME = 'bin,ward,road_code,tax_code,due_year';
                }
                if (layer == 'buildings_water_payment_status_layer')
                {
                    params.PROPERTYNAME = 'bin,ward,road_code,tax_code,due_year';
                }
                if (layer == 'toilets_layer') {
                    params.PROPERTYNAME = 'id,name,type,access_frm_nearest_road,male_seats,female_seats,male_or_female_facility,handicap_facility,children_facility,sanitary_supplies_disposal_facility,owner,operator_or_maintainer,indicative_sign,fee_collected,caretaker_name,caretaker_contact_number,ward,bin,status,no_of_hh_connected,no_of_male_users,no_of_female_users,no_of_children_users,no_of_pwd_users,total_no_of_toilets,amount_of_fee_collected,frequency_of_fee_collected,pwd_seats,caretaker_gender,location_name,total_no_of_urinals,separate_facility_with_universal_design,owning_institution_name,operator_or_maintainer_name';
                }
                if (layer == 'landuses_layer')
                {
                    params.PROPERTYNAME = 'id,shape_leng,shape_area,class';
                }
                if (layer == 'citypolys_layer')
                {
                    params.PROPERTYNAME = 'name,area';
                }
                if (layer == 'dengue_wards_layer')
                {
                    params.PROPERTYNAME = 'ward,total_dengue_cases';
                }
                if (layer == 'low_income_communities_layer')
                {
                    params.PROPERTYNAME = 'population_total,number_of_households,population_male,population_female,population_others,no_of_septic_tank,no_of_holding_tank,no_of_pit,no_of_sewer_connection,no_of_buildings,community_name,no_of_community_toilets';
                }

                var wmsSource = new ol.source.ImageWMS({
                    url: gurl_wms,
                    params: params
                });

                var url = wmsSource.getGetFeatureInfoUrl(
                    [+long, +lat], viewResolution, 'EPSG:3857',
                    {'INFO_FORMAT': 'application/json'});
                if (!url) {
                    Swal.fire({
                        title: 'Failed to generate URL!',
                        icon: "error",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return;
                }




                $('#feature_info_content').html('');

                displayAjaxLoader();
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {

                       if (data && data.features && Array.isArray(data.features)) {
                            if (data.features.length > 0) {
                                var html = '<div class="table-responsive">'+
                                    '<table class="table table-striped table-hover table-sm">'+
                                    '<thead class="table-info">'+
                                    '<tr>'+
                                    '<th>Attribute</th>'+
                            '<th>Value</th>'+
                            '</tr>'+
                            '</thead>'+
                            '<tbody>';
                                $.each(data.features[0].properties, function (k, v) {


                                    html += '<tr>';
                                    if (k == 'bin') {
                                        kk = 'House Number';
                                    } else if (k == 'gid') {
                                        kk = 'GID';
                                    } else if (k == 'holding') {
                                        kk = 'Holding';
                                    } else if (k == 'ward') {
                                        kk = 'Ward';
                                    } else if (k == 'toilet_status') {
                                        kk = 'Toilet Presence';
                                    } else if (k == 'toilet_count') {
                                        kk = 'Total Number of Toilets';
                                    } else if (k == 'floor_count') {
                                        kk = 'Floor Count';
                                    } else if (k == 'estimated_area') {
                                        kk = 'Estimated Area of the Building (sqm)';
                                    } else if (k == 'road_code') {
                                        kk = 'Road Code';
                                     } else if (k == 'right_of_way') {
                                        kk = 'Right of Way';
                                    }
                                    else if (k == 'building_associated_to') {
                                        kk = 'House Number of Main Building';
                                    } else if (k == 'containcd') {
                                        kk = 'Containment ID';
                                    } else if (k == 'office_business_name') {
                                        kk = 'Office Name';
                                    } else if (k == 'structure_type_type') {
                                        kk = 'Structure Type';
                                    }
                                    else if (k == 'cover_type') {
                                        kk = 'Cover Type';
                                    }
                                    else if (k == 'material_type') {
                                        kk = 'Material Type';
                                    }
                                    else if (k == 'functional_use_name') {
                                        kk = 'Functional Use of Building';
                                        //bldguse = JSON.parse("{{$bldguse}}".replace(/&quot;/g, '"'));
                                        v != null ? v = v : v = "No data";
                                    } else if (k == 'use_category_name') {
                                        kk = 'Use Categories of Building';
                                        //usecatg = JSON.parse("{{$usecatg}}".replace(/&quot;/g, '"'));
                                        v != null ? v = v : v = "No data";
                                    }
                                    else if (k == 'sanitation_system_technology_name') {
                                        kk = 'Sanitation System Technology';
                                    }
                                    else if (k == 'surveyed_date') {
                                        kk = 'Surveyed Date';
                                        //( Commented - Might be needed in the future )
                                        /*} else if (k == 'draincode') {
                                            kk = 'Drain Code';
                                        } else if (k == 'drain_connected_date') {
                                            kk = 'Drain Connected Date';*/
                                    } else if (k == 'id') {
                                        kk = 'ID';
                                    } else if (k == 'type') {
                                        kk = 'Type';
                                        if (v ==1 ) {
                                            v = 'Centralized WWTP';
                                        }else if(v==2){
                                            v = 'Decentralized WWTP';
                                        }
                                        else if(v==3){
                                            v = 'FSTP';
                                        }
                                    } else if (k == 'size') {
                                        kk = 'Volume (m³)';
                                    } else if (k == 'location') {
                                        kk = 'Location';
                                    } else if (k == 'population_served') {
                                        kk = 'Population Served';
                                    } else if (k == 'buildings_served') {
                                        kk = 'Buildings Served';
                                    } else if (k == 'household_served') {
                                        kk = 'Household Served';
                                    } else if (k == 'roadcd') {
                                        kk = 'Road Code';
                                    } else if (k == 'pit_number') {
                                        kk = 'Pit Number';
                                    } else if (k == 'pit_diameter') {
                                        kk = 'Pit Diameter (m)';
                                    } else if (k == 'depth') {
                                        kk = 'Depth (m)';
                                    } else if (k == 'tank_length') {
                                        kk = 'Tank Length (m)';
                                    } else if (k == 'tank_width') {
                                        kk = 'Tank Width (m)';
                                    } else if (k == 'name') {
                                        kk = 'Name';
                                        //( Commented - Might be needed in the future )
                                        /*} else if (k == 'roadnm') {
                                            kk = 'Road Name';
                                        } else if (k == 'rdwidth') {
                                            kk = 'Road Width';
                                        } else if (k == 'rdcarwdth') {
                                            kk = 'Carrying Width of the Road (m)';
                                        } else if (k == 'transstncd') {
                                            kk = 'Transfer Station Code';*/
                                    } else if (k == 'next_emptying_date') {
                                        kk = 'Next Emptying Date';
                                    } else if (k == 'emptied_status') {
                                        kk = 'Emptied Status';
                                    } else if (k == 'last_emptied_date') {
                                        kk = 'Last Emptied Date';
                                    } else if (k == 'no_of_times_emptied') {
                                        kk = 'No. of Times Emptied';
                                    }  else if (k == 'code') {
                                        kk = 'Code';
                                    } else if (k == 'width') {
                                        kk = 'Width (m)';
                                    } else if (k == 'hierarchy') {
                                        kk = 'Hierarchy';
                                    } else if (k == 'surface_type') {
                                        kk = 'Surface Type';
                                    } else if (k == 'length') {
                                        kk = 'Length (m)';
                                    } else if (k == 'carrying_width') {
                                        kk = 'Carrying Width (m)';
                                    } else if (k == 'contact_info') {
                                        kk = 'Contact Info';
                                    } else if (k == 'roadhier') {
                                        kk = 'Road Hierarchy';
                                    } else if (k == 'rdsurf') {
                                        kk = 'Road Surface Type';
                                    } else if (k == 'lining') {
                                        kk = 'Buiding';
                                    } else if (k == 'area') {
                                        kk = 'Area (sqm)';
                                    }  else if (k == 'lengthm') {
                                        kk = 'Length (m)';
                                    } else if (k == 'cpacitym3') {
                                        kk = 'Capacity (m³)';
                                    }  else if (k == 'srvtype') {
                                        kk = 'Service Type';
                                    } else if (k == 'srvname') {
                                        kk = 'Service Name';
                                    } else if (k == 'no_build') {
                                        kk = 'No. of Buildings';
                                    } else if (k == 'no_contain') {
                                        kk = 'No. of Containments';
                                    } else if (k == 'total_rdlen') {
                                        kk = 'Total Length of Road';
                                    }
                                        /*} else if (k == 'no_pucca') {
                                            kk = 'No. of pucca';
                                        } else if (k == 'no_katcha') {
                                            kk = 'No. of katcha';*/
                                     else if (k == 'no_septic_tank') {
                                        kk = 'No. of septic tank';
                                    } else if (k == 'drain_code') {
                                        kk = 'Drain Code';
                                    } else if (k == 'sewer_code') {
                                        kk = 'Sewer Code';
                                    }  else if (k == 'office_business_name') {
                                        kk = 'Office Business Name';
                                    } else if (k == 'water_source_source') {
                                        kk = 'Water Source';
                                    }else if (k == 'water_quality_satisfaction') {
                                        kk = 'Water Quality Satisfaction';
                                    } else if (k == 'well_presence') {
                                        kk = 'Well Presence';
                                    } else if (k == 'septic_criteria') {
                                        kk = 'Septic Tank Standard Compliance';
                                    } else if (k == 'construction_date') {
                                        kk = 'Cosnstuction Date';
                                    } else if (k == 'no_rcc_framed') {
                                        kk = 'No. of RCC Framed';
                                    } else if (k == 'no_load_bearing') {
                                        kk = 'No. of Load Bearing';
                                    } else if (k == 'no_wooden_mud') {
                                        kk = 'No. of Wooden Bearing';
                                    } else if (k == 'no_cgi_sheet') {
                                        kk = 'No. of CGI Sheet';
                                    } else if (k == 'roadname') {
                                        kk = 'Sewer Name';
                                    } else if (k == 'length_m_1') {
                                        kk = 'Length in Meters';
                                    } else if (k == 'sewer_p') {
                                        kk = 'Sewer P';
                                    } else if (k == 'sewer_loc') {
                                        kk = 'Sewer Location';
                                    } else if (k == 'sewer_size') {
                                        kk = 'Sewer Size';
                                    } else if (k == 'diameter') {
                                        kk = 'Diameter (m)';
                                    } else if (k == 'date_added') {
                                        kk = 'Date Added';
                                        v=v.substring(0,10);
                                    } else if (k == 'no_cases') {
                                        kk = 'No. of Cases';
                                    } else if (k == 'notes') {
                                        kk = 'Notes';
                                    } else if (k == 'campaign_date') {
                                        kk = 'Campaign Date';
                                    } else if (k == 'start_date') {
                                        kk = 'Start Date';
                                        v=v.substring(0,10);
                                    } else if (k == 'end_date') {
                                        kk = 'End Date';
                                        v=v.substring(0,10);
                                    } else if (k == 'campaign_name') {
                                        kk = 'Campaign Name';
                                    } else if (k == 'project_name') {
                                        kk = 'Project Name';
                                    } else if (k == 'supported_by') {
                                        kk = 'Supported By';
                                    } else if (k == 'owner_name') {
                                        kk = 'Owner\'s Name';
                                    } else if (k == 'owner_contact') {
                                        kk = 'Owner Contact';
                                    } else if (k == 'owner_gender') {
                                         kk = 'Owner Gender';
                                    } else if (k == 'desludging_vehicle_accessible') {
                                         kk = 'Desludging Vehicle Accessible';
                                    } else if (k == 'construction_year') {
                                         kk = 'Construction Year';
                                    } else if (k == 'distance_from_well') {
                                         kk = 'Distance From Well';
                                    } else if (k == 'house_image') {
                                         kk = 'House Image';
                                    } else if (k == 'well_presence_status') {
                                         kk = 'Well Presence Status';
                                    } else if (k == 'access_frm_nearest_road') {
                                         kk = 'Access From Nearest Road';
                                    } else if (k == 'caters_male_female') {
                                         kk = 'Caters Male Female';
                                    } else if (k == 'male_toilets') {
                                         kk = 'Male Toilets';
                                    } else if (k == 'female_toilets') {
                                         kk = 'Female Toilets';
                                    } else if (k == 'male_or_female_facility') {
                                         kk = 'Male or Female Facility';
                                    } else if (k == 'handicap_facility') {
                                         kk = 'Handicap Facility';
                                    } else if (k == 'children_facility') {
                                         kk = 'Children Facility';
                                    } else if (k == 'supplyy_disposal_facility') {
                                         kk = 'Supplyy Disposal Facility';
                                    } else if (k == 'owner') {
                                         kk = 'Owner';
                                    }
                                    else if (k == 'no_of_hh_connected') {
                                         kk = 'No. of Households Served';
                                    }else if (k == 'no_of_male_users') {
                                         kk = 'No. of Male Users';
                                    }else if (k == 'no_of_female_users') {
                                         kk = 'No. of Female Users';
                                    }else if (k == 'no_of_children_users') {
                                         kk = 'No. of Children Users';
                                    }else if (k == 'no_of_pwd_users') {
                                         kk = 'No. of People with Disability Users';
                                    }else if (k == 'total_no_of_toilets') {
                                         kk = 'Total Number of Seats';
                                    }else if (k == 'amount_of_fee_collected') {
                                         kk = 'Uses Fee Rate';
                                    }
                                    else if (k == 'pwd_seats') {
                                         kk = 'No. of Seats for People with Disability Users';
                                    }else if (k == 'frequency_of_fee_collected') {
                                         kk = 'Frequency of Fee Collection';
                                    }   else if (k == 'location_name') {
                                         kk = 'Location';
                                    }  else if (k == 'operator_or_maintainer_name') {
                                         kk = 'Name of Operate and Maintained by';
                                    }    else if (k == 'total_no_of_urinals') {
                                         kk = 'Total Number of Urinals';
                                    } else if (k == 'separate_facility_with_universal_design') {
                                         kk = 'Adherence with Universal Design Principles';
                                    }  else if (k == 'owning_institution_name') {
                                         kk = 'Name of Owning Institution';
                                    }else if (k == 'operator_or_maintainer') {
                                         kk = 'Operator or Maintainer';
                                    } else if (k == 'not_used_time') {
                                         kk = 'Not Used Time';
                                    } else if (k == 'indicative_sign') {
                                         kk = 'Indicative Sign';
                                    } else if (k == 'fee_collected') {
                                         kk = 'Fee Collected';
                                    } else if (k == 'caretaker') {
                                         kk = 'Caretaker';
                                    } else if (k == 'shape_leng') {
                                         kk = 'Shape Length';
                                    } else if (k == 'shape_area') {
                                         kk = 'Shape Area';
                                    }  else if (k == 'capacity_per_day') {
                                         kk = 'Capacity Per Day (m³/day)';
                                    } else if (k == 'caretaker_name') {
                                         kk = 'Caretaker Name';
                                    } else if (k == 'caretaker_number') {
                                         kk = 'Caretaker Number';
                                    } else if (k == 'caretaker_gender') {
                                         kk = 'Caretaker Gender';
                                    }  else if (k == 'total_dengue_cases') {
                                         kk = 'Total Dengue Cases';
                                    }  else if (k == 'date') {
                                         kk = 'Date';
                                    }  else if (k == 'hotspot_location') {
                                         kk = 'Hotspot Location';
                                    }  else if (k == 'no_of_cases') {
                                         kk = 'No. of Cases';
                                    }  else if (k == 'notes') {
                                         kk = 'Notes';
                                        }  else if (k == 'class') {
                                            kk = 'Class';
                                    } else if (k == 'no_of_fatalities') {
                                         kk = 'No. of Fatalities';
                                    }  else if (k == 'male_cases') {
                                         kk = 'Male Cases';
                                    } else if (k == 'other_cases') {
                                         kk = 'Other Cases';
                                    }else if (k == 'female_fatalities') {
                                         kk = 'Female Fatalities';
                                    }else if (k == 'male_fatalities') {
                                         kk = 'Male Fatalities';
                                    }else if (k == 'other_fatalities') {
                                         kk = 'Other Fatalities';
                                    }else if (k == 'disease') {
                                         kk = 'Disease';
                                    }
                                    else if (k == 'sample_date') {
                                         kk = 'Sample Date';
                                    }
                                    else if (k == 'sample_location') {
                                         kk = 'Sample Location';
                                    }
                                    else if (k == 'no_of_samples_taken') {
                                         kk = 'No. of Samples Taken';
                                    }
                                    else if (k == 'water_coliform_test_result') {
                                         kk = 'Water Coliform Test Result';
                                    }
                                    else if (k == 'female_cases') {
                                         kk = 'Female Cases';
                                    }
                                    else if (k == 'female_cases') {
                                         kk = 'Female Cases';
                                    }else if (k == 'tax_code') {
                                         kk = 'Tax Code';
                                    }  else if (k == 'due_year') {
                                         kk = 'Years Due';
                                    }  else if (k == 'male_seats') {
                                         kk = 'Male Seats';
                                    }  else if (k == 'female_seats') {
                                         kk = 'Female Seats';
                                    }  else if (k == 'sanitary_supplies_disposal_facility') {
                                         kk = 'Sanitary Supplies Disposal Facility';
                                    }  else if (k == 'caretaker_contact_number') {
                                         kk = 'Caretaker Contact Number';
                                    }   else if (k == 'community_name') {
                                         kk = 'Community Name';
                                    }    else if (k == 'no_of_buildings') {
                                         kk = 'No. of Buildings';
                                    }   else if (k == 'no_of_community_toilets') {
                                         kk = 'No. of Community Toilets';
                                    }  else if (k == 'population_total') {
                                         kk = 'Population';
                                    }    else if (k == 'number_of_households') {
                                         kk = 'No. of Households';
                                    }    else if (k == 'population_male') {
                                         kk = 'Male Population';
                                    }  else if (k == 'population_female') {
                                         kk = 'Female Population';
                                    }  else if (k == 'population_others') {
                                         kk = 'Other Population';
                                    }  else if (k == 'no_of_septic_tank') {
                                         kk = 'No. of Septic Tanks';
                                    }    else if (k == 'no_of_holding_tank') {
                                         kk = 'No. of Holding Tanks';
                                    }   else if (k == 'no_of_pit') {
                                         kk = 'Number of Pit';
                                    }   else if (k == 'no_of_sewer_connection') {
                                         kk = 'No. of Sewer Connections';
                                    }    else if (k == 'no_emptying') {
                                         kk = 'No. of Emptying';
                                    }    else if (k == 'no_septic_tank_with_soak_pit') {
                                         kk = 'No. of Septic Tank With Soak Pit';
                                    } else {
                                        kk = k;
                                    }
                                    if (v == null) {
                                        vv = '';
                                    }
                                    else {
                                        if(v === true) {
                                            vv = 'Yes';
                                        }
                                        else if(v === false){
                                             vv = 'No';
                                        }
//                                        else if (v.toString().toLowerCase().match(/\.(jpg|png|gif)/g)) {
//                                            var src = "<?php echo asset('storage/emptyings/receipts');?>/"+ v;
//                                            vv = '<img height="50%" width="50%" src="'+src+'"/>';
//                                        }
                                        else if (checkNumberIfFloat(v) == true){
                                            vv = v.toFixed(2);
                                        }
                                        else{
                                            vv = v;
                                        }
                                    }
                                    if(k=='bin' && layer =='buildings_with_owners')
                                    {
                                        html += '<td><strong>' + kk + '</strong></td>';
                                        html += '<td><strong>' + vv + '</strong>@can('Edit Building Structure')<br>Click <a href="{{ url("building-info/buildings") }}/'+vv+'/edit">here</a> to edit building info @endcan</td>';
                                    }
                                    else{
                                    html += '<td>' + kk + '</td>';
                                    html += '<td>' + vv + '</td>';
                                }
                                    html += '</tr>';
                                });
                                if (layer == 'buildings_with_owners') {
                                    html += '<tr>';
                                    html += '<td>House Image</td>';
                                    html += '<td><a target="_blank" href="{{asset('img/building-image-dummy.jpg')}}">Link to image</a></td>';
                                    html += '</tr>';
                                    html += '<tr><td colspan="2">House Image Preview :</td></tr>';
                                    html += '<tr><td colspan="2" style="text-align:center;"><img height="50%" width="50%" src="{{asset('img/building-image-dummy.jpg')}}"/></td></tr>';
                                }
                                html += '</tbody></table>';
                                $('#feature_information').html(html);
                                removeAjaxLoader();
                            } else {
                                $('#feature_information').html('');
                                $('#feature-info-popup-closer').click();
                                displayAjaxErrorModal('Feature Not Found');
                            }
                        } else {
                            displayAjaxError();
                        }
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });

                featureInfoPopupOverlay.setPosition(evt.coordinate);
            }

            /**
             * Elements that make up the popup.
             */
            var popupContainer = document.getElementById('popup');
            var popupContent = document.getElementById('popup-content');
            var popupCloser = document.getElementById('popup-closer');


            /**
             * Create an overlay to anchor the popup to the map.
             */
            var popupOverlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
                element: popupContainer,
                autoPan: true,
                stopEvent: true,
                autoPanAnimation: {
                    duration: 250
                }
            }));

            $(popupContainer).show();

            map.addOverlay(popupOverlay);

            /**
             * Add a click handler to hide the popup.
             * @return {boolean} Don't follow the href.
             */
            popupCloser.onclick = function () {
                popupOverlay.setPosition(undefined);
                popupCloser.blur();
                return false;
            };

            //DRAGGABLE OVERLAYS - SUJAL
// Drag interaction
            var ol_Drag = new ol.interaction.DragOverlay({
                overlays: [
                    reportPopupOverlay,
                    drainPotentialSummaryPopupOverlay,
                    waterBodyPopupOverlay,
                    wardBuildingsPopupOverlay,
                    bufferPolygonPopupOverlay,
                    roadPopupOverlay,
                    popupMarkerOverlay,
                    buildingsToRoadPopupOverlay,
                    pointBufferPopupOverlay,
                    exportCsvPopupOverlay,
                    populationPopupOverlay,
                    roadInaccessiblePopupOverlay,
                    feedbackPopupOverlay,
                ]
            });
            map.addInteraction(ol_Drag);

//END DRAGGABLE OVERLAYS


            // Display information about coordinate
            function displayCoordinateInformation(evt) {
                var coordinate = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');

                var html = '<div style="text-decoration:underline;">EPSG:3857</div>';
                html += '<table style="margin-bottom: 10px;">';
                html += '<tr>';
                html += '<td style="padding-right:5px;">Longitude</td>';
                html += '<td>' + evt.coordinate[0].toFixed(6) + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td style="padding-right:5px;">Latitude</td>';
                html += '<td>' + evt.coordinate[1].toFixed(6) + '</td>';
                html += '</tr>';
                html += '<table>';
                html += '<div style="text-decoration:underline;">EPSG:4326</div>';
                html += '<table>';
                html += '<tr>';
                html += '<td style="padding-right:5px;">Longitude</td>';
                html += '<td>' + coordinate[0].toFixed(6) + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td style="padding-right:5px;">Latitude</td>';
                html += '<td>' + coordinate[1].toFixed(6) + '</td>';
                html += '</tr>';
                html += '<table>';

                popupContent.innerHTML = html;
                popupOverlay.setPosition(evt.coordinate);
            }

            // Display popup form point buffer
            function displayPopupPointBuffer(evt) {
                var coordinate = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                $('#point-buffer-long-pos').val(evt.coordinate[0].toFixed(6));
                $('#point-buffer-lat-pos').val(evt.coordinate[1].toFixed(6));
                $('#point-buffer-long').val(coordinate[0].toFixed(6));
                $('#point-buffer-lat').val(coordinate[1].toFixed(6));
                $('#pointbuffer_control').removeClass('map-control-active');
                map.un('singleclick', displayPopupPointBuffer);
                currentControl = "";
                $('#popup-point-buffer').modal('show');


            }


            function displayContainmentBuildings(field, val) {
                var url = '{{ url("maps/containment-buildings") }}' + '/' + field + '/' + val;

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                            "_token": "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].lat && data[i].long) {
                                if (!eLayer.markers) {
                                    var markerLayer = new ol.layer.Vector({
                                        // visible: false,
                                        source: new ol.source.Vector()
                                    });

                                    addExtraLayer('markers', 'Markers', markerLayer);
                                    // showExtraLayer('markers');
                                }

                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data[i].long), parseFloat(data[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/building-purple.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.markers.layer.getSource().addFeature(feature);
                            }
                        }

                        showLayer('buildings_layer');
                    },
                    error: function (data) {

                    }
                });
            }

            function displayContainmentRoad(field, val) {
                var url = '{{ url("maps/containment-road") }}' + '/' + field + '/' + val;
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (dataResponse) {
                        if (dataResponse.c_lat && dataResponse.c_long && dataResponse.r_lat && dataResponse.r_long) {
                            if (!eLayer.markers) {
                                var markerLayer = new ol.layer.Vector({
                                    // visible: false,
                                    source: new ol.source.Vector()
                                });

                                addExtraLayer('markers', 'Markers', markerLayer);
                                // showExtraLayer('markers');
                            }

                            var markerFeature = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse.r_long), parseFloat(dataResponse.r_lat)], 'EPSG:4326', 'EPSG:3857'))
                            });

                            var markerStyle = new ol.style.Style({
                                image: new ol.style.Icon({
                                    anchor: [0.5, 1],
                                    src: '{{ url("/")}}/img/road-blue.png'
                                })
                            });

                            markerFeature.setStyle(markerStyle);

                            eLayer.markers.layer.getSource().addFeature(markerFeature);

                            if (!eLayer.line) {
                                var lineLayer = new ol.layer.Vector({
                                    // visible: false,
                                    source: new ol.source.Vector()
                                });

                                addExtraLayer('line', 'Line', lineLayer);
                                // showExtraLayer('line');
                            }

                            var lineFeature = new ol.Feature({
                                geometry: new ol.geom.LineString([
                                    ol.proj.transform([parseFloat(dataResponse.c_long), parseFloat(dataResponse.c_lat)], 'EPSG:4326', 'EPSG:3857'),
                                    ol.proj.transform([parseFloat(dataResponse.r_long), parseFloat(dataResponse.r_lat)], 'EPSG:4326', 'EPSG:3857')
                                ])
                            });

                            var lineStyle = new ol.style.Style({
                                stroke: new ol.style.Stroke({
                                    color: '#ff0000',
                                }),
                            });

                            lineFeature.setStyle(lineStyle);

                            eLayer.line.layer.getSource().addFeature(lineFeature);
                        }

                        showLayer('roadlines_layer');
                    },
                    error: function (dataResponse) {

                    }
                });
            }

            function displayBuildingRoad(field, val) {
                var url = '{{ url("maps/building-road") }}' + '/' + field + '/' + val;
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (dataResponse) {
                        if (dataResponse.c_lat && dataResponse.c_long && dataResponse.r_lat && dataResponse.r_long) {
                            if (!eLayer.markers) {
                                var markerLayer = new ol.layer.Vector({
                                    // visible: false,
                                    source: new ol.source.Vector()
                                });

                                addExtraLayer('markers', 'Markers', markerLayer);
                                // showExtraLayer('markers');
                            }

                            var markerFeature = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse.r_long), parseFloat(dataResponse.r_lat)], 'EPSG:4326', 'EPSG:3857'))
                            });

                            var markerStyle = new ol.style.Style({
                                image: new ol.style.Icon({
                                    anchor: [0.5, 1],
                                    src: '{{ url("/")}}/img/road-blue.png'
                                })
                            });

                            markerFeature.setStyle(markerStyle);

                            eLayer.markers.layer.getSource().addFeature(markerFeature);

                            if (!eLayer.line) {
                                var lineLayer = new ol.layer.Vector({
                                    // visible: false,
                                    source: new ol.source.Vector()
                                });

                                addExtraLayer('line', 'Line', lineLayer);
                                // showExtraLayer('line');
                            }

                            var lineFeature = new ol.Feature({
                                geometry: new ol.geom.LineString([
                                    ol.proj.transform([parseFloat(dataResponse.c_long), parseFloat(dataResponse.c_lat)], 'EPSG:4326', 'EPSG:3857'),
                                    ol.proj.transform([parseFloat(dataResponse.r_long), parseFloat(dataResponse.r_lat)], 'EPSG:4326', 'EPSG:3857')
                                ])
                            });

                            var lineStyle = new ol.style.Style({
                                stroke: new ol.style.Stroke({
                                    color: '#ff0000',
                                }),
                            });

                            lineFeature.setStyle(lineStyle);

                            eLayer.line.layer.getSource().addFeature(lineFeature);
                        }

                        showLayer('roadlines_layer');
                    },
                    error: function (dataResponse) {

                    }
                });
            }

            function findNearestRoad(evt) {
                var coordinate = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var long = coordinate[0];
                var lat = coordinate[1];

                if (eLayer.nearest_road_markers) {
                    eLayer.nearest_road_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('nearest_road_markers', 'Nearest Road Markers', layer);
                }

                if (eLayer.nearest_road_line) {
                    eLayer.nearest_road_line.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('nearest_road_line', 'Nearest Road Line', layer);
                }

                // showExtraLayer('nearest_road_markers');
                // showExtraLayer('nearest_road_line');

                var markerFeature = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857'))
                });

                var markerStyle = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: '{{ url("/")}}/img/pin-blue.png'
                    })
                });

                markerFeature.setStyle(markerStyle);

                eLayer.nearest_road_markers.layer.getSource().addFeature(markerFeature);

                displayAjaxLoader();
                var url = '{{ url("maps/nearest-road") }}' + '/' + long + '/' + lat;
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function (dataResponse) {
                        if (dataResponse.lat && dataResponse.long) {
                            var markerFeature1 = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse.long), parseFloat(dataResponse.lat)], 'EPSG:4326', 'EPSG:3857'))
                            });

                            var markerStyle1 = new ol.style.Style({
                                image: new ol.style.Icon({
                                    anchor: [0.5, 1],
                                    src: '{{ url("/")}}/img/road-yellow.png'
                                })
                            });

                            markerFeature1.setStyle(markerStyle1);

                            eLayer.nearest_road_markers.layer.getSource().addFeature(markerFeature1);

                            var lineFeature = new ol.Feature({
                                geometry: new ol.geom.LineString([
                                    ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857'),
                                    ol.proj.transform([parseFloat(dataResponse.long), parseFloat(dataResponse.lat)], 'EPSG:4326', 'EPSG:3857')
                                ])
                            });

                            var lineStyle = new ol.style.Style({
                                stroke: new ol.style.Stroke({
                                    color: '#9000ff',
                                }),
                            });

                            lineFeature.setStyle(lineStyle);

                            eLayer.nearest_road_line.layer.getSource().addFeature(lineFeature);
                        }

                        // map.getView().setCenter(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'));
                        // map.getView().setZoom(14);

                        removeAjaxLoader();

                        showLayer('roadlines_layer');
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on buildings that have taxes on due
            function displayDueBuildings() {
                if (eLayer.building_markers) {
                    eLayer.building_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('building_markers', 'Building Markers', layer);
                }

                // showExtraLayer('building_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/due-buildings") }}';
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                            "_token": "{{ csrf_token() }}",

                        },
                    success: function (dataResponse) {
                        for (var i = 0; i < dataResponse.length; i++) {
                            if (dataResponse[i].lat && dataResponse[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse[i].long), parseFloat(dataResponse[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/building-green.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.building_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('buildings_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on containments of applications
            function displayApplicationContainments() {
                if (eLayer.application_containment_markers) {
                    eLayer.application_containment_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('application_containment_markers', 'Building Markers', layer);
                }

                // showExtraLayer('application_containment_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/application-containments") }}';
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function (dataResponse) {
                        for (var i = 0; i < dataResponse.length; i++) {
                            if (dataResponse[i].lat && dataResponse[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse[i].long), parseFloat(dataResponse[i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: dataResponse[i].application_id,
                                    house_number: dataResponse[i].house_number,
                                    service_provider: dataResponse[i].service_provider,
                                    application_date: dataResponse[i].application_date
                                });

                                var filename = 'application.png';
                                if (dataResponse[i].sludge_collection_status) {
                                    filename = 'application-sludge-collection.png';
                                }
                                else if (dataResponse[i].feedback_status) {
                                    filename = 'application-feedback.png';
                                } else if (dataResponse[i].emptying_status) {
                                    filename = 'application-emptying.png';
                                } else if (dataResponse[i].assessment_status) {
                                    //Hiding Assessment so assigning same marker as application
                                    filename = 'application.png';
                                }

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/' + filename
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.application_containment_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }


            // Display markers on applications not reached to treatment plants
            function displayApplicationNotTP() {
                if (eLayer.application_notTP_markers) {
                    eLayer.application_notTP_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTPDate_containment_markers) {
                    eLayer.application_NotTPDate_containment_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTP_containment_markers) {
                    eLayer.application_NotTP_containment_markers.layer.getSource().clear();
                }

                var layer = new ol.layer.Vector({
                    // visible: false,
                    source: new ol.source.Vector()
                });

                addExtraLayer('application_notTP_markers', 'Applications Markers', layer);


                displayAjaxLoader();
                var url = '{{ url("maps/application-not-tp") }}';
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function (dataResponse) {
                        var service_provider_legends = '';
                        var service_provider_markers = {};
                        dataResponse["service_providers"].forEach(function(service_provider,index){
                            service_provider_legends += "<tr>" +
                                '<td style="vertical-align: top;">' +
                                '<img src="{{ url("/")}}/img/marker-' + markerColors[index] + '.png"></td>' +
                                '<td> - ' + service_provider.company_name + '</td>'+
                                '</tr>';
                            service_provider_markers[service_provider.company_name]=markerColors[index];
                        });
                        $('#service_providers_legends').html(service_provider_legends);

                        for (var i = 0; i < dataResponse["data"].length; i++) {
                            if (dataResponse["data"][i].lat && dataResponse["data"][i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse["data"][i].long), parseFloat(dataResponse["data"][i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: dataResponse["data"][i].application_id,
                                    house_number: dataResponse["data"][i].house_number,
                                    application_date: dataResponse["data"][i].application_date,
                                    service_provider: dataResponse["data"][i].service_provider,
                                    service_provider_id: dataResponse["data"][i].service_provider_id,
                                    emptying_date: dataResponse["data"][i].emptying_date
                                });

                                var filename = 'application.png';

                                var styles = [
                                    new ol.style.Style({
                                        image: new ol.style.Icon({
                                            anchor: [0.5, 1],
                                            src: '{{ url("/")}}/img/marker-' + service_provider_markers[dataResponse["data"][i].service_provider] + '.png'
                                        })
                                    })
                                ];

                                feature.setStyle(styles);
                                eLayer.application_notTP_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();
                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }

            // Add handler to building search form submit
            $('#building_search_form').submit(function () {
                var val = $('#building_value_text').val().trim();
                var field = $('#building_field_select').val();
                var field_text = $('#building_field_select option:selected').text();

                if (!val) {
                    Swal.fire({
                        title: 'Please enter ' + field_text + '!',
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else if (field == 'places_layer' || field == 'roadlines_layer') {

                    findPlacesRoads(val, field);
                } else {
                    if (eLayer.searchResultBuilding) {
                        eLayer.searchResultBuilding.layer.getSource().clear();
                    } else {
                        var searchResultBuildingLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector(),
                            /*style: new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: '#00bfff',
                        width: 3
                    }),
                    })*/
                        });

                        addExtraLayer('searchResultBuilding', 'Search Result Building', searchResultBuildingLayer);
                    }

                    // showExtraLayer('searchResultBuilding');

                    displayAjaxLoader();
                    var url = '{{ url("maps/search-building") }}' + '/' + field + '/' + val;
                    $.ajax({
                        url: url,
                        type: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",

                        },
                        success: function (dataResponse) {
                            if (dataResponse) {
                                //eLayer.searchResultBuilding.layer.getSource().addFeatures((new ol.format.GeoJSON()).readFeatures(dataResponse));

                                if (dataResponse && Array.isArray(dataResponse)) {
                                    var format = new ol.format.WKT();

                                    for (var i = 0; i < dataResponse.length; i++) {
                                        var feature = format.readFeature(dataResponse[i].geom, {
                                            dataProjection: 'EPSG:4326',
                                            featureProjection: 'EPSG:3857'
                                        });
                                        if (dataResponse[i].building_associated_to == null) {
                                            var colorFeature = '#008000';
                                        } else {
                                            var colorFeature = '#00bfff';
                                        }
                                        feature.setStyle(
                                            new ol.style.Style({
                                                stroke: new ol.style.Stroke({
                                                    color: colorFeature,
                                                    width: 3
                                                })
                                            })
                                        );
                                        eLayer.searchResultBuilding.layer.getSource().addFeature(feature);
                                        handleZoomToExtent('buildings_layer', 'bin', dataResponse[0].bin, false, function () {
                                            removeAjaxLoader();
                                        });
                                    }
                                }

                                removeAjaxLoader();
                            } else {
                                displayAjaxErrorModal('Building Not Found');
                            }
                        },
                        error: function (dataResponse) {
                            displayAjaxError();
                        }
                    });
                }

                return false;
            });

            // Add handler to ward filter while locate
            /*$('#filterward_select').on('change', function(e) {
                e.preventDefault();
                disableAllControls();
                $('#print_modal').modal('hide');
                var ward = $('#filterward_select').val();

                if(eLayer.searchResultMarkers) {
                    eLayer.searchResultMarkers.layer.getSource().clear();
                }
                else {
                    var searchResultMarkerLayer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('searchResultMarkers', 'Search Result Markers', searchResultMarkerLayer);
                }
                //displayAjaxLoader();
                var url = '{{ url("maps/filterward/ward-center-coordinates") }}';
                $.ajax({
                    url:url,
                    type: 'get',
                    data: { ward: ward, "_token": "{{ csrf_token() }}"},
                    success: function(data){
                        var format = new ol.format.WKT();
                        var feature = format.readFeature(data.geom, {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });

                        feature.setStyle(
                            new ol.style.Style({
                                stroke: new ol.style.Stroke({color: '#00bfff',
                                    width: 3
                                })
                            })
                        );
                        eLayer.searchResultMarkers.layer.getSource().addFeature(feature);
                        handleZoomToExtent('wards_layer', 'ward', data.ward, false, function(){
                            removeAjaxLoader();
                        });

                    },
                    error: function(data) {
                        displayAjaxError();
                    }
                });
            });*/


            // Add handler to ward filter while removing other
           $('#filterward_select').on('change', function(e) {
                e.preventDefault();
                disableAllControls();
                $('#print_modal').modal('hide');
                var ward = $('#filterward_select').val();

                if(eLayer.searchResultMarkers) {
                    eLayer.searchResultMarkers.layer.getSource().clear();
                }
                else {
                    var searchResultMarkerLayer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('searchResultMarkers', 'Search Result Markers', searchResultMarkerLayer);
                }
                //displayAjaxLoader();
                var url = '{{ url("maps/clipward/ward-center-coordinates") }}';
                $.ajax({
                    url:url,
                    type: 'get',
                    data: { ward: ward },
                    success: function(data){
                        var format = new ol.format.WKT();
                        var feature = format.readFeature(data.geom, {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        });

                        feature.setStyle(
                            new ol.style.Style({

                                fill: new ol.style.Fill({
                                    color: 'rgba(239,241,246,1.0)'
                                }),
                                stroke: new ol.style.Stroke({
                                    color: 'rgba(239,241,246,1.0)'
                                })
                            })
                        );
                        eLayer.searchResultMarkers.layer.getSource().addFeature(feature);
                        handleZoomToExtent('ward_overlay', 'ward', data.ward, false, function(){
                            removeAjaxLoader();
                        });
                    },
                    error: function(data) {
                        displayAjaxError();
                    }
                });
            });


            // Add handler to text search form submit
            $('#text_search_form').submit(function () {
                var keywords = $('#search_keyword_text').val().trim();
                var layer = $('#search_layer_select').val();

                if (!keywords) {
                    Swal.fire({
                        title: `Please type keyword to search!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                } else {
                    if (eLayer.searchResultMarkers) {
                        eLayer.searchResultMarkers.layer.getSource().clear();
                    } else {
                        var searchResultMarkerLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });

                        addExtraLayer('searchResultMarkers', 'Search Result Markers', searchResultMarkerLayer);
                    }

                    // showExtraLayer('markers');

                    displayAjaxLoader();
                    var url = '{{ url("searchByKeywords") }}';
                    $.ajax({
                        url: url,
                        data: {
                            layer: layer,
                            keywords: keywords,
                            "_token": "{{ csrf_token() }}",
                        },
                        type: 'get',
                        success: function (dataResponse) {
                            if (dataResponse.gid) {
                                if (dataResponse.point) {
                                    var format = new ol.format.WKT();
                                    var markerFeature = format.readFeature(dataResponse.point, {
                                        dataProjection: 'EPSG:4326',
                                        featureProjection: 'EPSG:3857'
                                    });

                                    var markerStyle = new ol.style.Style({
                                        image: new ol.style.Icon({
                                            anchor: [0.5, 1],
                                            src: '{{ url("/")}}/img/pin-purple.png'
                                        })
                                    });

                                    markerFeature.setStyle(markerStyle);

                                    eLayer.searchResultMarkers.layer.getSource().addFeature(markerFeature);
                                }

                                if (dataResponse.geom) {
                                    var format = new ol.format.WKT();
                                    var feature = format.readFeature(dataResponse.geom, {
                                        dataProjection: 'EPSG:4326',
                                        featureProjection: 'EPSG:3857'
                                    });

                                    if (feature.getGeometry() instanceof ol.geom.MultiLineString) {
                                        eLayer.searchResultMarkers.layer.getSource().addFeature(feature);
                                    }
                                }

                                handleZoomToExtent(layer, 'gid', dataResponse.gid, false, function () {
                                    removeAjaxLoader();
                                });
                            } else {
                                displayAjaxErrorModal('No Results Found');
                            }
                        },
                        error: function (dataResponse) {
                            displayAjaxError();
                        }
                    });
                }

                return false;
            });

            //search places and roads
            function findPlacesRoads(keywords, layer) {
                if (!keywords) {
                    Swal.fire({
                        title: `Please type keyword to search!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                } else {
                    if (eLayer.searchResultMarkers) {
                        eLayer.searchResultMarkers.layer.getSource().clear();
                    } else {
                        var searchResultMarkerLayer = new ol.layer.Vector({
                            // visible: false,
                            source: new ol.source.Vector()
                        });

                        addExtraLayer('searchResultMarkers', 'Search Result Markers', searchResultMarkerLayer);
                    }

                    // showExtraLayer('markers');

                    displayAjaxLoader();
                    var url = '{{ url("maps/search-by-keywords") }}';

                    $.ajax({
                        url: url,
                        data: {
                            layer: layer,
                            keywords: keywords,
                            "_token": "{{ csrf_token() }}",
                        },
                        type: 'get',
                        success: function (data) {
                            if (data.gid) {
                                if (data.point) {
                                    var format = new ol.format.WKT();
                                    var markerFeature = format.readFeature(data.point, {
                                        dataProjection: 'EPSG:4326',
                                        featureProjection: 'EPSG:3857'
                                    });

                                    var markerStyle = new ol.style.Style({
                                        image: new ol.style.Icon({
                                            anchor: [0.5, 1],
                                            src: '{{ url("/")}}/img/pin-purple.png'
                                        })
                                    });

                                    markerFeature.setStyle(markerStyle);

                                    eLayer.searchResultMarkers.layer.getSource().addFeature(markerFeature);
                                }

                                if (data.geom) {
                                    var format = new ol.format.WKT();
                                    var feature = format.readFeature(data.geom, {
                                        dataProjection: 'EPSG:4326',
                                        featureProjection: 'EPSG:3857'
                                    });

                                    if (feature.getGeometry() instanceof ol.geom.MultiLineString) {
                                        eLayer.searchResultMarkers.layer.getSource().addFeature(feature);
                                    }
                                }
                                if (layer == 'roadlines_layer') {
                                    var column = 'code';
                                } else {
                                    var column = 'id';
                                }
                                handleZoomToExtent(layer, column, data.gid, false, function () {
                                    removeAjaxLoader();
                                });
                            } else {
                                displayAjaxErrorModal('No Results Found');
                            }
                        },
                        error: function (data) {
                            displayAjaxError();
                        }
                    });
                }

                return false;

            }

            // Add handler to ward filer form submit
            $('#ward_form').submit(function () {
                var selectedWards = $('#ward').val();
                var selectedLayer = $('#ward_overlay').val();
                if (!selectedWards || selectedWards.length === 0 || !selectedLayer || selectedLayer.length === 0) {
                    Swal.fire({
                        title: 'Please select wards and overlay!',
                        icon: 'warning',
                        button: 'Close',
                        className: 'custom-swal',
                    });
                    // Prevent the form from submitting
                    event.preventDefault();
                    return false;
                }
                if (selectedWards && selectedLayer) {
                    var filters = [];
                    $.each(selectedWards, function (index, value) {
                        filters.push('ward=' + value);
                    });

                    mFilter.ward = "INTERSECTS(geom, collectGeometries(queryCollection('" + workspace + ":wards_layer', 'geom', '" + filters.join(' OR ') + "')))";

                    $.each(mLayer, function (key, value) {
                        if (selectedLayer == key) {
                            addFilterToLayer(key, 'ward');
                        } else {
                            removeFilterFromLayer(key, 'ward');
                        }
                    });

                    showLayer('wards_layer');
                } else {
                    mFilter.ward = '';
                    $.each(mLayer, function (key, value) {
                        removeFilterFromLayer(key, 'ward');
                    });
                }

                updateAllCQLFiltersParams();
                if (selectedLayer) {
                    showLayer(selectedLayer);
                }
                return false;
            });

            // Add handler to ward filter clear button
            $('#ward_clear_button').click(function () {
                $('#ward_form').submit();
                $('#ward_overlay').val('');
                $('#ward').multipleSelect('uncheckAll');

            });


            // Add handler to tax due buildings filer form submit
            $('#tax_due_buildings_form').submit(function () {
                var selectedWards = $('#ward_tax_due').val();
                var selectedTaxZones = $('#tax_zone_tax_due').val();

                if (eLayer.building_markers) {
                    eLayer.building_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('building_markers', 'Building Markers', layer);
                }

                // showExtraLayer('building_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/due-buildings-ward-taxzone") }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {selectedWards: selectedWards, selectedTaxZones: selectedTaxZones, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].lat && data[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data[i].long), parseFloat(data[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/building-green.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.building_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('buildings_layer');
                        zoomToCity();
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });

                return false;
            });
            // Add handler to wardtaxzone filter clear button
            $('#wardtaxzone_clear_button').click(function () {
                $('#ward_tax_due').multipleSelect('uncheckAll');
                $('#tax_zone_tax_due').multipleSelect('uncheckAll');
                $('#tax_due_buildings_form').submit();
            });



            // Add handler to export ward filter to csv button
            $('#export_ward_filter_csv').click(function (e) {
                e.preventDefault();
                exportWardFilter('csv');
            });


            // Add handler to export ward filter to kml button
            $('#export_ward_filter_kml').click(function (e) {
                e.preventDefault();
                exportWardFilter('kml');
            });

            // Add handler to export ward filter to shp button
            $('#export_ward_filter_shp').click(function (e) {
                e.preventDefault();
                exportWardFilter('shp');
            });

            function exportWardFilter(format) {
                if (['csv', 'kml', 'shp'].indexOf(format) == -1) {
                    return;
                }

                var selectedWards = $('#ward').val();
                var selectedLayer = $('#ward_overlay').val();
            if (!selectedWards || selectedWards.length === 0 || !selectedLayer || selectedLayer.length === 0) {

                    Swal.fire({
                        title: `Please select wards and overlay!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                }

                var filters = [];
                $.each(selectedWards, function (index, value) {
                    filters.push('ward=' + value);
                });

                var outputFormat;
                if (format == 'csv') {
                    outputFormat = 'CSV';
                } else if (format == 'kml') {
                    outputFormat = 'KML';
                } else {
                    outputFormat = 'SHAPE-ZIP';
                }

                var exportLink = gurl_wfs + "?request=GetFeature&service=WFS&version=1.0.0&authkey=" + authkey + "&typeName=" + workspace + ":" + selectedLayer + "&CQL_FILTER=deleted_at is null AND INTERSECTS(geom, collectGeometries(queryCollection('" + workspace + ":wards_layer', 'geom', '" + filters.join(' OR ') + "')))&outputFormat=" + outputFormat;

                if (outputFormat == 'SHAPE-ZIP') {
                    exportLink += '&format_options=filename:' + 'export_imis_' + moment().format('YYYYMMDD_HHmmss') + '.zip';
                }
                window.open(exportLink);
            }

            // Add handler to tax zone filer form submit
            $('#tax_zone_form').submit(function () {
                var selectedTaxZones = $('#tax_zone').val();
                var selectedLayer = $('#tax_zone_overlay').val();
                if (selectedTaxZones && selectedLayer) {
                    var filters = [];
                    $.each(selectedTaxZones, function (index, value) {
                        filters.push("taxzoneid=''" + value + "''");
                    });

                    mFilter.tax_zone = "INTERSECTS(geom, collectGeometries(queryCollection('" + workspace + ":jhe_taxzone', 'geom', '" + filters.join(' OR ') + "')))";

                    $.each(mLayer, function (key, value) {
                        if (selectedLayer == key) {
                            addFilterToLayer(key, 'tax_zone');
                        } else {
                            removeFilterFromLayer(key, 'tax_zone');
                        }
                    });

                    showLayer('jhe_taxzone');
                } else {
                    mFilter.tax_zone = '';
                    $.each(mLayer, function (key, value) {
                        removeFilterFromLayer(key, 'tax_zone');
                    });
                }

                updateAllCQLFiltersParams();
                if (selectedLayer) {
                    showLayer(selectedLayer);
                }
                return false;
            });

            // Add handler to tax zone filter clear button
            $('#tax_zone_clear_button').click(function () {
                $('#tax_zone').multipleSelect('uncheckAll');
                $('#tax_zone_overlay').val('');
                $('#tax_zone_form').submit();
            });

            // Add handler to export tax zone filter to csv button
            $('#export_tax_zone_filter_csv').click(function (e) {
                e.preventDefault();
                exportTaxZoneFilter('csv');
            });

            // Add handler to export tax zone filter to kml button
            $('#export_tax_zone_filter_kml').click(function (e) {
                e.preventDefault();
                exportTaxZoneFilter('kml');
            });

            // Add handler to export tax zone filter to shp button
            $('#export_tax_zone_filter_shp').click(function (e) {
                e.preventDefault();
                exportTaxZoneFilter('shp');
            });

            function exportTaxZoneFilter(format) {
                if (['csv', 'kml', 'shp'].indexOf(format) == -1) {
                    return;
                }

                var selectedTaxZones = $('#tax_zone').val();
                var selectedLayer = $('#tax_zone_overlay').val();
                if (!selectedTaxZones || !selectedLayer) {
                    Swal.fire({
                        title: `Please select tax zones and overlay!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                }

                var filters = [];
                $.each(selectedTaxZones, function (index, value) {
                    filters.push("taxzoneid=''" + value + "''");
                });

                var outputFormat;
                if (format == 'csv') {
                    outputFormat = 'CSV';
                } else if (format == 'kml') {
                    outputFormat = 'KML';
                } else {
                    outputFormat = 'SHAPE-ZIP';
                }
                var exportLink = gurl_wfs + "?request=GetFeature&service=WFS&version=1.0.0&authkey=" + authkey + "&typeName=" + workspace + ":" + selectedLayer + "&CQL_FILTER=deleted_at is null AND INTERSECTS(geom, collectGeometries(queryCollection('" + workspace + ":jhe_taxzone', 'geom', '" + filters.join(' OR ') + "')))&outputFormat=" + outputFormat;

                if (outputFormat == 'SHAPE-ZIP') {
                    exportLink += '&format_options=filename:' + 'export_imis_' + moment().format('YYYYMMDD_HHmmss') + '.zip';
                }

                window.open(exportLink);
            }

            // Add handler to export building tax payment to csv button
            $('#export_building_tax_filter_csv').click(function (e) {
                e.preventDefault();
                exportBuildingTaxFilter('csv');
            });

            // Add handler to export building tax payment filter to kml button
            $('#export_building_tax_filter_kml').click(function (e) {
                e.preventDefault();
                exportBuildingTaxFilter('kml');
            });

            // Add handler to export building tax payment filter to shp button
            $('#export_building_tax_filter_shp').click(function (e) {
                e.preventDefault();
                exportBuildingTaxFilter('shp');
            });

            function exportBuildingTaxFilter(format) {
                if (['csv', 'kml', 'shp'].indexOf(format) == -1) {
                    return;
                }

                var checkedList = [];
                $('#building_tax_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                    checkedList.push("due_year = " + $(this).attr('name') + "");
                });

                if (checkedList.length > 0) {
                    checklistparam = checkedList.join(' OR ');
                } else {
                    Swal.fire({
                        title: `Please check one or more options!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                }


                var outputFormat;
                if (format == 'csv') {
                    outputFormat = 'CSV';
                } else if (format == 'kml') {
                    outputFormat = 'KML';
                } else {
                    outputFormat = 'SHAPE-ZIP';
                }


                var exportLink = gurl_wfs + "?request=GetFeature&service=WFS&version=1.0.0&authkey=" + authkey + "&typeName=" + workspace + ":buildings_tax_status_layer&CQL_FILTER=deleted_at is null AND " + checklistparam + "&PROPERTYNAME=bin,road_code,due_year,ward,geom&outputFormat=" + outputFormat;
                if (outputFormat == 'SHAPE-ZIP') {
                    exportLink += '&format_options=filename:' + 'export_imis_' + moment().format('YYYYMMDD_HHmmss') + '.zip';
                }
                window.open(exportLink);
            }

            // Add handler to export water supply payment to csv button
            $('#export_water_supply_filter_csv').click(function (e) {
                e.preventDefault();
                exportWaterSupplyFilter('csv');
            });

            // Add handler to export water supply payment to kml button
            $('#export_water_supply_filter_kml').click(function (e) {
                e.preventDefault();
                exportWaterSupplyFilter('kml');
            });

            // Add handler to export water supply payment to shp button
            $('#export_water_supply_filter_shp').click(function (e) {
                e.preventDefault();
                exportWaterSupplyFilter('shp');
            });

            function exportWaterSupplyFilter(format) {
                if (['csv', 'kml', 'shp'].indexOf(format) == -1) {
                    return;
                }

                var checkedList = [];
                $('#water_supply_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                    checkedList.push("due_year = " + $(this).attr('name') + "");
                });

                if (checkedList.length > 0) {
                    checklistparam = checkedList.join(' OR ');
                } else {
                    Swal.fire({
                        title: `Please check one or more options!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return;
                }


                var outputFormat;
                if (format == 'csv') {
                    outputFormat = 'CSV';
                } else if (format == 'kml') {
                    outputFormat = 'KML';
                } else {
                    outputFormat = 'SHAPE-ZIP';
                }


                var exportLink = gurl_wfs + "?request=GetFeature&service=WFS&version=1.0.0&authkey=" + authkey + "&typeName=" + workspace + ":buildings_water_payment_status_layer&CQL_FILTER=deleted_at is null AND " + checklistparam + "&PROPERTYNAME=tax_code,bin,road_code,due_year,ward,geom&outputFormat=" + outputFormat;
                if (outputFormat == 'SHAPE-ZIP') {
                    exportLink += '&format_options=filename:' + 'export_imis_' + moment().format('YYYYMMDD_HHmmss') + '.zip';
                }

                window.open(exportLink);
            }

            // Add handler to building tax payment filter clear button
            $('#building_tax_payment_clear_button').click(function () {
                $('#building_tax_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                    $(this).prop( "checked", false )
                });
            });
            // Add handler to building tax payment filter clear button
            $('#water_supply_payment_clear_button').click(function () {
                $('#water_supply_payment_checkbox_container input[type=checkbox]:checked').each(function () {
                    $(this).prop( "checked", false )
                });
            })


            $('#application_date_form').submit(function () {
                var date = $('#application_date_field').val();
                if (!date) {
                    Swal.fire({
                        title: `Please select a date!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else {
                    var message = 'Number of applications on ' + date + ': ';

                    displaySelectedDateApplications(date, message);
                }

                return false;
            });


            $('#application_not_tp_date_form').submit(function () {
                var date = $('#application_not_tp_date_field').val();
                if (!date) {
                    Swal.fire({
                        title: `Please select a date!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else {
                    var message = 'Number of applications on ' + date + ': ';

                    displaySelectedDateApplicationsNotTP(date, message);
                }

                return false;
            });

            // Display markers on applications
            function displaySelectedDateApplicationsNotTP(startDate, message) {
                if (eLayer.application_notTP_markers) {
                    eLayer.application_notTP_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTPDate_containment_markers) {
                    eLayer.application_NotTPDate_containment_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTP_containment_markers) {
                    eLayer.application_NotTP_containment_markers.layer.getSource().clear();
                }

                var layer = new ol.layer.Vector({
                    // visible: false,
                    source: new ol.source.Vector()
                });

                addExtraLayer('application_NotTPDate_containment_markers', 'Building Markers', layer);


                // showExtraLayer('application_NotTPDate_containment_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/application-not-tp-on-date") }}' + '/' + startDate;
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        var service_provider_legends = '';
                        var service_provider_markers = {};
                        data["service_providers"].forEach(function(service_provider,index){
                            service_provider_legends += "<tr>" +
                                '<td style="vertical-align: top;">' +
                                '<img src="{{ url("/")}}/img/marker-' + markerColors[index] + '.png"></td>' +
                                '<td> - ' + service_provider.company_name + '</td>'+
                                '</tr>';
                            service_provider_markers[service_provider.company_name]=markerColors[index];
                        });
                        $('#service_providers_legends').html(service_provider_legends);

                        for (var i = 0; i < data["data"].length; i++) {
                            if (data["data"][i].lat && data["data"][i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data["data"][i].long), parseFloat(data["data"][i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: data["data"][i].application_id,
                                    house_number: data["data"][i].house_number,
                                    application_date: data["data"][i].application_date,
                                    service_provider: data["data"][i].service_provider,
                                    emptying_date: data["data"][i].emptying_date
                                });

                                var filename = 'application.png';

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/marker-' + service_provider_markers[data["data"][i].service_provider] + '.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.application_NotTPDate_containment_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }

        //Display application based on selected year and months
            $('#find_application_yearmonth').submit(function () {

                var applicaion_year = $('#applicaion_year').val();
                var application_month = $('#application_month').val();
                if (!applicaion_year) {
                    Swal.fire({
                        title: `Please select a year!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return false;
                } else if (!applicaion_year && !application_month) {
                    Swal.fire({
                        title: `Please select a month!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return false;
                } else {
                    var message = 'Number of applications on ' + applicaion_year + ' ' + application_month + ': ';
                    displaySelectedYearMonthApplications(applicaion_year, application_month, message);
                }

                return false;
            });


            // Display markers on applications on selected years and months
            function displaySelectedYearMonthApplications(year, month, message) {
                if (eLayer.application_containment_markers) {
                    eLayer.application_containment_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('application_containment_markers', 'Building Markers', layer);
                }

                // showExtraLayer('application_containment_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/application-containments-year-month") }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {year: year, month: month, "_token": "{{ csrf_token() }}"},
                    success: function (dataResponse) {
                        for (var i = 0; i < dataResponse.length; i++) {
                            if (dataResponse[i].lat && dataResponse[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse[i].long), parseFloat(dataResponse[i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: dataResponse[i].application_id,
                                    house_number: dataResponse[i].house_number,
                                    application_date: dataResponse[i].application_date,
                                    service_provider: dataResponse[i].service_provider,
                                    emptying_date: dataResponse[i].emptying_date
                                });

                                var filename = 'application.png';

                                if (dataResponse[i].sludge_collection_status) {
                                    filename = 'application-sludge-collection.png';
                                }
                                else if (dataResponse[i].feedback_status) {
                                    filename = 'application-feedback.png';
                                } else if (dataResponse[i].emptying_status) {
                                    filename = 'application-emptying.png';
                                } else if (dataResponse[i].assessment_status) {
                                    //Hiding Assessment so assigning same marker as application
                                    filename = 'application.png';
                                }

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/' + filename
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.application_containment_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }


            $('#find_application_not_tp_yearmonth').submit(function () {

                var applicaion_not_tp_year = $('#applicaion_not_tp_year').val();
                var application_not_tp_month = $('#application_not_tp_month').val();
                if (!applicaion_not_tp_year) {
                    Swal.fire({
                        title: `Please select a year!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return false;
                } else if (!applicaion_not_tp_year && !application_not_tp_month) {
                    Swal.fire({
                        title: `Please select a month!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                    return false;
                } else {
                    var message = 'Number of applications on ' + applicaion_not_tp_year + ' ' + application_not_tp_month + ': ';
                    displaySelectedYearMonthApplicationsNotTP(applicaion_not_tp_year, application_not_tp_month, message);
                }

                return false;
            });

            // Display markers on applications not reached on treatment plants on selected years and months
            function displaySelectedYearMonthApplicationsNotTP(year, month, message) {
                if (eLayer.application_notTP_markers) {
                    eLayer.application_notTP_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTPDate_containment_markers) {
                    eLayer.application_NotTPDate_containment_markers.layer.getSource().clear();
                }
                if (eLayer.application_NotTP_containment_markers) {
                    eLayer.application_NotTP_containment_markers.layer.getSource().clear();
                }

                var layer = new ol.layer.Vector({
                    // visible: false,
                    source: new ol.source.Vector()
                });

                addExtraLayer('application_NotTP_containment_markers', 'Building Markers', layer);


                // showExtraLayer('application_NotTP_containment_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/application-not-tp-containments-year-month") }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {year: year, month: month, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        var service_provider_legends = '';
                        var service_provider_markers = {};
                        data["service_providers"].forEach(function(service_provider,index){
                            service_provider_legends += "<tr>" +
                                '<td style="vertical-align: top;">' +
                                '<img src="{{ url("/")}}/img/marker-' + markerColors[index] + '.png"></td>' +
                                '<td> - ' + service_provider.company_name + '</td>'+
                                '</tr>';
                            service_provider_markers[service_provider.company_name]=markerColors[index];
                        });
                        $('#service_providers_legends').html(service_provider_legends);

                        for (var i = 0; i < data["data"].length; i++) {
                            if (data["data"][i].lat && data["data"][i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(data["data"][i].long), parseFloat(data["data"][i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: data["data"][i].application_id,
                                    house_number: data["data"][i].house_number,
                                    application_date: data["data"][i].application_date,
                                    service_provider: data["data"][i].service_provider,
                                    emptying_date: data["data"][i].emptying_date
                                });

                                var filename = 'application.png';

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/marker-' + service_provider_markers[data["data"][i].service_provider] + '.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.application_NotTP_containment_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (data) {
                        displayAjaxError();
                    }
                });
            }

            // Display markers on applications
            function displaySelectedDateApplications(startDate, message) {
                if (eLayer.application_containment_markers) {
                    eLayer.application_containment_markers.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('application_containment_markers', 'Building Markers', layer);
                }

                // showExtraLayer('application_containment_markers');

                displayAjaxLoader();
                var url = '{{ url("maps/application-on-date") }}' + '/' + startDate;
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function (dataResponse) {
                        for (var i = 0; i < dataResponse.length; i++) {
                            if (dataResponse[i].lat && dataResponse[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse[i].long), parseFloat(dataResponse[i].lat)], 'EPSG:4326', 'EPSG:3857')),
                                    application_id: dataResponse[i].application_id,
                                    house_number: dataResponse[i].house_number,
                                    application_date: dataResponse[i].application_date,
                                    service_provider: dataResponse[i].service_provider,
                                    emptying_date: dataResponse[i].emptying_date
                                });

                                var filename = 'application.png';

                                if (dataResponse[i].sludge_collection_status) {
                                    filename = 'application-sludge-collection.png';
                                }
                                else if (dataResponse[i].feedback_status) {
                                    filename = 'application-feedback.png';
                                } else if (dataResponse[i].emptying_status) {
                                    filename = 'application-emptying.png';
                                } else if (dataResponse[i].assessment_status) {
                                    //Hiding Assessment so assigning same marker as application
                                    filename = 'application.png';
                                }

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/' + filename
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.application_containment_markers.layer.getSource().addFeature(feature);
                            }
                        }

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }

            $('#proposed_emptying_days_form').submit(function () {
                var days = Number($('#proposed_emptying_days').val());
                if (!days) {
                    Swal.fire({
                        title: `Please enter number of days!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else if (!Number.isInteger(days) || days < 0) {
                    Swal.fire({
                        title: `Invalid input for number of days!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else {
                    var startDate = moment().format('YYYY-MM-DD');
                    var endDate = moment().add(days, 'days').format('YYYY-MM-DD');
                    var message = 'Number of containments proposed to be emptied on next ' + days + ' days: ';

                    displayProposedEmptyingContainments(startDate, endDate, message);
                }

                return false;
            });


            $('#proposed_emptying_week_form').submit(function () {
                var startDate = moment().format('YYYY-MM-DD');
                var endDate = moment().add(7, 'days').format('YYYY-MM-DD');
                var message = 'Number of containments proposed to be emptied next week: ';

                displayProposedEmptyingContainments(startDate, endDate, message);

                return false;
            });

            $('#proposed_emptying_date_form').submit(function () {
                var date = $('#proposed_emptying_date').val();
                if (!date) {
                    Swal.fire({
                        title: `Please select a date!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    });
                } else {
                    var message = 'Number of containments proposed to be emptied on ' + date + ': ';

                    displayProposedEmptyingContainments(date, date, message);
                }

                return false;
            });

            // Display markers on containments proposed to be emptied
            function displayProposedEmptyingContainments(startDate, endDate, message) {
                if (eLayer.proposed_emptying_containments) {
                    eLayer.proposed_emptying_containments.layer.getSource().clear();
                } else {
                    var layer = new ol.layer.Vector({
                        // visible: false,
                        source: new ol.source.Vector()
                    });

                    addExtraLayer('proposed_emptying_containments', 'Containment Markers', layer);
                }

                // showExtraLayer('proposed_emptying_containments');

                displayAjaxLoader();
                var url = '{{ url("maps/proposed-emptying-containments") }}' + '/' + startDate + '/' + endDate;

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (dataResponse) {
                        for (var i = 0; i < dataResponse.length; i++) {
                            if (dataResponse[i].lat && dataResponse[i].long) {
                                var feature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse[i].long), parseFloat(dataResponse[i].lat)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var style = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/containment-blue.png'
                                    })
                                });

                                feature.setStyle(style);

                                eLayer.proposed_emptying_containments.layer.getSource().addFeature(feature);
                            }
                        }

                        var html = message + dataResponse.length;
                        $("#proposed_emptying_modal .modal-body").html(html);
                        $('#proposed_emptying_modal').modal('show');

                        removeAjaxLoader();

                        showLayer('containments_layer');
                        zoomToCity();
                    },
                    error: function (dataResponse) {
                        displayAjaxError();
                    }
                });
            }


            // Add handler to water logging area filer form submit
            $('#watlog_form').submit(function () {
                var selectedLayers = $('#watlog_overlay').val();
                if (selectedLayers) {
                    mFilter.watlog = "INTERSECTS(geom, collectGeometries(queryCollection('" + workspace + ":waterbodys_layer', 'geom', 'INCLUDE')))"

                    $.each(mLayer, function (key, value) {
                        if (selectedLayers.indexOf(key) != -1) {
                            addFilterToLayer(key, 'watlog');
                        } else {
                            removeFilterFromLayer(key, 'watlog');
                        }
                    });

                    showLayer('waterbodys_layer');
                } else {
                    mFilter.watlog = '';
                    $.each(mLayer, function (key, value) {
                        removeFilterFromLayer(key, 'watlog');
                    });
                }

                updateAllCQLFiltersParams();
                if (selectedLayers) {
                    $.each(selectedLayers, function (index, value) {
                        showLayer(value);
                    });
                }
                return false;
            });


            function updateAllCQLFiltersParams() {
                $.each(mLayer, function (key, value) {
                    updateCQLFilterParams(key);
                });
            }

            function updateCQLFilterParams(layer) {

                if (mLayer[layer]) {

                    var cqlFilters = [];
                    $.each(mLayer[layer].filters, function (index, value) {
                        if (mFilter[value]) {
                            cqlFilters.push(mFilter[value]);
                        }
                    });
                    var cql_filter = cqlFilters.length > 0 ? cqlFilters.join(" AND ") : 'deleted_at is null';
                    mLayer[layer].layer.get('source').updateParams({CQL_FILTER: cql_filter});
                }
            }

            function handleZoomToExtent(layer, field, val, showMarker, callback) {
                var url = '{{ url("maps/extent") }}' + '/' + layer + '/' + field + '/' + val;

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function (dataResponse) {
                        var extent = ol.proj.transformExtent([parseFloat(dataResponse.xmin), parseFloat(dataResponse.ymin), parseFloat(dataResponse.xmax), parseFloat(dataResponse.ymax)], 'EPSG:4326', 'EPSG:3857');
                        map.getView().fit(extent);

                        if (showMarker) {
                            if (dataResponse.long && dataResponse.lat) {
                                if (!eLayer.markers) {
                                    var markerLayer = new ol.layer.Vector({
                                        // visible: false,
                                        source: new ol.source.Vector()
                                    });

                                    addExtraLayer('markers', 'Markers', markerLayer);
                                    // showExtraLayer('markers');
                                }

                                var markerFeature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse.long), parseFloat(dataResponse.lat)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var markerStyle = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/pin-green.png'
                                    })
                                });

                                markerFeature.setStyle(markerStyle);

                                eLayer.markers.layer.getSource().addFeature(markerFeature);

                                map.getView().setCenter(ol.proj.transform([parseFloat(dataResponse.long), parseFloat(dataResponse.lat)], 'EPSG:4326', 'EPSG:3857'));
                                map.getView().setZoom(16);
                            }

                            if (dataResponse.long1 && dataResponse.lat1) {
                                if (!eLayer.markers) {
                                    var markerLayer = new ol.layer.Vector({
                                        // visible: false,
                                        source: new ol.source.Vector()
                                    });

                                    addExtraLayer('markers', 'Markers', markerLayer);
                                    // showExtraLayer('markers');
                                }

                                var markerFeature = new ol.Feature({
                                    geometry: new ol.geom.Point(ol.proj.transform([parseFloat(dataResponse.long1), parseFloat(dataResponse.lat1)], 'EPSG:4326', 'EPSG:3857'))
                                });

                                var markerStyle = new ol.style.Style({
                                    image: new ol.style.Icon({
                                        anchor: [0.5, 1],
                                        src: '{{ url("/")}}/img/pin-purple.png'
                                    })
                                });

                                markerFeature.setStyle(markerStyle);

                                eLayer.markers.layer.getSource().addFeature(markerFeature);
                            }

                            if (dataResponse.geom) {
                                var format = new ol.format.WKT();
                                var feature = format.readFeature(dataResponse.geom, {
                                    dataProjection: 'EPSG:4326',
                                    featureProjection: 'EPSG:3857'
                                });

                                if (feature.getGeometry() instanceof ol.geom.MultiLineString) {
                                    if (!eLayer.markers) {
                                        var markerLayer = new ol.layer.Vector({
                                            // visible: false,
                                            source: new ol.source.Vector()
                                        });

                                        addExtraLayer('markers', 'Markers', markerLayer);
                                        // showExtraLayer('markers');
                                    }

                                    feature.setStyle(new ol.style.Style({
                                        stroke: new ol.style.Stroke({
                                            color: '#ed1f24',
                                            width: 3
                                        }),
                                    }));
                                    eLayer.markers.layer.getSource().addFeature(feature);
                                }
                            }
                        }

                        showLayer(layer);

                        if (callback) {
                            callback();
                        }
                    },
                    error: function (dataResponse) {

                    }
                });
            }

            function updateMapSize() {
                map.updateSize();
                google.maps.event.trigger(gmap, 'resize');
                onCenterChanged();
                onResolutionChanged();
            }

            function checkNumberIfFloat(value) {
                return Number(value) === value && value % 1 !== 0;
             }

             $(document).on('shown.lte.pushmenu collapsed.lte.pushmenu', function () {
                setTimeout(updateMapSize, 300);
            });

            function setSidebarTabContentHeight() {
                // var height = $('#map-right-sidebar').outerHeight() - $('#map-right-sidebar .nav-tabs').outerHeight();
                var height = window.innerHeight - $('#map-right-sidebar .nav-tabs').offset().top - $('#map-right-sidebar .nav-tabs').outerHeight() - $('footer').outerHeight();
                $('#map-right-sidebar .tab-content').height(height);
            }

            // Fix min-height of content-wrapper on window load
            $(window).on('load', function () {
                $(window).trigger('resize');
                updateMapSize();
            });

            $(window).on('resize', function () {
                setSidebarTabContentHeight();
            });
        });
    </script>
@endpush
