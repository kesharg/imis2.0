{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Add Route')
{{--Add sections for the index layout--}}

{{--Include the layout inside the main content section--}}
@section('content')
    @include('layouts.create')
@endsection

@push('scripts')
    <script src="{{ asset('js/map-functions.js') }}"></script>
    <script>
        $(document).ready(function (){
            console.log(workspace);
        })
    </script>
@endpush
