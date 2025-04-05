{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Collection Points')
{{--Add sections for the index layout--}}
@section('filter-form')
    @include('layouts.filter-form',['formFields' => $filterFormFields])
@endsection
@section('data-table')
    <table id="data-table" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Route</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>Ward</th>
            <th>Service Area</th>
            <th>Service Type</th>
            <th>Household Served</th>
            <th>Status</th>
            <th>Collection Time</th>
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
                bFilter: false,
                serverside: true,
                bStateSave: true,
                stateDuration: 1800,
                ajax: {
                    url: '{!! route('collection-point.get-data') !!}',
                    data: function (d) {
                        d.route_id = $('#route_id').val(),
                        d.type = $('#type').val(),
                        d.ward = $('#ward').val(),
                        d.service_type = $('#service_type').val(),
                        d.status = $('#status').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'route.name', name: 'route.name'},
                    { data: 'type', name: 'type'},
                    { data: 'capacity', name: 'capacity'},
                    { data: 'ward', name: 'ward'},
                    { data: 'service_area.name', name: 'service_area.name'},
                    { data: 'service_type', name: 'service_type'},
                    { data: 'household_served', name: 'household_served'},
                    { data: 'status', name: 'status'},
                    { data: 'collection_time', name: 'collection_time'},
                    { data: 'action', name: 'action'},
                ],
                order:[[0, 'desc']]

            }).on( 'draw', function () {
                $('.delete').on('click', function (e){
                    e.preventDefault();
                    var form =  $(this).closest("form");
                    deleteAction(form);
            });
            });

            filterDataTable(dataTable);
            resetDataTable(dataTable);
        });
    </script>
@endpush


