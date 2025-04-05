<?php

namespace App\Services\Fsm;

use Illuminate\Http\Request;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Auth;
use DataTables;
use DB;
use App\Models\Fsm\Hotspots;
use App\Enums\HotspotDisease;


class HotspotServiceClass
{
    /**
    * Fetch and format data for DataTables.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function fetchData($request)
    {
        $HotHotspot = Hotspots::whereNull('deleted_at');



        return Datatables::of($HotHotspot)
            ->filter(function ($query) use ($request) {
                if ($request->disease) {
                    $query->where('disease', $request->disease);
                }
                if ($request->hotspot_location) {
                    $query->where('hotspot_location', $request->hotspot_location);
                }
            })
            ->editColumn('disease', function ($model) {
                switch ($model->disease) {
                    case HotspotDisease::Cholera:
                        return 'Cholera';
                    case HotspotDisease::Diarrhea:
                        return 'Diarrhea';
                    case HotspotDisease::Dysentery:
                        return 'Dysentery';
                    case HotspotDisease::HepatitisA:
                        return 'Hepatitis A';
                    case HotspotDisease::Typhoid:
                        return 'Typhoid';
                    case HotspotDisease::Polio:
                        return 'Polio';
                    default:
                        return 'Unknown';
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['hotspots.destroy', $model->id]]);

                if (Auth::user()->can('Edit Hotspot Identification')) {
                    $content .= '<a title="Edit" href="' . action("Fsm\HotspotController@edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Hotspot Identification')) {
                    $content .= '<a title="History" href="' . action("Fsm\HotspotController@history", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                    $content .= '<a title="View" href="' . action("Fsm\HotspotController@show", [$model->id]) . '"class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('Delete Hotspot Identification')) {
                    $content .= '<a title="Delete" class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= \Form::close();
                return $content;
            })
            ->rawColumns(['disease', 'action'])
            ->make(true);
    }
    /**
    * Store data for a Hotspots record.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function storeData($request)
    {
        $Hotspots = new Hotspots();
        $fields = ['hotspot_location', 'date', 'disease', 'notes'];
        $caseFields = ['male_cases', 'female_cases', 'other_cases'];
        $fatalityFields = ['male_fatalities', 'female_fatalities', 'other_fatalities'];
        foreach ($fields as $field) {
            $Hotspots->$field = $request->$field ?? null;
        }

        $totalCases = 0;
        foreach ($caseFields as $field) {
            $Hotspots->$field = $request->$field ?? null;
            $totalCases += $Hotspots->$field ?? 0;
        }
        $totalFatalities = 0;
        foreach ($fatalityFields as $field) {
            $Hotspots->$field = $request->$field ?? null;
            $totalFatalities += $Hotspots->$field ?? 0;
        }
        $Hotspots->no_of_cases = $totalCases;
        $Hotspots->no_of_fatalities = $totalFatalities;
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
                $Hotspots->ward = $ward[0]->ward;
                $Hotspots->geom = $request->geom ? DB::raw("ST_Multi(ST_GeomFromText('" . $request->geom . "', 4326))") : null;
                $Hotspots->save();
                return redirect('fsm/hotspots')->with('success', 'Waterborne Hotspot created successfully');
            } else {
                return redirect('fsm/hotspots/create')->with('error', 'The selected area should be within the Mahalaxmi Boundary');
            }
        } else {
            return redirect('fsm/hotspots/create')->with('error', 'Failed to Create Hotspot Identification');
        }
    }

    /**
    * Update data for a Hotspots record.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id The ID of the Hotspots record to update
    * @return \Illuminate\Http\RedirectResponse
    */
    public function updateData($request, $id)
    {
        $Hotspots = Hotspots::find($id);

        if ($Hotspots) {
            $fields = ['hotspot_location', 'date', 'notes', 'disease', 'male_cases', 'female_cases', 'other_cases'];
            $caseFields = ['male_cases', 'female_cases', 'other_cases'];
            $fatalityFields = ['male_fatalities', 'female_fatalities', 'other_fatalities'];

            foreach ($fields as $field) {
                $Hotspots->$field = $request->$field ?? null;
            }

            $totalCases = 0;
            foreach ($caseFields as $field) {
                $Hotspots->$field = $request->$field ?? null;
                $totalCases += $Hotspots->$field ?? 0;
            }

            $Hotspots->no_of_cases = $totalCases;

            foreach ($fatalityFields as $field) {
                $Hotspots->$field = $request->$field ?? null;
            }

            $totalFatalities = 0;
            foreach ($fatalityFields as $field) {
                $Hotspots->$field = $request->$field ?? null;
                $totalFatalities += $Hotspots->$field ?? 0;
            }

            $Hotspots->no_of_fatalities = $totalFatalities;

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
                    $Hotspots->ward = $ward[0]->ward;
                    $Hotspots->geom = DB::raw("ST_Multi(ST_GeomFromText('" . $request->geom . "', 4326))");
                    $Hotspots->save();
                } else {
                    return redirect('fsm/hotspots/' . $id . '/edit')->with('error', 'The selected area should be within the Mahalaxmi Boundary');
                }
            }
            return redirect('fsm/hotspots')->with('success', 'Waterborne Hotspot updated successfully ');
        } else {
            return redirect('fsm/hotspots')->with('error', 'Failed to update Hotspot Identifications');
        }
    }

    /**
    * Display details of a Hotspots record.
    *
    * @param  int  $id The ID of the Hotspots record to display
    * @return \Illuminate\Contracts\View\View
    */
    public function showData($id)
    {
        $Hotspots = Hotspots::find($id);
        if ($Hotspots) {
            $enumValue =  (int)$Hotspots->disease;
            $diseaseName = HotspotDisease::getDescription($enumValue);
            $page_title = "Waterborne Hotspot Details";
            $geomArr = DB::select("SELECT ST_X(ST_AsText(ST_Centroid(ST_Centroid(geom)))) AS long, ST_Y(ST_AsText(ST_Centroid(ST_Centroid(geom)))) AS lat, ST_AsText(geom) AS geom FROM public_health.waterborne_hotspots WHERE id = $id");

            $geom = ($geomArr[0]->geom);
            $lat = $geomArr[0]->lat;
            $long = $geomArr[0]->long;


            return view('fsm.hotspots.show', compact('page_title', 'Hotspots', 'geom', 'lat', 'long', 'diseaseName'));
        } else {
            abort(404);
        }
    }
    
    /**
    * Export Hotspots data to a CSV file.
    *
    * @param  array  $data The data containing search criteria
    * @return void
    */
    public function exportData($data)
    {

        $searchData = $data['searchData'] ? $data['searchData'] : null;
        $disease = $data['disease'] ? $data['disease'] : null;
        $hotspot_location = $data['hotspot_location'] ? $data['hotspot_location'] : null;
        $columns = ['ID', 'Infected Disease', 'Hotspot Location', 'Date', 'No. Of Cases', 'No of Fatalities'];
        $query = Hotspots::select('id', 'disease', 'hotspot_location', 'date', 'no_of_cases', 'no_of_fatalities')->whereNull('deleted_at');
        if (!empty($disease)) {
            $query->where('disease', $disease);
        }
        if (!empty($hotspot_location)) {
            $query->where('hotspot_location', $hotspot_location);
        }
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();
        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Hotspots.CSV')
            ->addRowWithStyle($columns, $style);
        $query->chunk(5000, function ($hotspots) use ($writer) {
            foreach ($hotspots as $hotspot) {
                $values = [];
                $values[] = $hotspot->id;
                $values[] = ucwords(HotspotDisease::getDescription($hotspot->disease));
                $values[] = $hotspot->hotspot_location;
                $values[] = $hotspot->date;
                $values[] = $hotspot->no_of_cases;
                $values[] = $hotspot->no_of_fatalities;
                $writer->addRow($values);
            }
        });
        $writer->close();
    }
}
