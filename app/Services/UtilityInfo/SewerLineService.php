<?php
// Last Modified Date: 14-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\UtilityInfo;

use App\Models\UtilityInfo\SewerLine;
use App\Models\Fsm\TreatmentPlant;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use DB;
use Carbon\Carbon;
use Auth;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Yajra\DataTables\DataTables;

class SewerLineService {

    protected $session;
    protected $instance;

    /**
     * Constructs a new LandfillSite object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/


    }

    /**
     * Get all list of resource.
     *
     *
     * @return array[]|Collection
     */
    public function getAllData($data)
    {

        $sewerLinesData = SewerLine::select('*');

        return Datatables::of($sewerLinesData)
                ->filter(function ($query) use ($data) {
                if ($data['code']) {
                    $query->where('code',$data['code']);
                }
                if ($data['road_code']) {
                    $query->where('road_code', $data['road_code']);
                }
                if ($data['location']) {
                    $query->whereRaw('LOWER(location) LIKE ? ', [trim(strtolower($data['location']))]);
                }

            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['sewerlines.destroy', $model->code]]);

                if (Auth::user()->can('Edit Sewer')) {
                    $content .= '<a title="Edit" href="' . action("UtilityInfo\SewerLineController@edit", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }

                if (Auth::user()->can('View Sewer')) {
                    $content .= '<a title="Detail" href="' . action("UtilityInfo\SewerLineController@show", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }

                if (Auth::user()->can('View Sewer History')) {
                    $content .= '<a title="History" href="' . action("UtilityInfo\SewerLineController@history", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }

                if (Auth::user()->can('Delete Sewer')) {
                    $content .= '<a href="#" title="Delete"  class="delete btn btn-danger btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }

                if (Auth::user()->can('View Sewer On Map')) {
                    $content .= '<a title="Map" href="' . action("MapsController@index", ['layer' => 'sewerlines_layer', 'field' => 'code', 'val' => $model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-map-marker"></i></a> ';
                }

                $content .= \Form::close();
                return $content;
            })
            ->editColumn('tp_id', function ($model) {
                $treatmentPlant = TreatmentPlant::select('name')
                    ->where('id', $model->tp_id)
                    ->first();
                if ($treatmentPlant) {
                    return $treatmentPlant->name;
                }
                return null; // or any default value you prefer if treatment plant not found
            })
            ->make(true);
    }
    /**
     * Store or update a newly created resource in storage.
     *
     * @param character $code
     * @param array $data
     * @return bool
     */
    public function storeOrUpdate($code = null,$data)
    {
        if(empty($code)){

            $sewerLineTemp = DB::select("SELECT ST_AsText(geom) AS geom FROM sewerlines_temp");
            $geom = ($sewerLineTemp[0]->geom);
            $maxcode = SewerLine::withTrashed()->max('code');
            $maxcode = str_replace('S', '', $maxcode);
            $sewerLine = new SewerLine();
            $sewerLine->code = 'S' . sprintf('%04d', $maxcode + 1);
            $sewerLine->road_code = $data['road_code'] ? $data['road_code'] : null;
            $sewerLine->length = $data['length'] ? $data['length'] : null;
            $sewerLine->location = $data['location'] ? $data['location'] : null;
            $sewerLine->diameter = $data['diameter'] ? $data['diameter'] : null;
            $sewerLine->geom = $data['geom'] ? DB::raw("ST_Multi(ST_GeomFromText('" . $geom . "', 4326))") : null;
            $sewerLine->save();
        }
        else{

            $sewerLine = SewerLine::find($code);
            $sewerLine->length = $data['length'] ? $data['length'] : null;
            $sewerLine->location = $data['location'] ? $data['location'] : null;
            $sewerLine->diameter = $data['diameter'] ? $data['diameter'] : null;
            $sewerLine->save();
        }
    }

    /**
     * Download a listing of the specified resource from storage.
     *
     * @param array $data
     * @return null
     */
    public function download($data)
{
    $searchData = $data['searchData'] ?? null;
    $code = $data['code'] ?? null;
    $road_code = $data['road_code'] ?? null;
    $location = $data['location'] ?? null;
    

    $columns = ['Code', 'Treatment Plant', 'Road Code', 'Length', 'Location', 'Diameter (mm)'];

    $query = SewerLine::select('sewers.code', 'fsm.treatment_plants.name as Treatment Plant', 'sewers.road_code', 'sewers.length', 'sewers.location', 'sewers.diameter')
        ->leftJoin('fsm.treatment_plants', 'sewers.tp_id', '=', 'fsm.treatment_plants.id')
        ->whereNull('sewers.deleted_at');

    if (!empty($code)) {
        $query->where('sewers.code', $code);
    }
    if (!empty($location)) {
        $query->where('sewers.location', $location);
    }
   
    if (!empty($road_code)) {
        $query->where('sewers.road_code', $road_code);
    }
    // Debug: Check if the query retrieves any data
    $sewers = $query->get();
    if ($sewers->isEmpty()) {
        // No data found, handle accordingly
        return response()->json(['message' => 'No data found for the given filters'], 404);
    }

    $style = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(13)
        ->setBackgroundColor(Color::rgb(228, 228, 228))
        ->build();

    $writer = WriterFactory::create(Type::CSV);
    $writer->openToBrowser('Sewer Network.csv');
    $writer->addRowWithStyle($columns, $style); // Top row of CSV

    $query->chunk(5000, function ($sewers) use ($writer) {
        $sewersArray = $sewers->toArray();
        foreach ($sewersArray as &$sewer) {
            // Convert any objects to string if necessary
            $sewer = array_map('strval', $sewer);
        }
        $writer->addRows($sewersArray);
    });

    $writer->close();
}

}
