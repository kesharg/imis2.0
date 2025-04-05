@extends('layouts.dashboard')
@section('title', 'Buildings')


@section('content')
    <div class="modal fade" id="containmentsModal" tabindex="-1" role="dialog" aria-labelledby="containmentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containmentsModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            @can('Add Building Structure')
                <a href="{{ action('BuildingInfo\BuildingController@create') }}" class="btn btn-info">Add Building</a>
            @endcan
            @can('Export Building Structures')
                <a href="{{ action('BuildingInfo\BuildingController@export') }}" id="export" class="btn btn-info">Export to
                    CSV</a>
            @endcan
            @can('Export Building Structures')
                <a href="#" id="export-shp" class="btn btn-info">Export to Shape File</a>
            @endcan
            @can('Export Building Structures')
                <a href="#" id="export-kml" class="btn btn-info">Export to KML</a>
            @endcan

            <a href="#" class="btn btn-info float-right" id="headingOne" type="button" data-toggle="collapse"
                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Show Filter
            </a>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form class="form-horizontal" id="filter-form">
                                        <div class="form-group row">
                                            <label for="bin_text" class="control-label col-md-2">House Number </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="bin_text"
                                                    placeholder="Filter by House Number" />
                                            </div>
                                            <label for="structype_select" class="control-label col-md-2">Structure
                                                Type</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="structype_select">
                                                    <option value="">All Types</option>
                                                    @foreach ($structure_type as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="ward_select" class="control-label col-md-2">Ward</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="ward_select">
                                                    <option value="">Select Ward</option>
                                                    @foreach ($ward as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="functional_use_select" class="control-label col-md-2">Functional
                                                Use</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="functional_use_select">
                                                    <option value="">All Functional Use</option>
                                                    @foreach ($functional_use as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="road_code" class="control-label col-md-2">Road Code</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="road_code">
                                                </select>
                                            </div>
                                            <label for="owner_name" class="control-label col-md-2">Owners</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="owner_name"
                                                    placeholder="Filter by Owners" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="toilet" class="control-label col-md-2">Toilet</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="toilet">
                                                    <option value="">All</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>

                                            <label for="sanitation_system_id"
                                                class="control-label col-md-2">Sanitation Systems</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="sanitation_system_id">
                                                    <option value="">All</option>
                                                    @foreach ($sanitation_systems as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="watersourc" class="control-label col-md-2">Water Source</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="watersourc">
                                                    <option value="">All Water Source</option>
                                                    @foreach ($water_sources as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">


                                            <label for="well_prese" class="control-label col-md-2">Well Presence</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="well_prese">
                                                    <option value="">All</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <label for="emptying_status" class="control-label col-md-2">Emptying
                                                Status</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="emptying_status">
                                                    <option value="">All</option>
                                                    <option value="true">Yes</option>
                                                    <option value="false">No</option>
                                                </select>
                                            </div>

                                            <label for="floor_count" class="control-label col-md-2">Floor Count </label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="floor_count">
                                                    <option value="">Floor Count</option>
                                                    @foreach ($floorCount as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-info">Filter</button>
                                            <button type="reset" id="reset-filter" class="btn btn-info">Reset</button>
                                        </div>
                                    </form>
                                </div>
                                <!--- accordion body!-->
                            </div>
                            <!--- collapseOne!-->
                        </div>
                        <!--- accordion item!-->
                    </div>
                    <!--- accordion !-->
                </div>
                <!---col!-->
            </div>
            <!--- row !-->
        </div>
        <!--- card body !-->
        <div class="card-body">

            <table id="data-table" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>@lang('BIN')</th>
                        <th>@lang('House Number')</th>
                        <th>@lang('Road Code')</th>
                        <th>@lang('Ward')</th>
                        <th>@lang('Structure Type')</th>
                        <th>@lang('Number of Floor')</th>
                        <th>@lang('Toilet Presence')</th>
                        <th>@lang('Sanitation Systems')</th>
                        <th>@lang('Owner Name')</th>
                        <th>@lang('Actions')</th>

                    </tr>
                </thead>
            </table>
        </div>

    </div> <!-- /.card -->

@stop


@push('scripts')
    <script>
        $.fn.dataTable.ext.errMode = 'throw';
        $(function() {

            var bin = '';
            var structype = '';
            var ward = '';
            var functional_use = '';
            var roadcd = '';
            var ownername = '';
            var ownername = '';
            var sanitation_system_id = '';
            var floor_count ='';

            var dataTable = $('#data-table').DataTable({

                bFilter: false,
                processing: true,
                serverSide: true,
                stateSave: false,
                scrollX: true,
                "stateDuration": 1800, // In seconds; keep state for half an hour
                retrieve: true,
                ajax: {
                    url: '{!! url('building-info/buildings/data') !!}',
                    data: function(d) {
                        d.bin = $('#bin_text').val();
                        d.structype = $('#structype_select').val();
                        d.ward = $('#ward_select').val();
                        d.functional_use = $('#functional_use_select').val();
                        d.roadcd = $('#road_code').val();
                        d.toilet = $('#toilet').val();
                        d.defecation = $('#defecation').val();
                        d.toiletconn = $('#toiletconn').val();
                        d.watersourc = $('#watersourc').val();
                        d.well_prese = $('#well_prese').val();
                        d.ownername = $('#owner_name').val();
                        d.emptying_status = $('#emptying_status').val();
                        d.sanitation_system_id = $('#sanitation_system_id').val();
                        d.floor_count = $('#floor_count').val();

                    }
                },
                columns: [
                    {
                        data: 'bin',
                        name: 'bin'
                    },
                    {
                        data: 'house_number',
                        name: 'house_number'
                    },
                    {
                        data: 'road_code',
                        name: 'road_code'
                    },
                    {
                        data: 'ward',
                        name: 'ward'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'floor_count',
                        name: 'floor_count'
                    },
                    {
                        data: 'toilet_status',
                        name: 'toilet_status'
                    },
                    {
                        data: 'sanitation_system_id',
                        name: 'sanitation_system_id'
                    },
                    {
                        data: 'owner_name',
                        name: 'owner_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
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
                            form.submit();
                            Swal.fire(
                                'Deleted!',
                                'Building will be deleted.',
                                'Success'
                            )
                        }
                    })
                });
            });



            $(".sidebar-toggle").on("click", function() {
                dataTable.columns.adjust().draw(false);
            });


            var bin = '',
                structype = '',
                ward = '',
                roadcd = '',
                ownername = '';
            var toilet = '';
            var defecation = '';
            var toiletconn = '';
            var watersourc = '';
            var well_prese = '';
            var emptying_status = '';
            var sanitation_system_id = '';
            var floor_count ='';

            $('#filter-form').on('submit', function(e) {
                var binB = $('#bin_text').val();
                binB = binB.trim();
                var validB = /^B\d+$/gi.test(binB);
                if (binB != '') {
                    if (!validB || (binB.length != 7)) {
                        Swal.fire({
                            title: `House Number should be in BXXXXXX format!`,
                            icon: "warning",
                            button: "Close",
                            className: "custom-swal",
                        })
                        return false;
                    }
                }

                var ownernameO = $('#owner_name').val();
                ownernameO = ownernameO.trim().toLowerCase();
                var validO = /^[a-z][a-z\s]*$/.test(ownernameO);
                if (!validO && (ownernameO != '')) {
                    Swal.fire({
                        title: `Owner name should contain letters only!`,
                        icon: "warning",
                        button: "Close",
                        className: "custom-swal",
                    })
                    return false;
                }
                e.preventDefault();
                dataTable.draw();
                bin = $('#bin_text').val();
                structype = $('#structype_select').val();
                ward = $('#ward_select').val();
                functional_use = $('#functional_use_select').val();
                roadcd = $('#road_code').val();
                ownername = $('#owner_name').val();
                toilet = $('#toilet').val();
                watersourc = $('#watersourc').val();
                well_prese = $('#well_prese').val();
                emptying_status = $('#emptying_status').val();
                sanitation_system_id = $('#sanitation_system_id').val();
                floor_count = $('#floor_count').val();
                //save filter data in local storage

            });

            $('#road_code').prepend('<option selected=""></option>').select2({
                ajax: {
                    url: "{{ route('roadlines.get-road-names') }}",
                    data: function(params) {
                        return {
                            search: params.term,
                            // ward: $('#ward').val(),
                            page: params.page || 1
                        };
                    },
                },
                placeholder: 'Road Code',
                allowClear: true,
                closeOnSelect: true,
                width: '100%'
            });
            resetDataTable(dataTable);
            $("#export").on("click", function(e) {
                e.preventDefault();

                var searchData = $('input[type=search]').val();
                var bin = $('#bin_text').val();
                var structype = $('#structype_select').val();
                var ward = $('#ward_select').val();
                var roadcd = $('#road_code').val();
                var ownername = $('#owner_name').val();
                var toilet = $('#toilet').val();
                // var defecation = $('#defecation').val();
                // var toiletconn = $('#toiletconn').val();
                var watersourc = $('#watersourc').val();
                var well_prese = $('#well_prese').val();
                var emptying_status = $('#emptying_status').val();
                var functional_use = $('#functional_use_select').val();
                var sanitation_system_id = $('#sanitation_system_id').val();
                var floor_count = $('#floor_count').val();

                window.location.href = "{!! url('building-info/buildings/export?searchData=') !!}" +
                    searchData +
                    "&bin=" + bin +
                    "&structype=" + structype +
                    "&ward=" + ward +
                    "&roadcd=" + roadcd +
                    "&ownername=" + ownername +
                    "&toilet=" + toilet +
                    "&defecation=" + defecation +
                    "&toiletconn=" + toiletconn +
                    "&watersourc=" + watersourc +
                    "&well_prese=" + well_prese +
                    "&emptying_status=" + emptying_status +
                    "&functional_use=" + functional_use +
                    "&sanitation_system_id=" + sanitation_system_id +
                    "&floor_count=" + floor_count ;
            });

            $("#export-shp").on("click", function(e) {
                e.preventDefault();
                var cql_param = getCQLParams();
                window.location.href =
                    "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:buildings_layer+&CQL_FILTER=" +
                    cql_param + " &outputFormat=SHAPE-ZIP";

            });
            $("#export-kml").on("click", function(e) {
                e.preventDefault();
                var cql_param = getCQLParams();

                window.location.href =
                    "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:buildings_layer+&CQL_FILTER=" +
                    cql_param + " &outputFormat=KML";

            });

            function getCQLParams() {
                bin = $('#bin_text').val();
                structype = $('#structype_select').val();
                ward = $('#ward_select').val();
                functional_use = $('#functional_use_select').val();
                roadcd = $('#road_code').val();
                ownername = $('#owner_name').val();
                toilet = $('#toilet').val();
                defecation = $('#defecation').val();
                toiletconn = $('#toiletconn').val();
                watersourc = $('#watersourc').val();
                well_prese = $('#well_prese').val();
                emptying_status = $('#emptying_status').val();
                sanitation_system_id = $('#sanitation_system_id').val();
                floor_count = $('#floor_count').val();


                var cql_param = "deleted_at IS NULL";
                if (bin) {
                    cql_param += " AND bin ='" + bin + "'";
                }
                if (structype) {
                    cql_param += " AND structure_type_id = '" + structype + "'";
                }
                if (ward) {
                    cql_param += " AND ward = '" + ward + "'";
                }
                if (functional_use) {
                    cql_param += " AND functional_use_id = '" + functional_use + "'";
                }
                if (roadcd) {
                    cql_param += " AND road_code = '" + roadcd + "'";
                }

                if (ownername) {
                    cql_param += " AND owner_name ILIKE '%" + ownername + "%'";
                }
                if (toilet) {
                    cql_param += " AND toilet_status = '" + toilet + "'";
                }

                if (toiletconn) {
                    cql_param += " AND sanitation_system_type_id = '" + toiletconn + "'";
                }
                if (watersourc) {
                    cql_param += " AND water_source_id = '" + watersourc + "'";
                }
                if (well_prese) {
                    cql_param += " AND well_presence = '" + well_prese + "'";
                }
                if (emptying_status) {
                    cql_param += " AND emptied_status ='" + emptying_status + "'";
                }
                if (sanitation_system_id) {
                    cql_param += " AND sanitation_system_id = '" + sanitation_system_id + "'";
                }
                if (floor_count) {
                    cql_param += " AND floor_count = '" + floor_count + "'";
                }

                return encodeURI(cql_param);
            }
        });

        $(document).on('click', '.containment', function() {
            var modalHeader = $('#containmentsModal .modal-header');
            var modalBody = $('#containmentsModal .modal-body');
            var binId = $(this).data('id');
            $.ajax({
                url: 'buildings/' + binId + '/listContainments',
                type: 'GET',
                success: function(data) {
                    modalHeader.find('.modal-title').text(data.title);
                    modalBody.html(data.popContentsHtml);

                },
            });
        });
    </script>
@endpush
