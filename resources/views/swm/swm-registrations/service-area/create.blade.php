<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/ol-layerswitcher@3.8.3/dist/ol-layerswitcher.css"/>
{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Add Service Area')
{{--Add sections for the index layout--}}

{{--Include the layout inside the main content section--}}
@section('content')
    <script src="{{ asset('/js/ol.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/ol-layerswitcher@3.8.3"></script>
    <script src="{{ asset('js/map-functions.js') }}"></script>
    @include('layouts.create')
@endsection
