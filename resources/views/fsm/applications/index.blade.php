<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', 'Application')
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
            @if (!empty($createBtnLink) && !empty($createBtnTitle))
                <a href="{{ $createBtnLink }}" class="btn btn-info">{{ $createBtnTitle }}</a>
            @endif
            @if (!empty($exportBtnLink))
                <a href="{{ $exportBtnLink }}" class="btn btn-info" id="export" onclick="exportToCsv(event)">Export to CSV</a>
            @endif
            <a class="btn btn-info float-right" id="headingOne" type="button" data-toggle="collapse"
                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Show Filter
            </a>
            @if (!empty($reportBtnLink))
                <a class="btn btn-info" data-toggle="collapse" data-target="#collapseFilterPdf" aria-expanded="false"
                    aria-controls="collapseFilterPdf">Generate Report</a>
                <div class="card-body">
                    <div class="col-12">
                        <div id="collapseFilterPdf" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-group row">
                                    <label for="bin_text" class="control-label col-md-2">Month</label>
                                    <div class="col-md-2">
                                        <select class="form-control row" id="month_select" name="month"
                                            <?php if (!empty($application_months)) {
                                                echo 'disabled';
                                            } ?>>
                                            <?php
                                        foreach($application_months as $unique)
                                        {

                                        ?> <option value="{{ $unique->date1 }}">
                                                {{ date('F', mktime(0, 0, 0, $unique->date1, 10)) }}</option>
                                            <?php  }
                                                ?>
                                        </select>
                                    </div>
                                    <label for="bin_text" class="control-label col-md-2">Year</label>
                                    <div class="col-md-2">
                                        <select class="form-control row" id="year_select" name="year"
                                            <?php if (!empty($application_years)) {
                                                echo 'disabled';
                                            } ?>>
                                             <option value="" disabled selected>Select a year</option>
                                            <?php

                                                foreach($application_years as $unique) {
                                                    ?> <option value="{{ $unique->date1 }}">
                                                {{ $unique->date1 }}</option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <a class="btn btn-info pdf" id="pdf">Export to PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                                        {{-- A Layout for Filter --}}
                                        @foreach ($filterFormFields as $formFieldGroup)
                                            <div class="form-group row">
                                                @foreach ($formFieldGroup as $formField)
                                                    {!! Form::label($formField->labelFor, $formField->label, ['class' => $formField->labelClass]) !!}
                                                    <div class="col-md-2">
                                                        @if ($formField->inputType === 'text')
                                                            {!! Form::text($formField->inputId, $formField->inputValue, [
                                                                'class' => $formField->inputClass,
                                                                'placeholder' => $formField->placeholder,
                                                                'autocomplete' => $formField->autoComplete,
                                                            ]) !!}
                                                        @endif
                                                        @if ($formField->inputType === 'number')
                                                            {!! Form::number($formField->inputId, $formField->inputValue, [
                                                                'class' => $formField->inputClass,
                                                                'placeholder' => $formField->placeholder,
                                                            ]) !!}
                                                        @endif
                                                        @if ($formField->inputType === 'select')
                                                            {!! Form::select($formField->inputId, $formField->selectValues, $formField->selectedValue, [
                                                                'class' => $formField->inputClass,
                                                                'placeholder' => $formField->placeholder,
                                                            ]) !!}
                                                        @endif
                                                        @if ($formField->inputType === 'label')
                                                            {!! Form::label($formField->inputId, $formField->labelValue, ['class' => $formField->inputClass]) !!}
                                                        @endif
                                                        @if ($formField->inputType === 'multiple-select')
                                                            {!! Form::select($formField->inputId, $formField->selectValues, $formField->selectedValue, [
                                                                'class' => $formField->inputClass,
                                                                'disabled' => $formField->disabled,
                                                                'autocomplete' => $formField->autoComplete,
                                                            ]) !!}
                                                            @push('scripts')
                                                                <script>
                                                                    $(document).ready(function() {
                                                                        $('#{{ $formField->inputId }}').prepend(
                                                                                '<option selected=""></option>').append(
                                                                                '<option value="-1">Address Not Found</option>')
                                                                            .select2({
                                                                                placeholder: '{{ $formField->placeholder }}',
                                                                                matcher: function(params, data) {
                                                                                    if (data.id === "-1") {
                                                                                        return data;
                                                                                    } else {
                                                                                        return $.fn.select2.defaults.defaults
                                                                                            .matcher.apply(this, arguments);
                                                                                    }
                                                                                },
                                                                                closeOnSelect: true,
                                                                                width: 'select'
                                                                            });
                                                                    });
                                                                </script>
                                                            @endpush
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach

                                        <div class="card-footer t text-right">
                                            <button type="submit" class="btn btn-info">Filter</button>
                                            <button id="reset-filter" class="btn btn-info">Reset</button>
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
                            <th>ID</th>
                            <th>House Number</th>
                            <th>Application Date</th>
                            <th>Proposed Emptying Date</th>
                            <th>Road</th>
                            {{-- <th>Assessment Status</th> --}}
                            <th>Emptying Status</th>
                            <th>Sludge Collection Status</th>
                            <th>Feedback Status</th>
                            <th>Owner Name</th>
                            <th>Ward</th>
                            <th>Contact</th>
                            <th>Service Provider</th>
                            <th>Actions</th>
                            {{-- <th>Approved</th>
                    <th>Containment</th>
                    <th>User</th>
                    <th>Applicant Gender</th>
                    <th>Customer Gender</th>
                    <th>Verified Status</th>
                    <th>Applicant's Name</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
@stop

@push('scripts')
    <script>
        @if (!empty($reportBtnLink))
            var yearSelect = document.getElementById("year_select");
            var monthSelect = document.getElementById("month_select");
            var pdfSelect = document.getElementById("pdf");
            <?php echo !empty($application_months) ? 'monthSelect.disabled = false;' : 'monthSelect.disabled = true;'; ?>
            <?php echo !empty($application_years) ? 'yearSelect.disabled = false;' : 'yearSelect.disabled = true;'; ?>
        @endif

        $(function() {
            var dataTable = $('#data-table').DataTable({
                bFilter: false,
                processing: true,
                serverSide: true,
                stateSave: true,
                scrollCollapse: true,
                ajax: {
                    url: '{!! route('application.get-data') !!}',
                    data: function(d) {
                        d.house_number = $('#house_number').val();
                        d.customer_name = $('#customer_name').val();
                        d.ward = $('#ward').val();
                        d.application_id = $('#application_id').val();
                        d.emptying_status = $('#emptying_status').val();
                        d.sludge_collection_status = $('#sludge_collection_status').val();
                        d.feedback_status = $('#feedback_status').val();
                        d.road_code = $('#road_code').val();
                        d.proposed_emptying_date = $('#proposed_emptying_date').val();
                        d.service_provider_id = $('#service_provider_id').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'house_number',
                        name: 'house_number'
                    },
                    {
                        data: 'application_date',
                        name: 'application_date',
                        render: function(data) {
                            return moment(data).format("dddd, MMMM Do YYYY");
                        }
                    },
                    {
                        data: 'proposed_emptying_date',
                        name: 'proposed_emptying_date',
                        render: function(data) {
                            return moment(data).format("dddd, MMMM Do YYYY");
                        }
                    },
                    {
                        data: 'road_code',
                        name: 'road_code'
                    },
                    // { data: 'assessment_status', name: 'assessment_status'},
                    {
                        data: 'emptying_status',
                        name: 'emptying_status'
                    },
                    {
                        data: 'sludge_collection_status',
                        name: 'sludge_collection_status'
                    },
                    {
                        data: 'feedback_status',
                        name: 'feedback_status'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'ward',
                        name: 'ward'
                    },
                    {
                        data: 'customer_contact',
                        name: 'customer_contact'
                    },
                    {
                        data: 'service_provider_id',
                        name: 'service_provider_id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    /*{ data: 'approved', name: 'approved'},
                    { data: 'containment_code', name: 'containment_code'},
                    { data: 'applicants_name', name: 'applicants_name'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'applicant_gender', name: 'applicant_gender'},
                    { data: 'customer_gender', name: 'customer_gender'},
                    { data: 'verified_status', name: 'verified_status'},*/
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
                                'Application will be deleted.',
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
            filterDataTable(dataTable);
            resetDataTable(dataTable);
            $('#filter-form').on('submit', function(e) {
                var date_from = $('#date_from').val();
                var date_to = $('#date_to').val();
                if ((date_from !== '') && (date_to === '')) {

                    Swal.fire({
                        title: 'Date To is required',
                        text: "Please select Date To!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'close'
                    })

                    return false;
                }
                if ((date_from === '') && (date_to !== '')) {

                    Swal.fire({
                        title: 'Date From is required',
                        text: "Please select Date From!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'close'
                    })

                    return false;
                }

                e.preventDefault();
                dataTable.draw();
                treatment_plant_id = $('#treatment_plant_id').val();
                date_from = $('#date_from').val();
                date_to = $('#date_to').val();
                application_id = $('#application_id').val();
                servprov = $('#servprov').val();
            });


            setTimeout(function() {
                localStorage.clear();
            }, 60 * 60 * 1000); ///for 1 hour

            $('#proposed_emptying_date, #date_from, #date_to').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                showDropdowns: true,
                autoApply: true,
                drops: "auto"
            });
            $('#proposed_emptying_date, #date_from, #date_to').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
            });

            $('#proposed_emptying_date, #date_from, #date_to').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('#road_code').prepend('<option selected=""></option>').select2({
                ajax: {
                    url: "{{ route('roadlines.get-road-names') }}",
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                },
                placeholder: 'Road',
                allowClear: true,
                closeOnSelect: true,
                width: '100%'
            });


            $('[id="pdf"]').click(function(e) {
                <?php if(empty($application_months) && empty($application_years)) { ?>
                return false;
                <?php }  else { ?>
                // e.preventDefault();
                if (localStorage.getItem('year_select') != null && localStorage.getItem('month_select') !=
                    null) {
                    year_sel = localStorage.getItem('year_select');
                    month_sel = localStorage.getItem('month_select');
                } else {
                    year_sel = $('#year_select').val();
                    month_sel = $('#month_select').val();
                }
                const url = `application/monthly/pdf/${year_sel}/${month_sel}`;
                window.open(url, "Monthly Report");
                <?php } ?>
            })
            $('.date, #date_from, #date_to, #proposed_emptying_date').focus(function() {
                $(this).blur();
            });
        });
    </script>
@endpush
