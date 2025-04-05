<!-- Last Modified Date: 19-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
<div class="panel-group m-3" id="accordion" role="tablist" aria-multiselectable="true">
    @foreach ($subCategory_titles as $key=>$subCategory_title)
            {{-- <h3 class="panell-title" text-align="center">
                            <centre><a>{!! $subCategory_title !!}</a></centre>
            </h3> --}}

        <div class="panel panel-default">
        @for($i=0; $i<$param_listcount ; $i++)
            @foreach ($param_titles[$i] as $param_title)
                <div class="card">
                     <div class="card-header" id="headingOne">
                     <h5 class="mb-0">
        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseparam{{$i}}" aria-expanded="true" aria-controls="collapseOne">
                      {!! $param_title !!}
                        </a>
                    </h5>
                         </div>

                <div id="collapseparam{{$i}}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-bordered" width='100%'>
                            <!-- <tr>
                                <th colspan=4 width='50%' style="margin-left:auto; margin-right:auto;">Parameter: {!! $param_title !!}</th>
                            </tr> -->
                            <tr class="" width='100%'>
                                <th width='50%'>Indicators</th>
                                <!-- <th width='10%'>Unit</th> -->
                                <th width='10%'>Outcome</th>
                                <th width='10%'>Data Type</th>
                                <!-- <th width='10%'>Data Periodicity</th> -->
                                <th width='20%'>Data</th>
                            </tr>

                            @foreach ($param_details[$i] as $param_detail)
                            <tr class="" width='100%'>
                                <td width='50%'>{{ $param_detail->assmntmtrc_dtpnt }}
                                    @if($param_detail->remark != '' )
                                        <i class="ml-1 fa-solid fa-circle-info" data-toggle="tooltip" data-placement="right" title="{{ $param_detail->remark }}" style="color: #17a2b8; font-size: 16px;"></i>
                                    @endif
                                    @if ( $param_detail->remark == '' &&  $param_detail->data_value == '')

                                    <i class="fa-solid fa-square-pen fa-lg" style="color: #FFD43B;" data-toggle="tooltip" data-placement="right" title="Enter value"></i>
                                    @endif
                                </td>
                                <!-- <td width='10%'>{{ $param_detail->unit }}</td> -->
                                <td width='10%'>{{ $param_detail->co_cf }}</td>
                                <td width='10%' id="displayQuantitative">{{ isset($_GET['displayText']) ? $_GET['displayText'] : $param_detail->answer_type }}</td>
                                <!-- <td width='10%'>{{ $param_detail->data_periodicity }}</td> -->
                                <td width='20%'>
                                    <input class="form-control"
                                    placeholder="{{ isset($_GET['placeholder']) ? $_GET['placeholder'] : 'Enter value in percent' }}"
                                    name="data_value[]"
                                    type="{{ $param_detail->data_type }}"
                                    value="{{ $param_detail->data_value }}"
                                    {{ $param_detail->data_type_req }}
                                    @if ($param_detail->data_value != 'NA' && $param_detail->data_value != '') disabled @endif> </td>
                                <!-- <td width='20%'>{!! Form::text('data_value[]',$param_detail->data_value,['class' => 'form-control', 'placeholder' => 'Enter data here ..']) !!}</td> -->
                            </tr>
                            @endforeach
                            </table>
                    </div>
                </div>
                    </div>
            @endforeach
            <br/>
        @endfor
        </div>
    @endforeach
    @if(!Auth::user()->hasRole('Municipality - Executive'))
    <div class="footer">
        {!! Form::hidden('year', $year) !!}
        {!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
    </div>
    @endif
</div>





