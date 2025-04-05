
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
            @can('Add CT/PT General Information')
                <a href="{{ action('Fsm\CtptController@create') }}" class="btn btn-info">Add Public / Community Toilets</a>
            @endcan
            @can('Export CT/PT General Informations')
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
                                                <input type="text" class="form-control" id="name"
                                                    placeholder="Toilet Name" />
                                            </div>
                                            <label for="bin" class="control-label col-md-2">House Number </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="bin"
                                                    placeholder="House Number" />
                                            </div>
                                            <label for="type" class="col-md-2 col-form-label ">Toilet Type</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="type" name="type">
                                                    <option value="">Toilet Type</option>
                                                    <option value="Community Toilet">Community Toilet</option>
                                                    <option value="Public Toilet">Public Toilet</option>
                                                </select>
                                            </div>
                                           
                                        </div>
                                        <div class="form-group row">
                                       
                                       

                                            <label for="ward" class="col-md-2 col-form-label ">Ward</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="ward">
                                                    <option value="">Ward</option>
                                                    @foreach ($ward as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="caretaker_name" class="col-md-2 col-form-label ">Caretaker Name</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="caretaker_name"
                                                    placeholder="Caretaker Name" />
                                            </div>
                                            <label for="sanitary_supplies_disposal_facility" class="col-md-2 col-form-label ">Sanitary Supplies &
                                                Disposal Facility</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="sanitary_supplies_disposal_facility"
                                                    name="sanitary_supplies_disposal_facility">
                                                    <option value="">Sanitary Supplies & Disposal Facility</option>
                                                    <option value=true>Yes</option>
                                                    <option value=false>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                    <label for="width" class="col-md-2 col-form-label ">Status</label>
                                    <div class="col-md-2">
                                    <select class="form-control chosen-select" id="status" name="status">
                                        <option value="">Status</option>

                                        <option value="true">Operational</option>
                                        <option value="false">Not Operational</option>

                                    </select>
                                    </div>
                                    </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-info ">Filter</button>
                                            <button type="reset" id="reset-filter" class="btn btn-info ">Reset</button>
                                        </div>
                                    </form>
                                </div> <!--- accordion body!-->
                            </div> <!--- collapseOne!-->
                        </div> <!--- accordion item!-->
                    </div> <!--- accordion !-->
                </div> <!---col!-->
            </div> <!--- row !-->
        </div>
        <!--- card body !-->
        <div class="card-body">
            <div style="overflow: auto; width: 100%;">
                <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Toilet Name</th>
                            <th>House Number</th>
                            <th>Toilet Type</th>
                            <th>Ward</th>
                            <th>Caretaker Name</th>
                            <th>Sanitary Supplies Disposal Facility</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                bFilter: false,
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                ajax: {
                    url: '{!! url('fsm/ctpt/data') !!}',
                    data: function(d) {
                        d.toilet_id = $('#toilet_id').val();
                        d.bin = $('#bin').val();

                        d.name = $('#name').val();
                        d.ward = $('#ward').val();
                        d.type = $('#type').val();
                        d.status = $('#status').val();

                        d.caretaker_name = $('#caretaker_name').val();

                        d.sanitary_supplies_disposal_facility = $(
                            '#sanitary_supplies_disposal_facility').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                   
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'bin',
                        name: 'bin'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'ward',
                        name: 'ward'
                    },


                    {
                        data: 'caretaker_name',
                        name: 'caretaker_name'
                    },

                    
                    {
                        data: 'sanitary_supplies_disposal_facility',
                        name: 'sanitary_supplies_disposal_facility'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                                'Public / Community Toilets will be Deleted.',
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
            var toilet_id = '',
                bin = '',
                name = '',
                type = '',
                ward = '',
                caretaker_name = '',
                sanitary_supplies_disposal_facility = '';
                status = '';


                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                dataTable.draw();

                    bin = $('#bin').val();
                    name = $('#name').val();

                    type = $('#type').val();
                    ward = $('#ward').val();
                    caretaker_name = $('#caretaker_name').val();
                     status = $('#status').val();
                    sanitary_supplies_disposal_facility = $('#sanitary_supplies_disposal_facility').val();

                });


            $('#export').on('click', function(e) {
                e.preventDefault();
                var searchData = $('input[type=search]').val();

           
                bin = $('#bin').val();
                    name = $('#name').val();

                    type = $('#type').val();
                    ward = $('#ward').val();
                    caretaker_name = $('#caretaker_name').val();
                    status = $('#status').val();
                    sanitary_supplies_disposal_facility = $('#sanitary_supplies_disposal_facility').val();
                window.location.href = "{!! url('fsm/ctpt/export?searchData=') !!}" + searchData +
                    
                    "&bin=" + bin +
                    "&name=" + name +
                    "&type=" + type +
                    "&ward=" + ward +

                    "&caretaker_name=" + caretaker_name +
                    "&status=" + status +
                    "&sanitary_supplies_disposal_facility=" + sanitary_supplies_disposal_facility ;
            });



            // function getCQLParams() {
            //     bin = $('#bin').val();
            //     name = $('#name').val();
            //     ward = $('#ward').val();
            //     caretaker_name = $('#caretaker_name').val();
            //     toilet_type = $('#type').val();

            //     sanitary_supplies_disposal_facility = $('#sanitary_supplies_disposal_facility').val();
            //     var cql_param = "deleted_at IS NULL";
                
            //     if (name) {
            //         cql_param += " AND name ILIKE '%" + name + "%'";
            //     }
            //     if (ward) {
            //         cql_param += " AND ward ='" + ward + "'";
            //     }
            //     if (caretaker_name) {
            //         cql_param += " AND caretaker_name ILIKE '%" + caretaker_name + "%'";
            //     }
            //     if (toilet_type) {
            //         cql_param += " AND type ='" + toilet_type + "'";
            //     }
            //     if (male_or_female_facility) {
            //         cql_param += " AND male_or_female_facility ='" + male_or_female_facility + "'";
            //     }
            //     if (handicap_facility) {
            //         cql_param += " AND handicap_facility ='" + handicap_facility + "'";
            //     }
            //     if (children_facility) {
            //         cql_param += " AND children_facility ='" + children_facility + "'";
            //     }
            //     if (sanitary_supplies_disposal_facility) {
            //         cql_param += " AND sanitary_supplies_disposal_facility ='" + sanitary_supplies_disposal_facility + "'";
            //     }
            //     return encodeURI(cql_param);
            // }
        });
    </script>
@endpush
