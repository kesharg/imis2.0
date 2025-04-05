<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/ol-layerswitcher@3.8.3/dist/ol-layerswitcher.css"/>
@push('scripts')
    <script src="{{ asset('/js/ol.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/ol-layerswitcher@3.8.3"></script>
    <script src="{{ asset('js/map-functions.js') }}"></script>
    <script>
        $(document).ready(function () {
            if ($('#service_type').val() === 'Private'){
                $('#service_provider_id').parent().parent().show();
            } else {
                $('#service_provider_id').parent().parent().hide();
            }
            $('#service_type').on('change',function (e) {
                if (this.value === 'Private'){
                    $('#service_provider_id').parent().parent().show();
                } else {
                    $('#service_provider_id').parent().parent().hide();
                }
            })
        });
    </script>
@endpush

@include('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
