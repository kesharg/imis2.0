<?php

namespace App\Services\LayerInfo;

use Illuminate\Http\Request;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Auth;
use DataTables;
use DB;
use App\Models\LayerInfo\LowIncomeCommunity;

class LowIncomeCommunityServiceClass
{
    /**
    * Fetch and format data for DataTables.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function fetchData($request)
    {
        $lic = LowIncomeCommunity::whereNull('deleted_at');



        return Datatables::of($lic)
            ->filter(function ($query) use ($request) {
                if ($request->community_name) {
                    $query->where('community_name','ILIKE','%'.$request->community_name.'%');
                }

            })
            
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['low-income-communities.destroy', $model->id]]);

                if (Auth::user()->can('Edit Low Income Community')) {
                    $content .= '<a title="Edit" href="' . action("LayerInfo\LowIncomeCommunityController@edit", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Low Income Community')) {
                    $content .= '<a title="History" href="' . action("LayerInfo\LowIncomeCommunityController@history", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                    $content .= '<a title="View" href="' . action("LayerInfo\LowIncomeCommunityController@show", [$model->id]) . '"class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('View Low Income Community On Map')) {
                    $content .= '<a title="Map" href="' . action("MapsController@index", ['layer' => 'low_income_communities_layer', 'field' => 'id', 'val' => $model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-map-marker"></i></a> ';
                }
                if (Auth::user()->can('Delete Low Income Community')) {
                    $content .= '<a title="Delete" class="delete btn btn-danger btn-sm mb-1">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }
    /**
    * Store data for a Low income community record.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function storeData($request)
    {
        $lic = new LowIncomeCommunity();
        $lic->population_total = $request->population_total;
        $lic->number_of_households = $request->number_of_households;
        $lic->population_male = $request->population_male;
        $lic->population_female = $request->population_female;
        $lic->population_others = $request->population_others;
        $lic->no_of_septic_tank = $request->no_of_septic_tank;
        $lic->no_of_holding_tank = $request->no_of_holding_tank;
        $lic->no_of_pit = $request->no_of_pit;
        $lic->no_of_sewer_connection = $request->no_of_sewer_connection;
        $lic->no_of_buildings = $request->no_of_buildings;
        $lic->community_name = $request->community_name;
        $lic->no_of_community_toilets = $request->no_of_community_toilets;

        if (!empty($request->geom)) {
            
            
            $mahalaxmigeom = DB::table('layer_info.citypolys')->where('id', 1)->value('geom');
            $contains = DB::select(
                "SELECT ST_Contains(?, ST_GeomFromText(?, 4326)) as contains",
                [$mahalaxmigeom, $request->geom]
            )[0]->contains;

            if (!empty($contains) && $contains === true) {
                $ward=DB::select("SELECT w.ward
                FROM layer_info.wards w, ST_Intersects(w.geom, ST_GeomFromText('" . $request->geom . "', 4326)) 
                 ORDER BY
                    ST_Area(ST_Intersection(w.geom, ST_GeomFromText('" . $request->geom . "', 4326))) DESC
                LIMIT 1");
               
                $lic->geom = $request->geom ? DB::raw("ST_Multi(ST_GeomFromText('" . $request->geom . "', 4326))") : null;
                $lic->save();
                return redirect('layer-info/low-income-communities')->with('success', 'Low Income Community added successfully');
            } else {
                return redirect('layer-info/low-income-communities/create')->with('error', 'The selected area should be within the Municipality Boundary');
            }
        } else {
            return redirect('layer-info/low-income-communities/create')->with('error', 'Failed to Add Low Income Community');
        }
    }

    /**
    * Update data for a Low income community record.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id The ID of the Low income community record to update
    * @return \Illuminate\Http\RedirectResponse
    */
    public function updateData($request, $id)
    {
        $lic = LowIncomeCommunity::find($id);

        if ($lic) {
            
            $lic->population_total = $request->population_total;
            $lic->number_of_households = $request->number_of_households;
            $lic->population_male = $request->population_male;
            $lic->population_female = $request->population_female;
            $lic->population_others = $request->population_others;
            $lic->no_of_septic_tank = $request->no_of_septic_tank;
            $lic->no_of_holding_tank = $request->no_of_holding_tank;
            $lic->no_of_pit = $request->no_of_pit;
            $lic->no_of_sewer_connection = $request->no_of_sewer_connection;
            $lic->no_of_buildings = $request->no_of_buildings;
            $lic->community_name = $request->community_name;
            $lic->no_of_community_toilets = $request->no_of_community_toilets;
            // Check if 'geom' is provided in the request
            if (!empty($request->geom)) {
                $mahalaxmigeom = DB::table('layer_info.citypolys')->where('id', 1)->value('geom');
                $contains = DB::select(
                    "SELECT ST_Contains(?, ST_GeomFromText(?, 4326)) as contains",
                    [$mahalaxmigeom, $request->geom]
                )[0]->contains;

                if (!empty($contains) && $contains === true) {
                    $ward=DB::select("SELECT w.ward
                    FROM layer_info.wards w, ST_Intersects(w.geom, ST_GeomFromText('" . $request->geom . "', 4326)) 
                     ORDER BY
                        ST_Area(ST_Intersection(w.geom, ST_GeomFromText('" . $request->geom . "', 4326))) DESC
                    LIMIT 1");
                    $lic->geom = DB::raw("ST_Multi(ST_GeomFromText('" . $request->geom . "', 4326))");
                    $lic->save();
                } else {
                    return redirect('layer-info/low-income-communities/' . $id . '/edit')->with('error', 'The selected area should be within the Municipality Boundary');
                }
            }
            return redirect('layer-info/low-income-communities')->with('success', 'Low Income Community updated successfully ');
        } else {
            return redirect('layer-info/low-income-communities')->with('error', 'Failed to update Low Income Community');
        }
    }

    /**
    * Display details of a Low income community record.
    *
    * @param  int  $id The ID of the Low income community record to display
    * @return \Illuminate\Contracts\View\View
    */
    public function showData($id)
    {
        $lic = LowIncomeCommunity::find($id);
        if ($lic) {
            $page_title = "Low Income Community Details";
            $geomArr = DB::select("SELECT ST_X(ST_AsText(ST_Centroid(ST_Centroid(geom)))) AS long, ST_Y(ST_AsText(ST_Centroid(ST_Centroid(geom)))) AS lat, ST_AsText(geom) AS geom FROM layer_info.low_income_communities WHERE id = $id");
            $geom = ($geomArr[0]->geom);
            $lat = $geomArr[0]->lat;
            $long = $geomArr[0]->long;
            return view('layer-info/low-income-communities.show', compact('page_title', 'lic', 'geom', 'lat', 'long'));
        } else {
            abort(404);
        }
    }
    
    /**
    * Export Low income community data to a CSV file.
    *
    * @param  array  $data The data containing search criteria
    * @return void
    */
    public function exportData($data)
    {

        $searchData = $data['searchData'] ? $data['searchData'] : null;
        $community_name = $data['community_name'] ? $data['community_name'] : null;
        $columns = ['ID', 'Community Name', 'No. of Buildings', 'Population', 'No. Of Households', 'Male Population',
            'Female Population', 'Other Population', 'No. of Septic Tanks', 'No. of Holding Tanks', 'No. of Pit', 'No. of Sewer Connections',
            'No. of Community Toilets'];
        $query = LowIncomeCommunity::select('id', 'community_name', 'no_of_buildings', 'population_total', 'number_of_households', 
                'population_male', 'population_female', 'population_others', 'no_of_septic_tank', 'no_of_holding_tank',
                'no_of_pit', 'no_of_sewer_connection', 'no_of_community_toilets')->whereNull('deleted_at');
        if (!empty($community_name)) {
            $query->where('community_name', $community_name);
        }
      
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();
        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Low Income Community.CSV')
            ->addRowWithStyle($columns, $style);
        $query->chunk(5000, function ($lics) use ($writer) {
            foreach ($lics as $lic) {
                $values = [];
                $values[] = $lic->id;
                $values[] = $lic->community_name;
                $values[] = $lic->no_of_buildings;
                $values[] = $lic->population_total;
                $values[] = $lic->number_of_households;
                $values[] = $lic->population_male;
                $values[] = $lic->population_female;
                $values[] = $lic->population_others;
                $values[] = $lic->no_of_septic_tank;
                $values[] = $lic->no_of_holding_tank;
                $values[] = $lic->no_of_pit;
                $values[] = $lic->no_of_sewer_connection;
                $values[] = $lic->no_of_community_toilets;
                
                $writer->addRow($values);
            }
        });
        $writer->close();
    }
}
