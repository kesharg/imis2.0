<!-- Last Modified Date: 11-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@push('style')
<style type="text/css">
.dataTables_filter {
  display: none;
}
</style>
@endpush
@section('content')

	<div class="card">
        <div class="card-header">
          @can('Export Drains to Excel')
          <a href="{{ action('UtilityInfo\DrainController@export') }}" id="export" class="btn btn-info">Export to CSV</a>
          @endcan
          @can('Export Drains to Shape')
                  <a href="#" id="export-shp" class="btn btn-info">Export to Shape File</a>
          @endcan
          @can('Export Drains to KML')
                  <a href="#" id="export-kml" class="btn btn-info">Export to KML</a>
          @endcan
          <a href="#" class="btn btn-info float-right" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">Show Filter</a>
        </div><!-- /.box-header -->
        <div class="card-body">
        <div class="row">
            <div class="col-12">
          <div class="accordion" id="accordionFilter">
          <div class="accordion-item">
              <div id="collapseFilter" class="collapse" aria-labelledby="filter" data-parent="#accordionFilter">
              <div class="accordion-body">
                  <form class="form-horizontal" id="filter-form">
                    <div class="form-group row">
                      <label for="code_text" class="col-md-2 col-form-label ">Code</label>
                      <div class="col-md-2">
                      <input type="text" class="form-control" id="code_text" placeholder="Code" />
                      </div>
                      <label for="cover_type" class="col-md-2 col-form-label ">Cover Type</label>
                              <div class="col-md-2">
                              <select class="form-control" id="cover_type">
                                  <option value="">All</option>
                                  @foreach($cover_type as $key)
                                  <option value="{{$key}}">{{$key}}</option>
                                  @endforeach

                                </select>
                            </div>
                    </div>
                    <div class="card-footer text-right">
                    <button type="submit" class="btn btn-info ">Filter</button>
                    <button id="reset-filter" type="reset" class="btn btn-info ">Reset</button>
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
          <table id="data-table" class="table table-bordered table-striped dataTable dtr-inline" width="100%">
            <thead>
              <tr>
                <th>Code</th>
                <th>Treatment Plant</th>
                <th>Cover Type</th>
                <th>Width (mm)</th>
                <th>Length (m)</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

@stop

@push('scripts')
<script>
$(function() {
    var dataTable = $('#data-table').DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        scrollCollapse: true,
        "bStateSave": true,
        "stateDuration" : 1800, // In seconds; keep state for half an hour
        ajax: {
          url: '{!! url("utilityinfo/drains/data") !!}',
          data: function(d) {
            d.code = $('#code_text').val();
            d.cover_type = $('#cover_type').val();
          }
        },
        columns: [
            { data: 'code', name: 'code' },
            { data: 'tp_id', name: 'tp_id' },
            { data: 'cover_type', name: 'cover_type' },
            { 
                data: 'size', 
                name: 'size',
                render: function (data) {
                    return parseFloat(data).toFixed(2);
                }
            },
            { data: 'length', name: 'length' },
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[ 0, 'desc' ]]
    }).on( 'draw', function () {
          $('.delete').on('click', function(e) {
            var form =  $(this).closest("form");
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
                'Drain will be deleted.',
                'success'
              ).then((willDelete) => {
                     if (willDelete) {
                       form.submit();
                     }
                   })
            }
          })

          });
    } );

    var code = '', cover_type = '';

    $('#filter-form').on('submit', function(e){
        var drainR = $('#code_text').val();
        drainR = drainR.trim();
        var validR = /^D\d+$/gi.test(drainR);
        if(drainR != ''){
        if(!validR || (drainR.length != 7)){
            Swal.fire({
                title: `Drain code should be in DXXXX format!`,
                icon: "warning",
                button: "Close",
                className: "custom-swal",
            })
            return false;
            }
        }
      e.preventDefault();
      dataTable.draw();
      code = $('#code_text').val();
      cover_type = $('#cover_type').val();

    });
    filterDataTable(dataTable);
    resetDataTable(dataTable);
    //  $('#data-table_filter input[type=search]').attr('readonly', 'readonly');
    $("#export").on("click",function(e){
        e.preventDefault();
        var searchData=$('input[type=search]').val();
        var code = $('#code_text').val();
        var cover_type = $('#cover_type').val();
        window.location.href="{!! url('utilityinfo/drains/export?searchData=') !!}"+searchData+"&code="+code+"&cover_type="+cover_type;
    })

    $("#export-shp").on("click", function (e) {
        e.preventDefault();
        var cql_param = getCQLParams();
        window.location.href = "{{ Config::get("constants.GEOSERVER_URL") }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get("constants.AUTH_KEY") }}&typeName={{ Config::get("constants.GEOSERVER_WORKSPACE") }}:drains_layer+&CQL_FILTER=" + cql_param + " &outputFormat=SHAPE-ZIP";

    })

    $("#export-kml").on("click", function (e) {
        e.preventDefault();
        var cql_param = getCQLParams();
        window.location.href = "{{ Config::get("constants.GEOSERVER_URL") }}wfs?service=WFS&version=1.0.0&request=GetFeature&authkey={{ Config::get("constants.AUTH_KEY") }}&typeName={{ Config::get("constants.GEOSERVER_WORKSPACE") }}:drains_layer+&CQL_FILTER=" + cql_param + " &outputFormat=KML";
    });

    function getCQLParams(){
        code = $('#code_text').val();
        type = $('#cover_type').val();

        var cql_param = "1=1 AND deleted_at IS NULL";
        if (code) {
            cql_param += " AND code ='" + code + "'";
        }
        if (type) {
            cql_param += " AND type ='" + type + "'";
        }
        return encodeURI(cql_param);
        
    }

   
});
</script>
@endpush
