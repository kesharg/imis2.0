{{-- Last Modified Date: 14-04-2024
 Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) --}}
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
    <div class="card">
        <div class="card-header">
            @can('Add Hotspot Identification')
                <a href="{{ action('Fsm\HotspotController@create') }}" class="btn btn-info">Add Waterborne Hotspot</a>
            @endcan
            @can('Export Hotspot Identification')
                <a href="#" id="export" class="btn btn-info">Export to Excel</a>
            @endcan
            <a href class="btn btn-info float-right" data-toggle="collapse" data-target="#collapseFilter"
                aria-expanded="false" aria-controls="collapseFilter">Show Filter</a>
        </div><!-- /.card-header -->
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
                                            <label for="disease" class="control-label col-md-2">Infected Disease</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="disease">
                                                    <option value="">Infected Disease</option>
                                                    @foreach ($enumValues as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <label for="hotspot_location" class="control-label col-md-2">Hotspot
                                                Location</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="hotspot_location">
                                                    <option value="">Hotspot Location</option>
                                                    @foreach ($hotspotLocation as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-info">Filter</button>
                                            <button type="reset" class="btn btn-info reset">Reset</button>
                                        </div>
                                    </form>
                                </div>
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
                            <th>ID</th>
                            <th>Infected Disease</th>
                            <th>Hotspot Location</th>
                            <th>Date</th>
                            <th>No. of Cases</th>
                            <th>No. of Fatalities</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div><!-- /.card-body -->
        </div><!-- /.card -->


    @stop

    @push('scripts')
        <script>
            $(function() {
                var dataTable = $('#data-table').DataTable({
                    processing: true,
                    bFilter: false,
                    serverSide: true,
                    scrollCollapse: true,
                    ajax: {
                        url: '{!! url('fsm/hotspots/data') !!}',
                        data: function(d) {
                            d.disease = $('#disease').val();
                            d.hotspot_location = $('#hotspot_location').val();
                        },
                    },

                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'disease',
                            name: 'disease'
                        },
                        {
                            data: 'hotspot_location',
                            name: 'hotspot_location'
                        },

                        {
                            data: 'date',
                            name: 'date',
                            render: function(data, type, row, meta) {
                                if (data) {
                                    return data.split(/(\s+)/)[0];
                                }
                                return data;
                            }
                        },

                        {
                            data: 'no_of_cases',
                            name: 'no_of_cases'
                        },
                        {
                            data: 'no_of_fatalities',
                            name: 'no_of_fatalities'
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
                                Swal.fire(
                                    'Deleted!',
                                    'Waterborne Hotspot Deleted.',
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

                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                    dataTable.draw();
                    disease = $('#disease').val();
                    hotspot_location = $('#hotspot_location').val();

    });
    $(".reset").on("click", function(e) {
        $('#disease').val('');
        $('#disease option:selected').removeAttr('selected');
        $('#hotspot_location').val('');
        $('#hotspot_location option:selected').removeAttr('selected');

        $('#data-table').dataTable().fnDraw();
    });
    $("#export").on("click", function(e) {
        e.preventDefault();
        var searchData = $('input[type=search]').val();
        var disease = $('#disease').val();
        var hotspot_location = $('#hotspot_location').val();
        window.location.href = "{!! url('fsm/hotspots/export?searchData=') !!}" + searchData +
            "&disease=" + disease + "&hotspot_location=" + hotspot_location;

    })
    // $("#export-shp").on("click", function(e) {
    //     e.preventDefault();
    //     var cql_param = getCQLParams();
    //     window.location.href = "{{ Config::get("
    //     constants
    //         .GEOSERVER_URL ") }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get("
    //     constants.AUTH_KEY ") }}&typeName={{ Config::get("
    //     constants.GEOSERVER_WORKSPACE ") }}:waterborne_hotspots_layer+&CQL_FILTER=" + cql_param +
    //         " &outputFormat=SHAPE-ZIP";

    // })

    // $("#export-kml").on("click", function(e) {
    //     e.preventDefault();
    //     var cql_param = getCQLParams();
    //     window.location.href = "{{ Config::get("
    //     constants
    //         .GEOSERVER_URL ") }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get("
    //     constants.AUTH_KEY ") }}&typeName={{ Config::get("
    //     constants.GEOSERVER_WORKSPACE ") }}:waterborne_hotspots_layer+&CQL_FILTER=" + cql_param +
    //         " &outputFormat=KML";
    // });

    function getCQLParams() {
        var cql_param = "1=1 AND deleted_at IS NULL";

        if ($('#hotspot_location').val()) {
            cql_param += " AND hotspot_location =" + $('#hotspot_location').val();
        }
        if ($('#disease').val()) {
            cql_param += " AND disease =" + $('#disease').val();
        }
        return encodeURI(cql_param);
    }

});
</script>
@endpush
