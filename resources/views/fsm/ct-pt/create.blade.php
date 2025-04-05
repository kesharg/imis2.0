<!-- Last Modified Date: 19-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
@include('layouts.components.error-list')
@include('layouts.components.success-alert')
@include('layouts.components.error-alert')
<div class="card card-info">
	{!! Form::open(['url' => 'fsm/ctpt', 'class' => 'form-horizontal']) !!}
		@include('fsm/ct-pt.partial-form', ['submitButtomText' => 'Save'])
	{!! Form::close() !!}
</div><!-- /.card -->
@stop
@push('scripts')

<script>
$(document).ready(function() {
   
    
            $('.bin').prepend('<option selected=""></option>').select2({
                ajax: {
                    url: "{{ route('building.get-house-numbers-all') }}",
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                },
                placeholder: 'House Number',
                allowClear: true,
                closeOnSelect: true,
            });
 
       }); 

</script> 
@endpush