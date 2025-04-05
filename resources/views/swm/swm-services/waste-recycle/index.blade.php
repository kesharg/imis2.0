{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Waste Recycle')
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
            <th>Volume</th>
            <th>Waste Type</th>
            <th>Date & Time</th>
            <th>Rate</th>
            <th>Total Price</th>
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
                    url: '{!! route('waste-recycle.get-data') !!}',
                    data: function (d) {
                        d.transfer_station_id = $('#transfer_station_id').val(),
                        d.waste_type = $('#waste_type').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'transfer_station.name', name: 'transfer_station.name'},
                    { data: 'volume', name: 'volume'},
                    { data: 'waste_type', name: 'waste_type'},
                    { data: 'date_time', name: 'date_time'},
                    { data: 'rate', name: 'rate'},
                    { data: 'total_price', name: 'total_price'},
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


