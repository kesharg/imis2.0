<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', $page_title)


@section('content')

<div class="card border-0">
  <div class="card-header">
      @can('Import Property Tax Collection From CSV')
      <a href="{{ route('swm-payment.create') }}" class="btn btn-info">Import from CSV </a>
    @endcan
    @can('Export Property Tax Collection Info')
    <a href="/pdf/building-tax-payments-template.csv" download="building-tax-payments-template.csv" class="btn btn-info">Download CSV Template</a>
    @endcan
    @can('Export Property Tax Collection Info')
      <a href="{{ route('tax-payment.export') }}" id="export" class="btn btn-info">Export to CSV </a>
      @endcan
      <a href="#" class="btn btn-info float-right" id="headingOne" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Show Filter
      </a>
  </div><!-- /.card-header -->
  <div class="card-body">
    <div class="row">
      <div class="accordion col-md-12" id="accordionExample">
        <div class="accordion-item">
          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="accordion-body">
              <form class="form-horizontal">
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
                <div class="card-footer form-group">
                  <button type="reset" class="btn btn-info float-right ml-2" id="reset">Reset</button>
                  <button type="" class="btn btn-info float-right" id="filter-form">Filter</button>
                </div>
              </form>
            </div>  <!--- accordion body!-->
          </div>    <!--- collapseOne!-->
        </div>      <!--- accordion item!-->
      </div>        <!--- accordion !-->
    </div>            <!--- row !-->
  </div>              <!--- card body !-->
  <div class="card-body">               <div style="overflow: auto; width: 100%;">
            <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
        <thead>
          <tr>
            <th>Tax Code</th>
            <th>Bin</th>
            <th>Owner</th>
            <th>Gender</th>
            <th>Years Due</th>
            <th>Ward</th>
            <th>Match</th>
          </tr>
        </thead>
    </table>
</div>
  </div><!-- /.card-body -->
</div> <!-- /.card -->

@stop


@push('scripts')
<script>
$(function() {
    var dataTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        scrollCollapse: true,
        "bStateSave": true,
        "bFilter" : false,
        "stateDuration" : 1800, // In seconds; keep state for half an hour
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        },
        ajax: {
          url: '{!! url("tax-payment/data") !!}',
          data: function(d) {
            d.ward_select = $('#ward_select').val();
            d.dueyear_select = $('#dueyear_select').val();
            d.match = $('#match_unmatch').val();
          }
        },
        columns: [
            { data: 'tax_code', name: 'tax_code' },
            { data: 'bin', name: 'bin' },
            { data: 'owner_name', name: 'owner_name' },
            { data: 'owner_gender', name: 'owner_gender' },
            { data: 'name', name: 'name' },
            { data: 'ward', name: 'ward' },
            { data: 'match', render : function (data, type, row) {
                    if(data) {
                        return 'Y'
                    }
                    else {
                        return 'N'
                    }
                } }
        ]
    }).on( 'draw', function () {
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
    } );
    var ward_select = '', dueyear_select = '', match = '';
    $('#filter-form').on('click', function(e){

      e.preventDefault();
      dataTable.draw();
      ward_select = $('#ward_select').val();
      dueyear_select = $('#dueyear_select').val();
      match = $('#match_unmatch').val();
    });

    // $('#data-table_filter input[type=search]').attr('readonly', 'readonly');

    $("#export").on("click",function(e){
        e.preventDefault();
        var searchData=$('input[type=search]').val();
        window.location.href="{!! url('tax-payment/export?searchData=') !!}"+searchData+"&ward="+ward_select+"&due_year="+encodeURIComponent(dueyear_select)+"&match="+match;
    })

    $("#reset").on("click",function(e){
    $('#ward_select').val('');
    $('#dueyear_select').val('');
    $('#match_unmatch').val('');
    $('#data-table').dataTable().fnDraw();localStorage.removeItem('DataTables_'+window.location.pathname);
    // localStorage.clear();
    // window.location.reload();
    })

    setTimeout(function(){
    localStorage.clear();
    }, 60*60*1000); ///for 1 hour

});
</script>
<!-- toggle filter show hide -->
<script>
      $(document).ready(function() {
      $('[data-toggle="collapse"]').click(function() {
        $(this).toggleClass( "active" );
        if ($(this).hasClass("active")) {
          $(this).text("Hide Filter");
        } else {
          $(this).text("Show Filter");
        }
      });
      });
    </script>
@endpush
