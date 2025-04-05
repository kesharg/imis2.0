<?php
// Last Modified Date: 14-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\UtilityInfo;

use App\Models\UtilityInfo\WaterSupplys;
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

class WaterSupplysService {

    protected $session;
    protected $instance;

    /**
     * Constructs a new WaterSupplys.
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

        $waterSupplysData = WaterSupplys::select('*');

        return Datatables::of($waterSupplysData)
                ->filter(function ($query) use ($data) {
                if ($data['code']) {

                    $query->where('code', 'ILIKE', '%' .  $data['code'] . '%');
                }

                if ($data['lengths']) {
                    $query->where('length', 'ILIKE', '%' .  $data['lengths'] . '%');
                }

                if ($data['project_name']) {
                    $query->where('project_name', 'ILIKE', '%' .  $data['project_name'] . '%');
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['watersupplys.destroy', $model->code]]);

                if (Auth::user()->can('Edit WaterSupply Network')) {
                    $content .= '<a title="Edit" href="' . action("UtilityInfo\WaterSupplysController@edit", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }

                if (Auth::user()->can('View WaterSupply Network')) {
                    $content .= '<a title="Detail" href="' . action("UtilityInfo\WaterSupplysController@show", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }

                if (Auth::user()->can('View WaterSupply Network History')) {
                    $content .= '<a title="History" href="' . action("UtilityInfo\WaterSupplysController@history", [$model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }

                if (Auth::user()->can('Delete WaterSupply Network')) {
                    $content .= '<a href="#" title="Delete"  class="delete btn btn-danger btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }

                if (Auth::user()->can('View WaterSupply Network On Map')) {
                    $content .= '<a title="Map" href="' . action("MapsController@index", ['layer' => 'watersupply_network_layer', 'field' => 'code', 'val' => $model->code]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-map-marker"></i></a> ';
                }
                $content .= \Form::close();
                return $content;
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

            $waterSupplysTemp = DB::select("SELECT ST_AsText(geom) AS geom FROM watersupplys_temp");
            $geom = ($waterSupplysTemp[0]->geom);
            $maxcode = WaterSupplys::withTrashed()->max('code');
            $maxcode = str_replace('W', '', $maxcode);
            $waterSupplys = new WaterSupplys();
            $waterSupplys->code = 'W' . sprintf('%04d', $maxcode + 1);
            $waterSupplys->road_code = $data['road_code'] ? $data['road_code'] : null;
            $waterSupplys->diameter = $data['diameter'] ? $data['diameter'] : null;
            $waterSupplys->length = $data['length'] ? $data['length'] : null;
            $waterSupplys->project_name = $data['project_name'] ? $data['project_name'] : null;
            $waterSupplys->type = $data['type'] ? $data['type'] : null;
            $waterSupplys->material_type = $data['material_type'] ? $data['material_type'] : null;
            $waterSupplys->geom = $data['geom'] ? DB::raw("ST_Multi(ST_GeomFromText('" . $geom . "', 4326))") : null;
            $waterSupplys->save();
        }
        else{

            $waterSupplys = WaterSupplys::find($code);
            $waterSupplys->diameter = $data['diameter'] ? $data['diameter'] : null;
            $waterSupplys->length = $data['length'] ? $data['length'] : null;
            $waterSupplys->project_name = $data['project_name'] ? $data['project_name'] : null;
            $waterSupplys->type = $data['type'] ? $data['type'] : null;
            $waterSupplys->material_type = $data['material_type'] ? $data['material_type'] : null;

            $waterSupplys->save();
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
        $searchData = $data['searchData'] ? $data['searchData'] : null;
        $code = $data['code'] ? $data['code'] : null;
        $length = $data['length'] ? $data['length'] : null;
        $project_name = $data['project_name'] ? $data['project_name'] : null;
        $columns = ['Code', 'Road Code', 'Length', 'Diameter (mm)', 'City Water Supply', 'Type', 'Material Type'];

        $query = WaterSupplys::select('code','road_code',  'length', 'diameter', 'project_name', 'type', 'material_type')
            ->whereNull('deleted_at');


        if (!empty($code)) {
            $query->where('code',   $code );

        }
        if (!empty($length)) {
            $query->where('length',   $length );

        }
      

        if (!empty($project_name)) {
            $query->where('project_name', $project_name);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Water Supply Network.CSV')
            ->addRowWithStyle($columns, $style); //Top row of excel

        $query->chunk(5000, function ($waterSupplys) use ($writer) {
            $writer->addRows($waterSupplys->toArray());
        });

        $writer->close();

    }
}
