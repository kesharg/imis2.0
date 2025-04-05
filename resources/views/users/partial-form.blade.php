<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
<div class="card-body">
    <div class="form-group row required">
        {!! Form::label('name','Full Name',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('name',null,['class' => 'form-control', 'placeholder' => 'Full Name']) !!}
        </div>
    </div>
    <div class="form-group row required">
        {!! Form::label('Gender',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('gender', ["M"=>"Male", "F"=>"Female"], null, ['class' => 'form-control', 'placeholder' => 'Gender']) !!}
        </div>
    </div>
    <div class="form-group row required">
        {!! Form::label('username',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('username',null,['class' => 'form-control', 'placeholder' => 'Username']) !!}
        </div>
    </div>
    <div class="form-group row required">
        {!! Form::label('email',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('email',null,['class' => 'form-control', 'placeholder' => 'Email']) !!}
        </div>
    </div>
    <div class="form-group row required">
        {!! Form::label('password',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('password_confirmation','Confirm Password',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
        </div>
    </div>
    @if(Auth::user()->hasRole('Municipality - Sanitation Department'))
        <div class="form-group row">
            {!! Form::label('User Type',null,['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                {!! Form::select('user_type', ["Service Provider"=>"Service Provider", "Treatment Plant"=>"Treatment Plant"], null, ['class' => 'form-control userType', 'placeholder' => 'User Type']) !!}
            </div>
        </div>
    @else
        <div class="form-group row required">
            {!! Form::label('User Type',null,['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                {!! Form::select('user_type', ["Municipality"=>"Municipality", "Service Provider"=>"Service Provider", "Treatment Plant"=>"Treatment Plant", "Help Desk"=>"Help Desk", "External"=>"External","Solid Waste"=>"Solid Waste Management"], null, ['class' => 'form-control userType', 'placeholder' => 'User Type']) !!}
            </div>
        </div>
    @endif
    <div class="form-group row required">
        {!! Form::label('roles',null,['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3 roles-select">
        </div>
    </div>

    <div class="form-group row" id="treatment_plant">
        {!! Form::label('treatment_plant_id','Treatment Plant',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('treatment_plant_id', $treatmentPlants, null, ['class' => 'form-control ', 'placeholder' => 'Treatment Plant']) !!}
        </div>
    </div>
    <div class="form-group row required" id="service_provider">
        {!! Form::label('service_provider_id','Service Provider',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('service_provider_id', $serviceProviders, null, ['class' => 'form-control', 'placeholder' => 'Service Provider']) !!}
        </div>
    </div>
    <div class="form-group row" id="help_desk">
        {!! Form::label('help_desk_id','Help Desk',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('help_desk_id', $helpDesks, null, ['class' => 'form-control ', 'placeholder' => 'Help Desk']) !!}
        </div>
    </div>
    <div class="form-group row" id="transfer_station">
        {!! Form::label('transfer_station_id','Transfer Station',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('transfer_station_id', $transferStations, null, ['class' => 'form-control ', 'placeholder' => 'Transfer station']) !!}
        </div>
    </div>
    <div class="form-group row" id="landfill_site">
        {!! Form::label('landfill_site_id','Landfill Site',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('landfill_site_id', $landfillSites, null, ['class' => 'form-control ', 'placeholder' => 'Landfill site']) !!}
        </div>
    </div>
    <div class="form-group row required">
        {!! Form::label('status','Status',['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::select('status', $status, null, ['class' => 'form-control ', 'placeholder' => 'Status']) !!}
        </div>
    </div>
</div><!-- /.box-body -->
<div class="card-footer">
    <a href="{{ action('Auth\UserController@index') }}" class="btn btn-info">Back to List</a>
    {!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
</div><!-- /.card-footer -->
@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){
      let user_type = $('.userType').val();
           $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        $.ajax({
          url: '{!! url("auth/roles/list-roles") !!}',
          type: "GET",
          data: {
                    user_type: user_type,
                    @if(old("roles"))
                        roles : '<?php echo json_encode(old("roles"));?>'
                    @elseif(isset($user))
                        roles : '<?php echo json_encode($user->roles);?>'
                    @endif
                },
            cache: true,
            success: function(html) {
                $('.roles-select').html(html);
                $('.chosen-select').select2().on('change',function (e){
                    onRoleSelect(e);
            });
            }
          });
      // For inital load or when page loads with error
      switch ($(".userType option:selected").val()){
          case 'Service Provider':
              $('#service_provider').show();
              $('#treatment_plant').hide();
              $('#help_desk').hide();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              $('#service_provider').prop("required", true);
              break;
          case 'Municipality':
              $('#service_provider').hide();
              $('#treatment_plant').hide();
              $('#help_desk').hide();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              break;
          case 'External':
              $('#service_provider').hide();
              $('#treatment_plant').hide();
              $('#help_desk').hide();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              break;
          case 'Treatment Plant':
              $('#service_provider').hide();
              $('#treatment_plant').show();
              $('#help_desk').hide();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              break;
          case 'Help Desk':
              $('#service_provider').hide();
              $('#treatment_plant').hide();
              $('#help_desk').show();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              break;
          default:
              $('#service_provider').hide();
              $('#treatment_plant').hide();
              $('#help_desk').hide();
              $('#transfer_station').hide();
              $('#landfill_site').hide();
              break;
      }
      // Roles

      $('.userType').change(function() {
           let user_type = $('.userType').val();
           $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
          $.ajax({
          url: '{!! url("auth/roles/list-roles") !!}',
          type: "GET",
          data: {
                    user_type: user_type,

                },
            cache: true,
            success: function(html) {
                $('.roles-select').html(html);
                $('.chosen-select').select2().on('change',function (e){
                    onRoleSelect(e);
                });
            }
          });
          $(".userType option:selected").each(function () {
              switch (this.value) {
                  case 'Service Provider':
                      $('#service_provider').show()
                      $('#treatment_plant').hide();
                      $('#help_desk').hide();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      $('#service_provider').prop("required", true);
                      break;
                  case 'Municipality':
                      $('#service_provider').hide();
                      $('#treatment_plant').hide();
                      $('#help_desk').hide();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      break;
                  case 'External':
                      $('#service_provider').hide();
                      $('#treatment_plant').hide();
                      $('#help_desk').hide();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      break;
                  case 'Treatment Plant':
                      $('#service_provider').hide();
                      $('#treatment_plant').show();
                      $('#help_desk').hide();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      break;
                  case 'Help Desk':
                      $('#service_provider').hide();
                      $('#treatment_plant').hide();
                      $('#help_desk').show();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      break;
                  default:
                      $('#service_provider').hide();
                      $('#treatment_plant').hide();
                      $('#help_desk').hide();
                      $('#transfer_station').hide();
                      $('#landfill_site').hide();
                      break;
              }
          });
      });

      function onRoleSelect() {
          var selected = $('.chosen-select').select2('data');
          if (['Solid Waste - Transfer Station','Solid Waste - Landfill'].every(role => selected.some(e => e.text === role))){
              $('#transfer_station').show();
              $('#landfill_site').show();
          } else if (selected.filter(function(e) { return e.text === 'Solid Waste - Transfer Station'; }).length > 0) {
              $('#transfer_station').show();
              $('#landfill_site').hide();
          } else if (selected.filter(function(e) { return e.text === 'Solid Waste - Landfill'; }).length > 0) {
              $('#transfer_station').hide();
              $('#landfill_site').show();
          } else {
              $('#transfer_station').hide();
              $('#landfill_site').hide();
          }
      }
  });
</script>
@endpush
