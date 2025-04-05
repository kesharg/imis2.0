<!-- Last Modified Date: 19-04-2024
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
    <div class="card">
        <div class="card-header">
            @can('Add Male or Female User')
                <a href="{{ action('Fsm\CtptUserController@create') }}" class="btn btn-info">Add PT Users Log</a>
            @endcan
            @can('Export Male or Female User')
                <a href="#" id="export" class="btn btn-info">Export to CSV</a>
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
                                        <label for="name" class="col-md-2 col-form-label ">Toilet Name</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="toilet_id"
                                                    placeholder="Toilet Name" />
                                            </div>
                                           
                                            
                                            <label for="date" class="col-md-2 col-form-label ">Date</label>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control" id="date"
                                                    placeholder="Date" />
                                            </div>
                                           
                                        </div>

                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-info ">Filter</button>
                                            <button type="reset" id="reset-filter" class="btn btn-info ">Reset</button>
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
            <div style="overflow: auto; width: 100%;">
                <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Toilet Name</th>
                            <th>Date</th>
                            <th>No. of Male Users (daily)</th>
                            <th>No. of Female Users (daily)</th>
                            <th>Action </th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                    url: '{!! url('fsm/ctpt-users/data') !!}',
                    data: function(d) {
                        d.toilet_id = $('#toilet_id').val();
                        d.date = $('#date').val();
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'toilet_id',
                        name: 'toilet_id'
                    },{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'no_male_user',
                        name: 'no_male_user'
                    },
                    {
                        data: 'no_female_user',
                        name: 'no_female_user'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
        order: [ [0, 'desc'] ]
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
                                'PT Users Log will be Deleted.',
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
                var toilet_id = $('#toilet_id').val();
                var date = $('#date').val();

                e.preventDefault();
                dataTable.draw();
            });

            resetDataTable(dataTable);

            // $('#data-table_filter input[type=search]').attr('readonly', 'readonly');

            $("#export").on("click", function(e) {
                e.preventDefault();
                var searchData = $('input[type=search]').val();
                var toilet_id = $('#toilet_id').val();
                var date = $('#date').val();
                window.location.href = "{!! url('fsm/ctpt-users/export?searchData=') !!}" + searchData +
                    "&toilet_id=" + $('#toilet_id').val() +
                    "&date=" + $('#date').val();
            });


        });
    </script>
@endpush
