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

                                        <label for="bin" class="control-label col-md-2">House Number</label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" id="bin" />
                                        </div>

                                        <label for="date_from" class="control-label col-md-2">Date From</label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" id="date_from" />
                                        </div>
                                        <label for="date_to" class="control-label col-md-2">Date To</label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" id="date_to" />
                                        </div>


                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-info">Filter</button>
                                        <button type="reset" class="btn btn-info reset">Reset</button>
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
                    <th>House Number</th>
                    <th>Tax Code</th>
                    <th>Survey Date</th>
                    <th>Action</th>

                </tr>
            </thead>
        </table>
    </div><!-- /.card-body -->
</div> <!-- /.card -->
@include('building-info.building-surveys.kmlPreviewModal')


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
            url: '{!! url("building-info/building-surveys/data") !!}',
            data: function(d) {
                d.bin = $('#bin').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();

            }
        },
        columns: [{
                data: 'bin',
                name: 'bin'
            },
            {
                data: 'tax_code',
                name: 'tax_code'
            },
            {
                data: 'collected_date',
                name: 'collected_date'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
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
    var bin = '';
        date_from = '',
        date_to = '';

    $('#filter-form').on('submit', function(e) {
        var bin = $('#bin').val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();


        if (date_from === '') {
            $('.message').text('Please select both Date From and Date To');
            $('#exampleModal').modal('show');
            return false;
        }
        if (date_to === '') {
            $('.message').text('Please select both Date From and Date To');
            $('#exampleModal').modal('show');
            return false;
        }
        if ((date_to === '') && (date_to === '')) {
            $('.message').text('Please select both Date From and Date To');
            $('#exampleModal').modal('show');
            return false;
        }
        e.preventDefault();
        dataTable.draw();
        bin = $('#bin').val();
        date_from = $('#date_from').val();
        date_to = $('#date_to').val();

    });

    $(".reset").on("click", function(e) {
        $('#bin').val('');
        $('#date_from').val('');
        $('#date_to').val('');
        $('#data-table').dataTable().fnDraw();
    })
    $('#date_from, #date_to').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        autoApply: true,
        drops: "auto"
    });
    $('#date_from, #date_to').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('#date_from, #date_to').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('.date, #date_from, #date_to').focus(function() {
        $(this).blur();
    });

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
