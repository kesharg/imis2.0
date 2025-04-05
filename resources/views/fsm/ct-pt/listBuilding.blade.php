@extends('layouts.dashboard')
@section('title', $page_title)
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
          <a href="{{ action('Fsm\CtptController@index') }}" class="btn btn-info">Back to the toilet List</a>
          @can('View Buildings connect to CT/PT')
          <a href="{{ action('Fsm\CtptController@addBuildings', ['id' => $toilet->id]) }}" class="btn btn-info">Add Buildings to this Toilet</a>
          @endcan
        </div><!-- /.card-header -->
        <div class="card-body">
          <table id="data-table" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>House Number </th>
                <th>Tax Code</th>
                <th>Structure Type</th>
                <th>Estimated Area of the Building</th>
                <th>Functional Use</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(!($buildings->isEmpty()))
                @foreach($buildings as $building)
                <tr>
                  <td>{{ $building->bin }}</td>
                  <td>{{ $building->tax_id }}</td>
                  <td>{{ $building->StructureType->type }}</td>
                  <td>{{ $building->estimated_area }}</td>
                  <td>{{ $building->functionalUse->name }}</td>
                  <td>
                  {!! Form::open(['method' => 'DELETE', 'action' => ['Fsm\ContainmentController@deleteBuilding', $containment->id, $building->bin]]) !!}
                    @can('Make Building of Containment Main')
                  {{-- <!-- <a title="Make Main Building" href="{{action("ContainmentController@makeMainBuilding", [$containment->containcd, $building->bin])}}" class="btn btn-info btn-xs" {{ $building->pivot->main_building == true ? 'Disabled' : '' }}>Make Main</a> --> --}}
                    @endcan
                    @can('Delete Building from Containment')
                    <button title="Remove Connection of Containment From Building" type="submit" class="btn btn-info btn-xs delete">&nbsp;<i class="fa fa-trash"></i>&nbsp;</button>
                    @endcan
                  {!! Form::close() !!}
                  </td>
                </tr>
                @endforeach
              @else
                <tr>
                  <td valign="top" colspan="6">No Matching records found</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div><!-- /.card-body -->
      </div><!-- /.card -->
    </div><!-- /.col -->
  </div><!-- /.row -->

@stop

@push('scripts')
<script>
 $('.delete').on('click', function(e) {
         
         var form =  $(this).closest("form");
         event.preventDefault();
         Swal.fire({
           title: 'Are you sure?',
           text: "You won't be able to revert this!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes, delete it!'
         }).then((result) => {
           if (result.isConfirmed) {
             form.submit();
             Swal.fire(
               'Deleted!',
               'Your file has been deleted.',
               'success'
             )
           }
         })
     });
</script>
@endpush