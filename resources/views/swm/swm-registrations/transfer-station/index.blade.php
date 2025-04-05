{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Transfer Stations')
{{--Add sections for the index layout--}}
@section('filter-form')
    @include('layouts.filter-form',['formFields' => $filterFormFields])
@endsection
@section('data-table')
    <table id="data-table" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Ward</th>
            <th>Separation Facility</th>
            <th>Area</th>
            <th>Capacity</th>
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
                    url: '{!! route('transfer-station.get-data') !!}',
                    data: function (d) {
                        d.name = $('#name').val(),
                        d.ward = $('#ward').val(),
                        d.separation_facility = $('#separation_facility').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'ward', name: 'ward'},
                    { data: 'separation_facility', name: 'separation_facility',render: function (val) {
                            return val?"Yes":"No";
                        }},
                    { data: 'area', name: 'area'},
                    { data: 'capacity', name: 'capacity'},
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






