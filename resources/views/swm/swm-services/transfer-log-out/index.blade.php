{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Transfer Log Outs')
{{--Add sections for the index layout--}}
@section('filter-form')
    @include('layouts.filter-form',['formFields' => $filterFormFields])
@endsection
@section('data-table')
    <table id="data-table" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Transfer Station</th>
            <th>Landfill Site</th>
            <th>Type of Waste</th>
            <th>Volume</th>
            <th>Date & Time</th>
            <th>Received</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
@endsection

{{--Include the layout inside the main content section--}}
@section('content')
    @include('layouts.index')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverside: true,
                bStateSave: true,
                bFilter: false,
                stateDuration: 1800,
                ajax: {
                    url: '{!! route('transfer-log-out.get-data') !!}',
                    data: function (d) {
                        d.transfer_station_id = $('#transfer_station_id').val(),
                        d.landfill_site_id = $('#landfill_site_id').val(),
                        d.type_of_waste = $('#type_of_waste').val(),
                        d.received = $('#received').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'transfer_station.name', name: 'transfer_station.name'},
                    { data: 'landfill_site.name', name: 'landfill_site.name'},
                    { data: 'type_of_waste', name: 'type_of_waste'},
                    { data: 'volume', name: 'volume'},
                    { data: 'date_time', name: 'date_time'},
                    { data: 'received', name: 'received'},
                    { data: 'action', name: 'action'},
                ],
                order:[[0, 'desc']]

            }).on( 'draw', function () {
                $('.delete').on('click', function (e){
                    e.preventDefault();
                    var form =  $(this).closest("form");
                    deleteAction(form);
                });
            } );

            filterDataTable(dataTable);
            resetDataTable(dataTable);
        });
    </script>
@endpush



