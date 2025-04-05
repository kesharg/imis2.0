{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Landfill Sites')
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
            <th>Area</th>
            <th>Capacity</th>
            <th>Lifespan(in years)</th>
            <th>Status</th>
            <th>Operated By</th>
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
                    url: '{!! route('landfill-site.get-data') !!}',
                    data: function (d) {
                        d.name = $('#name').val(),
                        d.ward = $('#ward').val(),
                        d.status = $('#status').val(),
                        d.operated_by = $('#operated_by').val()
                    },
                },
                columns:[
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'ward', name: 'ward'},
                    { data: 'area', name: 'area'},
                    { data: 'capacity', name: 'capacity'},
                    { data: 'life_span', name: 'life_span'},
                    { data: 'status', name: 'status'},
                    { data: 'operated_by', name: 'operated_by'},
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




