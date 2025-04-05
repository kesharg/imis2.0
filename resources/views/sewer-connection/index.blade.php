@extends('layouts.dashboard')
@section('title', $page_title)


@section('content')

<div class="card">
    <div class="card-header">
    <a class="btn btn-info float-right" id="headingOne" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Show Filter
        </a>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="" id="mydiv" style="display: none">
                    <div class="xaccordion-item">
                        <div id="xcollapseOne" xclass="xaccordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="xaccordion-body">
                            <form class="form-horizontal" id="filter-form">
                    <div class="form-group row">
                      <label for="sewer_code" class="col-md-2 col-form-label ">Sewer Code</label>
                      <div class="col-md-2">
                      <input type="text" class="form-control" id="sewer_code" placeholder="Sewer Code" />
                      </div>
                      <label for="bin" class="col-md-2 col-form-label ">Building Code</label>
                      <div class="col-md-2">
                      <input type="text" class="form-control" id="bin" placeholder="Building Code" />
                      </div>
                    </div>
                    <div class="card-footer text-right">
                    <button type="submit" class="btn btn-info ">Filter</button>
                    <button type="reset" class="btn btn-info reset ">Reset</button>
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
        <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
        <thead>
              <tr>
                <th>Sewer Code</th>
                <th>Building Code</th>
                <th>Actions</th>
              </tr>
            </thead>
        </table>
    </div><!-- /.card-body -->
</div> <!-- /.card -->
@include('sewer-connection.approve')
      @include('sewer-connection.mapview')


@stop

@push('scripts')
<script>
$.fn.dataTable.ext.errMode = 'throw';
$(function() {
    var dataTable = $('#data-table').DataTable({
        processing: true,
        bFilter: false,
        serverSide: true,
        ajax: {
          url: '{!! url("sewerconnection/sewerconnection/data") !!}',
          data: function(d) {
            d.sewer_code = $('#sewer_code').val();
            d.bin = $('#bin').val();
          }
        },
        columns: [
            { data: 'sewer_code', name: 'sewer_code' },
            { data: 'bin', name: 'bin' },
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [ [2, 'desc'] ]
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
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        });
    });
    var sewer_code = '', bin = '';

    $('#filter-form').on('submit', function(e){
  
      e.preventDefault();
      dataTable.draw();
      sewer_sewer_code = $('#sewer_code').val();
      bin = $('#bin').val();

    });
   

    $(".reset").on("click",function(e){
    $('#sewer_code').val('');
    $('#bin').val('');
    $('#data-table').dataTable().fnDraw();
    localStorage.removeItem('DataTables_'+window.location.pathname);

   
  })

$('#headingOne').click(function () {

    if ($(this).text() == 'Hide Filter') {
        $('#mydiv').slideDown("slow");
    } else if ($(this).text() == 'Show Filter') {
        $('#mydiv').slideUp("slow");
    }
  });
});
</script>
@endpush
