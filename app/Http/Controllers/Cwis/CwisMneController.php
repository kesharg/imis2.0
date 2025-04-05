<?php
// Last Modified Date: 16-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)
namespace App\Http\Controllers\Cwis;

use App\Http\Controllers\Controller;
use App\Models\Cwis\DataSource;
use App\Models\Cwis\cwis_mne;
use App\Exports\MneCsvExport;
use Maatwebsite\Excel\Excel;
use Auth;
use Datatables;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use DB;
use Route;

class CwisMneController extends Controller
{
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
        $this->middleware('auth');
        // $this->middleware('permission:List CWIS MnE', ['only' => ['index']]);
        // $this->middleware('permission:View CWIS MnE', ['only' => ['show']]);
        // $this->middleware('permission:Add CWIS MnE', ['only' => ['create', 'store']]);
        // $this->middleware('permission:Edit CWIS MnE', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:Export CWIS MnE', ['only' => ['export']]);
    }

    public function index(Request $request)
    {

        // $year = cwis_mne::select("year")
        //             ->distinct()
        //             ->orderby('year', 'desc')
        //             ->pluck('year')->first();

        if($request->year){
            $year = $request->year;
        } else {

            $year = cwis_mne::select("year")
                    ->distinct()
                    ->orderby('year', 'desc')
                    ->pluck('year')->first();
        }
        $slugyear = cwis_mne::select(DB::raw('year + 1 as year'))
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
        $pickyear = cwis_mne::select("year")
                    ->distinct()
                    ->orderby('year', 'desc')->pluck('year');
        $page_title = "CWIS Indicators Monitoring and Evaluation";
        $subCategory_titles = DB::Table('cwis.data_athena as d')
                    ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                    ->where('d.year', '=', $year)
                    ->distinct()->pluck('ds.sub_category_title');
        $param_list = cwis_mne::where('year', '=', $year)
                    ->orderBy('parameter_id')
                    ->groupBy('parameter_id')
                    ->pluck('parameter_id');

        $param_listcount = count($param_list);
        for($i=0; $i<$param_listcount ; $i++)
        {
            $param_titles[$i] = DB::Table('cwis.data_athena as d')
                        ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                        ->where('d.year', '=', $year)
                        ->where('d.parameter_id', '=', $param_list[$i])->limit(1)
                        ->orderBy('d.source_id')
                        ->pluck('parameter_title');
            $param_details[$i] = DB::Table('cwis.data_athena as d')
                        ->select('ds.parameter_title', 'd.assmntmtrc_dtpnt', 'd.unit', 'd.co_cf', 'd.data_value', 'ds.answer_type', 'ds.data_periodicity', 'ds.remark', 'ds.is_system_generated')
                        ->selectRaw("UPPER(LEFT(d.data_type[1], 1)) || SUBSTRING(d.data_type[1] FROM 2) AS data_type, CONCAT(UPPER(LEFT(d.data_type[2], 1)), SUBSTRING(d.data_type[2] FROM 2)) as data_type_phldr, d.data_type[array_length(d.data_type, 1)] as data_type_req")
                        ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                        ->where('d.year', '=', $year)
                        ->where('d.parameter_id', '=', $param_list[$i])
                        ->orderBy('d.source_id')
                        ->get();

        }
        // $add_cwis_data_button_visible = false;
        // $range_from = date('Y-m-d', strtotime('Dec 31')); //Current year end date
        // $range_end = date('Y-m-d', strtotime($range_from. ' + 2 months'));
        // $today = date('Y-m-d');
        // if (($today >= $range_from) && ($today <= $range_end)){
        //     $add_cwis_data_button_visible = true;
        // }
        return view('cwis/cwis-df-mne.index', compact('pickyear', 'page_title', 'subCategory_titles', 'param_listcount', 'param_titles', 'param_details', 'year','slugyear'));
    }

    public function show(Request $request, $year)
    {

        $pickyear = cwis_mne::select("year")
                    ->distinct()
                    ->orderby('year', 'desc')->pluck('year');

        $page_title = "CWIS Indicators Monitoring and Evaluation";

        $subCategory_titles = DB::Table('cwis.data_athena as d')
                    ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                    ->where('d.year', '=', $year)
                    ->distinct()->pluck('ds.sub_category_title');

        $param_list = cwis_mne::where('year', '=', $year)
                    ->orderBy('parameter_id')
                    ->groupBy('parameter_id')
                    ->pluck('parameter_id');
        $param_listcount = count($param_list);


        for($i=0; $i<$param_listcount ; $i++)
        {
            $param_titles[$i] = DB::Table('cwis.data_athena as d')
                        ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                        ->where('d.year', '=', $year)
                        ->where('d.parameter_id', '=', $param_list[$i])->limit(1)
                        ->orderBy('d.source_id')
                        ->pluck('parameter_title');
            $param_details[$i] = DB::Table('cwis.data_athena as d')
                        ->select('ds.parameter_title', 'd.assmntmtrc_dtpnt', 'd.unit', 'd.co_cf', 'd.data_value')
                        ->selectRaw('d.data_type[1] as data_type, d.data_type[2] as data_type_phldr,  d.data_type[array_length(d.data_type, 1)] as data_type_req')
                        ->leftJoin('cwis.data_source as ds', 'ds.id', '=', 'd.source_id')
                        ->where('d.year', '=', $year)
                        ->where('d.parameter_id', '=', $param_list[$i])
                        ->orderBy('d.source_id')
                        ->get();
        }
        return view('cwis/cwis-df-mne.index', compact('pickyear', 'page_title', 'subCategory_titles', 'param_listcount', 'param_titles', 'param_details'));
    }


    public function store(Request $request, cwis_mne $cwis_mne)
    {

        if($request->year){
            $year = $request->year;
        } else {
            $year = cwis_mne::select("year")
                    ->distinct()
                    ->orderby('year', 'desc')
                    ->pluck('year')->first();
        }

        $cwis_infos = cwis_mne::where('year', '=',$year)->where('data_value', '=',NULL)->orderBy('source_id')->get();

        if($cwis_infos){
            foreach($cwis_infos as $key => $cwis_info){
                $cwis_info->data_value = $request->data_value[$key];

                $cwis_info->save();
            }

            return redirect('cwis/cwis/cwis-df-mne/?year='.$year)->with('success','CWIS updated successfully');
        }
        else{
            return redirect('cwis/cwis/cwis-df-mne/?year='.$year)->with('error','Failed to update Data');
        }

    }

    public function cwis($year)
    {

        $result = DB::select(DB::raw('select * from insert_data_into_cwis_athena(' . $year . ');'));
        return response()->json($result);
    }
    public function createIndex(Request $request)
    {
        $currentYear = date('Y');
        $newsurveyear = cwis_mne::latest()->selectRaw("year + 1  as newyear")->limit(1)->get();
        $page_title = "Data Framework for Monitoring and Evaluation";

        $subCategory_titles = DataSource::where('category_id', '=', 7)
            ->distinct()->pluck('sub_category_title');

        $param_list = DataSource::where('category_id', '=', 7)
            ->orderBy('parameter_id')
            ->groupBy('parameter_id')
            ->pluck('parameter_id');
        $param_listcount = count($param_list);


        for ($i = 0; $i < $param_listcount; $i++) {
            $param_titles[$i] = DataSource::where('category_id', '=', 7)
                ->where('parameter_id', '=', $param_list[$i])->limit(1)
                ->orderBy('id')
                ->pluck('parameter_title');
            $param_details[$i] = DataSource::select('parameter_title', 'assmntmtrc_dtpnt', 'unit', 'co_cf')
                // ->selectRaw('data_type[1] as data_type, data_type[2] as data_type_phldr,  data_type[array_length(data_type, 1)] as data_type_req')
                ->where('category_id', '=', 7)
                ->where('parameter_id', '=', $param_list[$i])
                ->orderBy('id')
                ->get();
        }
        $year = $request->year;
        $cwisResult = $this->cwis($year);
        return view('cwis/cwis-df-mne.create', compact('newsurveyear', 'page_title', 'subCategory_titles', 'param_listcount', 'param_titles', 'param_details', 'cwisResult','year'));
    }

    public function createStore(Request $request, cwis_mne $cwis_mne)
    {

        $cwis_data_source = DataSource::where('category_id', '=', 7)->orderBy('id');

        if($cwis_data_source){
            foreach($cwis_data_source as $key => $cwis_mne){
                $cwis_mne = new cwis_mne;
                $cwis_mne->sub_category_id = $request->sub_category_id[$key];
                $cwis_mne->parameter_id = $request->parameter_id[$key];
                $cwis_mne->assmntmtrc_dtpnt = $request->assmntmtrc_dtpnt[$key];
                $cwis_mne->unit = $request->unit[$key];
                $cwis_mne->co_cf = $request->co_cf[$key];
                $cwis_mne->data_value = $request->data_value[$key];
                // $cwis_mne->data_type = $request->data_type[$key];
                $cwis_mne->sym_no = $request->sym_no[$key];
                $cwis_mne->year = $request->year[$key];
                $cwis_mne->source_id = $request->source_id[$key];
                $cwis_mne->save();
            }

            return redirect('cwis/cwis/cwis-df-mne')->with('success','CWIS updated successfully');
        }
        return redirect('cwis/cwis/cwis-df-mne')->with('error','Failed to update Data');
    }
    public function exportMneCsv(Request $request)
    {
        $pickyear = cwis_mne::select("year")
        ->distinct()
        ->orderby('year', 'desc')->pluck('year');
        $year = $request->year_select ?? $pickyear[0];
        ob_end_clean();
        ob_start();
        return $this->excel->download(new MneCsvExport($year), 'CWIS M&E '. $year .'.xlsx');
     }

}
