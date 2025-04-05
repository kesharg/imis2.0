{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'Add Waste Recycle')
{{--Add sections for the index layout--}}

{{--Include the layout inside the main content section--}}
@section('content')
    @include('layouts.create')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#volume').val() !== '' && $('#rate').val() !== ''){
                $('#total_price').val($('#volume').val()*$('#rate').val());
                $('#total_price_disabled').val($('#volume').val()*$('#rate').val());
            }
            $('#volume').on('input',function () {
                $('#total_price').val($('#volume').val()*$('#rate').val());
                $('#total_price_disabled').val($('#volume').val()*$('#rate').val());
            });
            $('#rate').on('input',function () {
                $('#total_price').val($('#volume').val()*$('#rate').val());
                $('#total_price_disabled').val($('#volume').val()*$('#rate').val());
            });
        })
    </script>
@endpush
