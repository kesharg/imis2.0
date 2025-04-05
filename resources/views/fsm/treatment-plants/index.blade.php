<!-- Last Modified Date: 09-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@push('style')
    <style type="text/css">
        .dataTables_filter {
            display: none;
        }
    </style>
@endpush
@section('title', $page_title)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('Add Treatment Plant')
                        <a href="{{ action('Fsm\TreatmentPlantController@create') }}" class="btn btn-info">Add Treatment
                            Plant</a>
                    @endcan
                    @can('Export Treatment Plants to Excel')
                        <a href="{{ action('Fsm\TreatmentPlantController@export') }}" id="export" class="btn btn-info">Export to
                            CSV</a>
                    @endcan
                    @can('Export Treatment Plants to Shape')
                        <a href="#" id="export-shp" class="btn btn-info">Export to Shape File</a>
                    @endcan
                    @can('Export Treatment Plants to KML')
                        <a href="#" id="export-kml" class="btn btn-info">Export to KML</a>
                    @endcan
                    <a href class="btn btn-info float-right" data-toggle="collapse" data-target="#collapseFilter"
                        aria-expanded="false" aria-controls="collapseFilter">Show Filter</a>

                </div><!-- /.box-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionFilter">
                                <div class="accordion-item">
                                    <div id="collapseFilter" class="collapse" aria-labelledby="filter"
                                        data-parent="#accordionFilter">
                                        <div class="accordion-body">
                                            <form class="form-horizontal" id="filter-form">
                                                <div class="form-group row">

                                                    <label for="name" class="col-md-2 col-form-label ">Name</label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="name" placeholder= "Name" />
                                                    </div>


                                                    <label for="status" class="col-md-2 col-form-label ">Status</label>
                                                    <div class="col-md-2">
                                                        <select class="form-control" id="status"
                                                            name="status">
                                                            <option value="">Status</option>
                                                            <option value="TRUE">Operational</option>
                                                            <option value="FALSE">Not Operational</option>

                                                        </select>
                                                    </div>

                                                    <label for="type" class="col-md-2 col-form-label ">Type</label>
                                                    <div class="col-md-2">
                                                        <select class="form-control" id="type" name="type">
                                                            <option value="">Type</option>
                                                            @foreach ($tpType as $key => $value)
                                                                <option value="{{ $key }}">{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="form-group row">
                                                    <label for="caretaker_name" class="col-md-2 col-form-label ">Caretaker
                                                        Name
                                                    </label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="caretaker_name" placeholder= "Caretaker Name"/>

                                                    </div>

                                                    <label for="capacity_per_day" class="col-md-2 col-form-label ">Capacity
                                                        per Day
                                                    </label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="capacity_per_day" placeholder= "Capacity per Day"/>
                                                    </div>

                                                    <label for="caretaker_number" class="col-md-2 col-form-label ">Caretaker
                                                        Number
                                                    </label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="caretaker_number" placeholder= "Caretaker Number"/>
                                                    </div>


                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" class="btn btn-info ">Filter</button>
                                                    <button type="reset" id="reset-filter" class="btn btn-info">Reset</button>

                                                </div>
                                                <div class="clearfix"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="overflow: auto; width: 100%;">
                            <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Name</th>
                                        <th>Capacity per Day (m³/Day)</th>
                                        <th>Caretaker Name</th>
                                        <th>Caretaker Number</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->


                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="message"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @stop

            @push('scripts')
                <script>
                    $(function() {
                        var dataTable = $('#data-table').DataTable({
                            bFilter: false,
                            processing: true,
                            serverSide: true,
                            scrollCollapse: true,
                            ajax: {
                                url: '{!! url('fsm/treatment-plants/data') !!}',
                                data: function(d) {

                                    d.name = $('#name').val();
                                    d.capacity_per_day = $('#capacity_per_day').val();
                                    d.caretaker_name = $('#caretaker_name').val();
                                    d.caretaker_number = $('#caretaker_number').val();
                                    d.status = $('#status').val();
                                    d.type = $('#type').val();

                                }
                            },
                            columns: [

                                {
                                    data: 'name',
                                    name: 'name'
                                },
                                {
                                    data: 'capacity_per_day',
                                    name: 'capacity_per_day'
                                },
                                {
                                    data: 'caretaker_name',
                                    name: 'caretaker_name'
                                },
                                {
                                    data: 'caretaker_number',
                                    name: 'caretaker_number'
                                },
                                {
                                    data: 'status',
                                    name: 'status',

                                },
                                {
                                    data: 'type',
                                    name: 'type',

                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ],
                            order: [
                                [0, 'desc']
                            ]
                        }).on('draw', function() {
                            $('.delete').on('click', function(e) {
                                var form = $(this).closest("form");
                                event.preventDefault();
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You won't be able to revert this!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {


                                        Swal.fire(
                                            'Deleted!',
                                            'Treatment Plant will be deleted.',
                                            'success'
                                        ).then((willDelete) => {
                                            if (willDelete) {
                                                form.submit();
                                            }
                                        })
                                    }
                                })

                            });

                        });
                        resetDataTable(dataTable);
                        // var trtpltid = '',
                        var name = '',
                            capacity_per_day = '',
                            caretaker_name = '',
                            caretaker_number = '',
                            status = '',
                            type = '';

                        $('#filter-form').on('submit', function(e) {

                            name = $('#name').val();
                            capacity_per_day = $('#capacity_per_day').val();
                            caretaker_name = $('#caretaker_name').val();
                            caretaker_number = $('#caretaker_number').val();
                            status = $('#status').val();
                            type = $('#type').val();

                            e.preventDefault();
                            dataTable.draw();

                        });



                        $("#export").on("click", function(e) {

                            e.preventDefault();
                            var searchData = $('input[type=search]').val();
                            // var trtpltid = $('#trtpltid').val();
                            name = $('#name').val();
                            capacity_per_day = $('#capacity_per_day').val();
                            caretaker_name = $('#caretaker_name').val();
                            caretaker_number = $('#caretaker_number').val();
                            status = $('#status').val();
                            type = $('#type').val();
                            window.location.href = "{!! url('fsm/treatment-plants/export?searchData=') !!}" + searchData +
                                "&name=" + name +
                                "&caretaker_name=" + caretaker_name + "&caretaker_number=" +
                                caretaker_number + "&capacity_per_day=" + capacity_per_day +
                                "&status=" + status + "&type=" + type;
                        });

                        $("#export-shp").on("click", function(e) {
                            e.preventDefault();
                            var cql_param = getCQLParams();
                            window.location.href =
                                "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:treatmentplants_layer+&CQL_FILTER=" +
                                cql_param + " &outputFormat=SHAPE-ZIP";

                        })

                        $("#export-kml").on("click", function(e) {
                            e.preventDefault();
                            var cql_param = getCQLParams();
                            window.location.href =
                                "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:treatmentplants_layer+&CQL_FILTER=" +
                                cql_param + " &outputFormat=KML";
                        });

                        function getCQLParams() {

                            var name = $('#name').val();
                            var caretaker_name = $('#caretaker_name').val();
                            var caretaker_number = $('#caretaker_number').val();
                            var capacity_per_day = $('capacity_per_day').val();
                            var status = $('#status').val();
                            var cql_param = "deleted_at IS NULL";

                            if (name) {
                                cql_param += " AND name ILIKE '%" + name + "%'";
                            }
                            if (caretaker_number) {
                                cql_param += " AND caretaker_number ='" + caretaker_number + "'";

                            }
                            if (caretaker_name) {
                                cql_param += " AND caretaker_name ILIKE '%" + caretaker_name + "%'";
                            }
                            if (capacity_per_day) {
                                cql_param += " AND capacity_per_day ='%" + capacity_per_day + "'";
                            }
                            if (status) {
                                cql_param += " AND status ='" + status + "'";
                            }

                            return encodeURI(cql_param);
                        }

                        $('.date, #date_from, #date_to').datepicker({

                            format: 'yyyy-mm-dd',
                            todayHighlight: true

                        });

                        $('.date, #date_from, #date_to').focus(function() {
                            $(this).blur();
                        });
                    });
                </script>
            @endpush
