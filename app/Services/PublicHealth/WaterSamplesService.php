<?php
// Last Modified Date: 08-05-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\PublicHealth;

use Illuminate\Http\Request;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Auth;
use DataTables;
use DB;
use App\Models\PublicHealth\WaterSamples;
use App\Enums\WaterSamplesResult;

class WaterSamplesService
{
    public function getAllData($request)
    {
        $watersamples = WaterSamples::whereNull('deleted_at');
        return Datatables::of($watersamples)
            ->filter(function ($query) use ($request) {
                if ($request->sample_date) {
                    $query->where('sample_date', $request->sample_date);
                }

                if ($request->sample_location) {
                    $query->where('sample_location', 'ILIKE', '%' .  $request->sample_location . '%');
                }
                if ($request->water_coliform_test_result) {
                    $query->where('water_coliform_test_result', $request->water_coliform_test_result);
                }
            })
            

            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['water-samples.destroy', $model->id]]);

                if (Auth::user()->can('Edit Water Samples')) {
                    $content .= '<a title="Edit" href="' . action("PublicHealth\WaterSamplesController@edit", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Water Samples')) {
                   
                    $content .= '<a title="View" href="' . action("PublicHealth\WaterSamplesController@show", [$model->id]) . '"class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('View Water Samples History')) {
                    $content .= '<a title="History" href="' . action("PublicHealth\WaterSamplesController@history", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }
                    if (Auth::user()->can('View Water Samples')) {
                    $content .= '<a title="Map" href="' . action("MapsController@index", ['layer' => 'water_samples_layer', 'field' => 'id', 'val' => $model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-map-marker"></i></a> ';
                }

                if (Auth::user()->can('View Water Samples on Map ')) {
                    $content .= '<a href="#" title="Delete"  class="delete btn btn-danger btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }
                $content .= \Form::close();
                return $content;
            })

            ->editColumn('water_coliform_test_result', function ($model) {
                return WaterSamplesResult::getDescription($model->water_coliform_test_result);
            })
            
            ->make(true);
    }


    public function storeOrUpdate($id, $data)
    {
        if (is_null($id)) 
        {
 
        $watersamples = new WaterSamples();
        $watersamples->sample_date = $data['sample_date']?$data['sample_date'] : null;
        $watersamples->sample_location = $data['sample_location']?$data['sample_location'] : null;
        $watersamples->no_of_samples_taken = $data['no_of_samples_taken']?$data['no_of_samples_taken'] : null;
        $watersamples->water_coliform_test_result = $data['water_coliform_test_result']?$data['water_coliform_test_result'] : null;
        
        $geomValue = $data['geom'];
        $coordinates = explode(',', $geomValue);
        $longitude = $coordinates[0];
        $latitude = $coordinates[1];
        
        $watersamples->geom = DB::raw("ST_GeomFromText('POINT(" . $longitude . " " . $latitude .  ")', 4326)");
        
        $watersamples->save();
        }
        else 
        {
          
            $watersamples = WaterSamples::find($id);
            $watersamples->sample_date = $data['sample_date'] ? $data['sample_date'] : null;
            $watersamples->sample_location = $data['sample_location'] ? $data['sample_location'] : null;
            $watersamples->no_of_samples_taken = $data['no_of_samples_taken'] ? $data['no_of_samples_taken'] : null;
            $watersamples->water_coliform_test_result = $data['water_coliform_test_result'] ? $data['water_coliform_test_result'] : null;
            $geomValue = $data['geom'];
         
            $coordinates = explode(',', $geomValue);
            $longitude = $coordinates[0];
            $latitude = $coordinates[1];
           
            
            $watersamples->geom = DB::raw("ST_GeomFromText('POINT(" . $longitude . " " . $latitude .  ")', 4326)");
            $watersamples->save();
        }
    }


    public function download($data)
    {
        $sample_date = $data['sample_date'] ? $data['sample_date'] : null;
        $sample_location = $data['sample_location'] ? $data['sample_location'] : null;
        $water_coliform_test_result = $data['water_coliform_test_result'] ? $data['water_coliform_test_result'] : null;

        $columns = ['ID', 'Sample Date', 'Sample Location', 'No. of Samples Taken', 'Water Coliform Test Result'];

        $query = WaterSamples::select('id', 'sample_date', 'sample_location', 'no_of_samples_taken', 'water_coliform_test_result')->whereNull('deleted_at');
        if (!empty($sample_date)) {
            $query->where('sample_date', $sample_date);
        }
        if (!empty($sample_location)) {
            $query->where('sample_location', 'ILIKE', '%' . $sample_location . '%');
        }
        if (!empty($water_coliform_test_result)) {
            $query->where('water_coliform_test_result', $water_coliform_test_result);
        }
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);

        $writer->openToBrowser('WaterSamples.CSV')
            ->addRowWithStyle($columns, $style); // Top row of the CSV

        $query->chunk(5000, function ($watersamples) use ($writer) {
            foreach ($watersamples as $data) {
                $values = [];
                $values[] = $data->id;
                $values[] = $data->sample_date;
                $values[] = $data->sample_location;
                $values[] = $data->no_of_samples_taken;

                $values[] = $data->water_coliform_test_result;

                $writer->addRow($values);
            }
        });

        $writer->close();
    }
}
