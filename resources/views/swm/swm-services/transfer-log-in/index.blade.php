{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Transfer Log Ins')
{{--Add sections for the index layout--}}
@section('filter-form')
    @include('layouts.filter-form',['formFields' => $filterFormFields])
@endsection
@section('data-table')
    <table id="data-table" class="table table-bordered table-striped dataTable dtr-inline">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Route</th>
                        <th>Transfer Station</th>
                        <th>Type of Waste</th>
                        <th>Volume</th>
                        <th>Date</th>
                        <th>Time</th>
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
                    url: '{!! route('transfer-log-in.get-data') !!}',
                    data: function (d) {
                        d.route_id = $('#route_id').val(),
                        d.transfer_station_id = $('#transfer_station_id').val(),
                        d.type_of_waste = $('#type_of_waste').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'route.name', name: 'route.name'},
                    { data: 'transfer_station.name', name: 'transfer_station.name'},
                    { data: 'type_of_waste', name: 'type_of_waste'},
                    { data: 'volume', name: 'volume'},
                    { data: 'date', name: 'date'},
                    { data: 'time', name: 'time'},
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


