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
            @can('Add Low Income Community')
                <a href="{{ action('LayerInfo\LowIncomeCommunityController@create') }}" class="btn btn-info">Add Low Income Community</a>
            @endcan
            @can('Export Low Income Communities')
                <a href="#" id="export" class="btn btn-info">Export to CSV</a>
            @endcan
            @can('Export Low Income Communities')
                <a href="#" id="export-shp" class="btn btn-info">Export to Shape File</a>
            @endcan
            @can('Export Low Income Communities')
                <a href="#" id="export-kml" class="btn btn-info">Export to KML</a>
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
                                           <label for="owner_name" class="control-label col-md-2">Community Name</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="community_name"
                                                    placeholder="Community Name" />
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
                            <th>Community Name</th>
                            <th>No. of Buildings</th>
                            <th>Population</th>
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
                        url: '{!! url('layer-info/low-income-communities/data') !!}',
                        data: function(d) {
                            d.community_name = $('#community_name').val();
                        },
                    },

                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'community_name',
                            name: 'community_name'
                        },
                        {
                            data: 'no_of_buildings',
                            name: 'no_of_buildings'
                        },
                        {
                            data: 'population_total',
                            name: 'population_total'
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
                                    'Low Income Community Deleted.',
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
                    community_name = $('#community_name').val();

    });
    $(".reset").on("click", function(e) {
        $('#community_name').val('');
        $('#data-table').dataTable().fnDraw();
    });
    $("#export").on("click", function(e) {
        e.preventDefault();
        var searchData = $('input[type=search]').val();
        var community_name = $('#community_name').val();
        window.location.href = "{!! url('layer-info/low-income-communities/export?searchData=') !!}" + searchData +
            "&community_name=" + community_name;

    })

$("#export-shp").on("click", function(e) {
    e.preventDefault();
    var cql_param = getCQLParams();
    window.location.href =
        "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:low_income_communities_layer+&CQL_FILTER=" +
        cql_param + " &outputFormat=SHAPE-ZIP";

})

$("#export-kml").on("click", function(e) {
    e.preventDefault();
    var cql_param = getCQLParams();
    window.location.href =
        "{{ Config::get('constants.GEOSERVER_URL') }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get('constants.AUTH_KEY') }}&typeName={{ Config::get('constants.GEOSERVER_WORKSPACE') }}:low_income_communities_layer+&CQL_FILTER=" +
        cql_param + " &outputFormat=KML";
});

    function getCQLParams() {
        var cql_param = "1=1 AND deleted_at IS NULL";

        if ($('#community_name').val()) {
            var community_name = $('#community_name').val().trim();;
            cql_param += " AND community_name ILIKE '%" + community_name + "%'";
        }
        return encodeURI(cql_param);
    }

});
</script>
@endpush
