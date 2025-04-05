@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="card card-info">
	<div class="card-header bg-transparent">
		<a href="{{ action('LayerInfo\LowIncomeCommunityController@index') }}" class="btn btn-info">Back to List</a>
	</div><!-- /.card-header -->
	<div class="card-body">
		<ul>
			@foreach($lic->revisionHistory as $history)
			@if($history->key == 'created_at' && !$history->old_value)
                            @if($history->userResponsible())
                            <li>{{ $history->userResponsible()->name }} created this resource at {{ $history->newValue() }}</li>
                            @endif
                        @else
                            @if($history->userResponsible() && $history->fieldName() != 'geom')
                            <li>{{ $history->userResponsible()->name }} changed {{ $history->fieldName() }} from {{ $history->oldValue() }} to {{ $history->newValue() }} on {{ $history->created_at }}</li>
                            @endif
                        @endif
			@endforeach
		</ul>
	</div><!-- /.card-body -->
</div><!-- /.card -->
@stop

