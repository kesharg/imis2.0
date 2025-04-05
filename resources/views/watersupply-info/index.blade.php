@extends('layouts.dashboard')
@section('title', 'Water Supply ISS')
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
    @can('Import Water Supply Payment Info From CSV')
        <a href="{{ route('watersupply-payment.create') }}" class="btn btn-info">Import from CSV </a>
        @endcan
        @can('Export Water Supply Info')
        <a href="/pdf/water-supply-template-iss.csv" download="water-supply-template-iss.csv" class="btn btn-info">Download CSV Template</a>
        @endcan
        @can('Export Water Supply Info')
        <a href="{{ route('watersupply-payment.export') }}" id="export" class="btn btn-info">Export to CSV </a>
        @endcan
      <a href="#" class="btn btn-info float-right" id="headingOne" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Show Filter
      </a>
    </div><!-- /.box-header -->
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
                                    <label for="ward_select" class="control-label col-md-2">Ward</label>
                                    <div class="col-md-2">
                                        <select class="form-control" id="ward_select">
                                            <option value="">All Wards</option>
                                            @foreach($wards as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="dueyear_select" class="control-label col-md-2">Due</label>
                                    <div class="col-md-2">
                                        <select class="form-control" id="dueyear_select">
                                            <option value="">All Dues</option>
                                            @foreach($dueYears as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="match_unmatch" class="control-label col-md-2">Match</label>
                                    <div class="col-md-2">
                                        <select class="form-control" id="match_unmatch">
                                            <option value="">All</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-info ">Filter</button>
                                        <button id="reset-filter" type="reset" class="btn btn-info">Reset</button>
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
        </div>
        <!--- row !-->
    </div>
    <!--- card body !-->

    <div class="card-body">
        <div style="overflow: auto; width: 100%;">
            <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
                <thead>
                <tr>
                    <th>Tax Code</th>
                    <th>Owner</th>
                    <th>Gender</th>
                    <th>Years Due</th>
                    <th>Ward</th>
                    <th>Match</th>
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
        "stateDuration": 1800, // In seconds; keep state for half an hour

        ajax: {
            url: '{!! url("watersupply-payment/data") !!}',
            data: function(d) {
                d.ward_select = $('#ward_select').val();
                d.dueyear_select = $('#dueyear_select').val();
                    d.match = $('#match_unmatch').val();
            }
        },
        columns: [{
                    data: 'tax_code',
                    name: 'tax_code'
                },
                {
                    data: 'owner_name',
                    name: 'owner_name'
                },
                {
                    data: 'owner_gender',
                    name: 'owner_gender'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'ward',
                    name: 'ward'
                },
                {
                    data: 'match',
                    render: function(data, type, row) {
                        if (data) {
                            return 'Y'
                        } else {
                            return 'N'
                        }
                    }
                }
            ]
    }).on('draw', function() {
      $('.delete').on('click', function(e) {

      var form =  $(this).closest("form");
      event.preventDefault();
      swal({
          title: `Are you sure you want to delete this record?`,
          text: "If you delete this, it will be gone forever.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          form.submit();
        }
      })
      });
    });

    var ward_select = '', dueyear_select = '', match = '';


    $('#filter-form').on('submit', function(e) {
     
        e.preventDefault();
        dataTable.draw();
        ward_select = $('#ward_select').val();
      dueyear_select = $('#dueyear_select').val();
      match = $('#match_unmatch').val();
    });
    filterDataTable(dataTable);
    resetDataTable(dataTable);
    //  $('#data-table_filter input[type=search]').attr('readonly', 'readonly');

    $("#export").on("click", function(e) {
        e.preventDefault();
        var searchData = $('input[type=search]').val();
        var  ward_select = $('#ward_select').val();
        var dueyear_select = $('#dueyear_select').val();
        var match = $('#match_unmatch').val();
        window.location.href = "{!! url('watersupply-payment/export?searchData=') !!}"+searchData+"&ward="+ward_select+"&due_year="+encodeURIComponent(dueyear_select)+"&match="+match;
    });

   
 


});
</script>

@endpush
