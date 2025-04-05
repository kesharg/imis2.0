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
        <a href="{{ action('PublicHealth\YearlyWaterborneController@create') }}" class="btn btn-info">@lang('public.waterborne.add_wcs')</a>
        @endcan
        @can('Export Hotspot Identification')
        <a href="#" id="export" class="btn btn-info">@lang('public.waterborne.export_wcs')</a>
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



                                        <label for="year" class="control-label col-md-2">@lang('public.waterborne.year')</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="year">
                                                    <option value="">@lang('public.waterborne.year')</option>
                                                    @foreach($years  as $key=>$value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <label for="infected_disease" class="control-label col-md-2">@lang('public.waterborne.infected')</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="infected_disease">
                                                    <option value="">@lang('public.waterborne.infected')</option>
                                                    @foreach($enumValues  as $key=>$value)
                                                    <option value="{{$key}}">{{$value}}</option>
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
    <div class="card-body"> <div style="overflow: auto; width: 100%;">
            <table id="data-table" class="table table-bordered table-striped dtr-inline" width="100%">
            <thead>
                <tr>
                    <th>@lang('public.waterborne.id')</th>
                    <th>@lang('public.waterborne.infected')</th>
                    <th>@lang('public.waterborne.year')</th>
                    <th>@lang('public.waterborne.num_cases')</th>
                    <th>@lang('public.waterborne.num_fatal')</th>
                    <th>@lang('public.waterborne.action')</th>
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
        order: [],
        ajax: {
            url: '{!! url("publichealth/waterborne/data") !!}',
            data: function(d) {
                d.infected_disease = $('#infected_disease').val();
                d.year = $('#year').val();
            },
        },
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'infected_disease',
                name: 'infected_disease'
            },
            {
                data: 'year',
                name: 'year',
            },

            {
                data: 'total_no_of_cases',
                name: 'total_no_of_cases'
            },
            {
                data: 'total_no_of_fatalities',
                name: 'total_no_of_fatalities'
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
                        'Waterborne case will be deleted.',
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
    });
    $(".reset").on("click", function(e) {
        $('#infected_disease').val('');
        $('#infected_disease option:selected').removeAttr('selected');
        $('#year').val('');
        $('#year option:selected').removeAttr('selected');
        $('#data-table').dataTable().fnDraw();
    });

    $("#export").on("click", function(e) {
        e.preventDefault();
        var searchData = $('input[type=search]').val();
                var infected_disease = $('#infected_disease').val();
                var year = $('#year').val();
        window.location.href = "{!! url('publichealth/waterborne/export?searchData=') !!}" + searchData +
            "&year=" + $('#year option:selected ').val()+
            "&infected_disease=" + $('#infected_disease option:selected ').val()

    })


});
</script>


@endpush
