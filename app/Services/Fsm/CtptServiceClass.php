<?php
// Last Modified Date: 19-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\Fsm;

use Illuminate\Http\Request;
use App\Models\Fsm\Ctpt;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Auth;
use DataTables;
use DB;
use App\Models\BuildingInfo\Building;
use App\Enums\CtptStatus;
use App\Enums\CtptStatusOperational;

class CtptServiceClass{


    public function fetchData(Request $request){
        $cwis_general = Ctpt::whereNull('deleted_at');
        return Datatables::of($cwis_general)
            ->filter(function ($query) use ($request) {
                if ($request->toilet_id) {
                    $query->where('id', '=',trim($request->toilet_id));
                }
                if ($request->name) {
                    $query->where('name', 'ILIKE', '%' .  trim($request->name) . '%');
                }
                if ($request->ward) {
                    $query->where('ward', '=', $request->ward);
                }
                if ($request->caretaker_name) {
                    $query->where('caretaker_name', 'ILIKE', '%' .  trim($request->caretaker_name) . '%');
                }
                if ($request->bin) {
                    $query->where('bin',$request->bin);
                }
                if ($request->type) {
                    $query->where('type', '=',$request->type);

                }
                if ($request->sanitary_supplies_disposal_facility) {
                    $query->where('sanitary_supplies_disposal_facility',$request->sanitary_supplies_disposal_facility);
                }

                if ($request->status) {
                    $query->where('status', $request->status);
                }
                })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['ctpt.destroy', $model->id]]);

                if (Auth::user()->can('Edit CT/PT General Information')) {
                    $content .= '<a title="Edit" href="' . action("Fsm\CtptController@edit", [$model->id]) .
                     '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View CT/PT General Information')) {
                    $content .= '<a title="View" href="' . action("Fsm\CtptController@show", [$model->id]) .
                    '"class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('View CT/PT History')) {
                    $content .= '<a title="History" href="' . action("Fsm\CtptController@history", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }
                if (Auth::user()->can('Delete CT/PT General Information')) {
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }
                if (Auth::user()->can('View CT/PT General Information on Map')) {
                    $content .= '<a title="Map" href="' . action("MapsController@index", ['layer' => 'toilets_layer', 'field' => 'id', 'val' => $model->id]) .
                    '" class="btn btn-info btn-sm mb-1"><i class="fa fa-map-marker"></i></a> ';
                }
                $content .= \Form::close();
                return $content;
            })
            ->editColumn('male_or_female_facility',function($model){
                $content = '<div style="display:flex;align-items: center;justify-content: space-between;align-content: center;">';
                if ($model->male_or_female_facility === true) {
                    $content .= 'Yes';
                } elseif ($model->male_or_female_facility === false) {
                    $content .= 'No';
                } else {
                    $content .= '<i class="fa fa-minus"></i>';
                }

                $content .= '</div>';
                return $content;
            })

            ->editColumn('handicap_facility',function($model){
                $content = '<div style="display:flex;align-items: center;justify-content: space-between;align-content: center;">';
                if ($model->handicap_facility === true) {
                    $content .= 'Yes';
                } elseif ($model->handicap_facility === false) {
                    $content .= 'No';
                } else {
                    $content .= '<i class="fa fa-minus"></i>';
                }
                $content .= '</div>';
                return $content;
            })

            ->editColumn('children_facility',function($model){
                $content = '<div style="display:flex;align-items: center;justify-content: space-between;align-content: center;">';
                if ($model->children_facility === true) {
                    $content .= 'Yes';
                } elseif ($model->children_facility === false) {
                    $content .= 'No';
                } else {
                    $content .= '<i class="fa fa-minus"></i>';
                }
                $content .= '</div>';
                return $content;
            })
            ->editColumn('sanitary_supplies_disposal_facility',function($model){
                $content = '<div style="display:flex;align-items: center;justify-content: space-between;align-content: center;">';
                if ($model->sanitary_supplies_disposal_facility === true) {
                    $content .= 'Yes';
                } elseif ($model->sanitary_supplies_disposal_facility === false) {
                    $content .= 'No';
                } else {
                    $content .= '<i class="fa fa-minus"></i>';
                }

                $content .= '</div>';
                return $content;
            })
            ->editColumn('status',function ($model){
                return CtptStatusOperational::getDescription($model->status);
               })
            ->rawColumns(['male_or_female_facility','handicap_facility','children_facility', 'sanitary_supplies_disposal_facility', 'action'])
            ->make(true);
    }

    public function storeCtptData($request)
    {

        $info = new ctpt();
        $info->type = $request->type ? $request->type : null;
        $info->name = $request->name ? $request->name : null;
        $info->ward = $request->ward ? $request->ward : null;
        $info->location_name = $request->location_name ? $request->location_name : null;
        $info->bin= $request->bin ? $request->bin : null;
        $info->owner= $request->owner ? $request->owner : null;
        $info->owning_institution_name= $request->owning_institution_name ? $request->owning_institution_name : null;
        $info->operator_or_maintainer= $request->operator_or_maintainer ? $request->operator_or_maintainer : null;
        $info->operator_or_maintainer_name= $request->operator_or_maintainer_name ? $request->operator_or_maintainer_name : null;
        $info->caretaker_name= $request->caretaker_name? $request->caretaker_name: null;
        $info->caretaker_gender= $request->caretaker_gender? $request->caretaker_gender: null;
        $info->caretaker_contact_number = $request->caretaker_contact_number ? $request->caretaker_contact_number : null;
        $info->no_of_hh_connected= $request->no_of_hh_connected ? $request->no_of_hh_connected : null;
        $info->no_of_male_users= $request->no_of_male_users ? $request->no_of_male_users : null;
        $info->no_of_female_users= $request->no_of_female_users ? $request->no_of_female_users : null;
        $info->no_of_children_users= $request->no_of_children_users ? $request->no_of_children_users : null;
        $info->no_of_pwd_users= $request->no_of_pwd_users ? $request->no_of_pwd_users : null;
        $info->total_no_of_toilets= $request->total_no_of_toilets ? $request->total_no_of_toilets : null;
        $info->total_no_of_urinals= $request->total_no_of_urinals ? $request->total_no_of_urinals : null;
        $info->access_frm_nearest_road= $request->access_frm_nearest_road ? $request->access_frm_nearest_road : null;
        $info->separate_facility_with_universal_design= $request->separate_facility_with_universal_design ?? null;
        $info->male_or_female_facility= $request->male_or_female_facility ?? null;
        $info->handicap_facility = $request->handicap_facility ?? null;
        $info->children_facility= $request->children_facility ?? null;
        $info->male_seats= $request->male_seats? $request->male_seats: null;
        $info->female_seats= $request->female_seats? $request->female_seats: null;
        $info->pwd_seats= $request->pwd_seats? $request->pwd_seats: null;
        $info->sanitary_supplies_disposal_facility = $request->sanitary_supplies_disposal_facility ?? null;
        $info->status= $request->status ?? null;
        $info->indicative_sign= $request->indicative_sign ?? null;
        $info->fee_collected= $request->fee_collected ?? null;
        $info->amount_of_fee_collected= $request->amount_of_fee_collected ? $request->amount_of_fee_collected : null;
        $info->frequency_of_fee_collected= $request->frequency_of_fee_collected ? $request->frequency_of_fee_collected : null;
        $centroid = DB::select(DB::raw("SELECT (ST_AsText(st_centroid(st_union(geom)))) AS central_point FROM building_info.buildings WHERE bin = '$request->bin'"));
        $info->geom = DB::raw("ST_GeomFromText('".$centroid[0]->central_point."', 4326)");
       
        $info->save();
        return redirect('fsm/ctpt')->with('success','Public / Community Toilets Added Successfully ');
    }

    public function updateCtptData($request, $id)
    {

        $info = Ctpt::find($id);
        if ($info) {
            $info->type = $request->type ?? null;
            $info->name = $request->name ?? null;
            $info->ward = $request->ward ?? null;
            $info->location_name = $request->location_name ?? null;
            $info->bin = $request->bin ?? null;
            $info->owner = $request->owner ?? null;
            $info->operator_or_maintainer = $request->operator_or_maintainer ?? null;
            $info->caretaker_name = $request->caretaker_name ?? null;
            $info->caretaker_gender = $request->caretaker_gender ?? null;
            $info->caretaker_contact_number = $request->caretaker_contact_number ?? null;
            $info->owning_institution_name= $request->owning_institution_name ?? null;
            $info->operator_or_maintainer= $request->operator_or_maintainer ?? null;
            $info->no_of_hh_connected= $request->no_of_hh_connected ?? null;
            $info->no_of_male_users= $request->no_of_male_users ?? null;
            $info->no_of_female_users= $request->no_of_female_users ?? null;
            $info->no_of_children_users= $request->no_of_children_users ?? null;
            $info->no_of_pwd_users= $request->no_of_pwd_users ?? null;
            $info->total_no_of_toilets= $request->total_no_of_toilets ?? null;
            $info->total_no_of_urinals= $request->total_no_of_urinals ?? null;
            $info->access_frm_nearest_road = $request->access_frm_nearest_road ?? null;
            $info->separate_facility_with_universal_design = $request->separate_facility_with_universal_design ?? null;
            $info->male_or_female_facility = $request->male_or_female_facility ?? null;
            $info->handicap_facility = $request->handicap_facility ?? null;
            $info->children_facility = $request->children_facility ?? null;
            $info->male_seats = $request->male_seats ?? null;
            $info->female_seats = $request->female_seats ??  null;
            $info->pwd_seats = $request->pwd_seats ?? null;
            $info->sanitary_supplies_disposal_facility = $request->sanitary_supplies_disposal_facility ?? null;
            $info->status = $request->status ?? null;
            $info->indicative_sign = $request->indicative_sign ?? null;
            $info->fee_collected = $request->fee_collected ?? null;
            $info->amount_of_fee_collected= $request->amount_of_fee_collected ?? null;
            $info->frequency_of_fee_collected= $request->frequency_of_fee_collected ?? null;
            $centroid = DB::select(DB::raw("SELECT (ST_AsText(st_centroid(st_union(geom)))) AS central_point FROM building_info.buildings WHERE bin = '$request->bin'"));
            $info->geom = DB::raw("ST_GeomFromText('".$centroid[0]->central_point."', 4326)");
            $info->save();

            return redirect('fsm/ctpt')->with('success', 'Public / Community Toilets Updated Successfully');
        } else {
            return redirect('fsm/ctpt')->with('error', 'Failed to update CT / PT General info');
        }

    }

    public function exportData($data)
    {
        
        $name = $data['name'] ? $data['name'] : null;
        $type = $data['type'] ? $data['type'] : null;
        $ward = $data['ward'] ? $data['ward'] : null;
        $bin = $data['bin'] ? $data['bin'] : null;
        $status = $data['status'] ? $data['status'] : null;
        $caretaker_name = $data['caretaker_name'] ? $data['caretaker_name'] : null;
        $sanitary_supplies_disposal_facility = $data['sanitary_supplies_disposal_facility'] ? $data['sanitary_supplies_disposal_facility'] : null;

        $columns = ['ID','Toilet Name','Toilet Type',
        'Ward', 'Location Name', 'House Number', 'Owning Institution', 'Operator and Maintained By','Caretaker Name','Caretaker Gender', 'Caretaker Contact', 'No. of Households Served', 'No. of Male Users', 'No. of Female Users','No. of People with Disability Users', 'Total Number of Seats ', 'Total Number of Urinals',
        'Adherence with Universal Design Principles ', 'Distance from Nearest Road (in m)', 'Separate Facility for Male and Female','No. of Seats for Male Users','No. of Seats for Female Users ','Separate Facility for People with Disability','No. of seats for  People with Disability ','Separate Facility for Children','No. of Children Users', 'Sanitary Supplies and Disposal Facilities', 'Status', 'Presence of Indicative Sign', 'Fee Collected', 'Amount of Fee Collected', 'Frequency of Fee Collected '];

        $query = Ctpt::select('id','name','type',
        'ward', 'location_name', 'bin','owner', 'operator_or_maintainer',
        'caretaker_name', 'caretaker_gender', 'caretaker_contact_number','no_of_hh_connected', 'no_of_male_users', 'no_of_female_users', 'no_of_pwd_users','total_no_of_toilets', 'total_no_of_urinals', 'separate_facility_with_universal_design', 'access_frm_nearest_road', 'male_or_female_facility','male_seats','female_seats','handicap_facility','pwd_seats', 'children_facility', 'no_of_children_users', 'sanitary_supplies_disposal_facility', 'status', 'indicative_sign', 'fee_collected','amount_of_fee_collected', 'frequency_of_fee_collected')->whereNull('deleted_at');

       
        if(!empty($bin)){
            $query->where('bin',$bin);
        }
        if(!empty($name)){
            $query->where('name', 'ILIKE', '%' .  trim($name) . '%');
        }
        if(!empty($type)){
            $query->where('type', $type);
        }
        if(!empty($ward)){
            $query->where('ward', '=', $ward);
        }
        if(!empty($status)){
            $query->where('status', '=', $status);
        }
        if(!empty($caretaker_name)){
            $query->where('caretaker_name', 'ILIKE', '%' .  trim($caretaker_name) . '%');
        }
        if(!empty($sanitary_supplies_disposal_facility)){

            $query->where('sanitary_supplies_disposal_facility',$sanitary_supplies_disposal_facility);
        }
        $style = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(13)
        ->setBackgroundColor(Color::rgb(228, 228, 228))
        ->build();

    $writer = WriterFactory::create(Type::CSV);

    $writer->openToBrowser('Public or Community Toilets.CSV')
        ->addRowWithStyle($columns, $style); //Top row of excel

    $query->chunk(5000, function ($info) use ($writer) {

        foreach($info as $ctpt) {
            $values = [];
            $values[] = $ctpt->id;
            $values[] = $ctpt->name;
            $values[] = $ctpt->type;
            $values[] = $ctpt->ward;
            $values[] = $ctpt->location_name;
            $values[] = $ctpt->bin;
            $values[] = $ctpt->owner;
            $values[] = $ctpt->operator_or_maintainer;
            $values[] = $ctpt->caretaker_name;
            $values[] = $ctpt->caretaker_gender;
            $values[] = $ctpt->caretaker_contact_number;
            $values[] = $ctpt->no_of_hh_connected;
            $values[] = $ctpt->no_of_male_users;
            $values[] = $ctpt->no_of_female_users;
            $values[] = $ctpt->no_of_pwd_users;
            $values[] = $ctpt->total_no_of_toilets;
            $values[] = $ctpt->total_no_of_urinals;
            $values[] = CtptStatus::getDescription($ctpt->separate_facility_with_universal_design);
            $values[] = $ctpt->access_frm_nearest_road;
            $values[] = CtptStatus::getDescription($ctpt->male_or_female_facility);
            $values[] = $ctpt->male_seats;
            $values[] = $ctpt->female_seats;
            $values[] = CtptStatus::getDescription($ctpt->handicap_facility);
            $values[] = $ctpt->pwd_seats;
            $values[] = CtptStatus::getDescription($ctpt->children_facility);
            $values[] = $ctpt->no_of_children_users;
            $values[] = CtptStatus::getDescription($ctpt->sanitary_supplies_disposal_facility);
            $values[] = CtptStatusOperational::getDescription($ctpt->status);
            $values[] = CtptStatus::getDescription($ctpt->indicative_sign);
            $values[] = CtptStatus::getDescription($ctpt->fee_collected);
            $values[] = $ctpt->amount_of_fee_collected;
            $values[] = $ctpt->frequency_of_fee_collected;
            

            $writer->addRow($values);
        }

    });

    $writer->close();
    }

}
