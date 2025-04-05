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
@include('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
