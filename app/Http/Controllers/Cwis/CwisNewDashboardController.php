<?php

namespace App\Http\Controllers\Cwis;

use App\Exports\MneCsvExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cwis\cwis_mne;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use PDF;

class CwisNewDashboardController extends Controller
{
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cwis.cwis-dashboard.chart-layout.cwis-dash-layout');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($emc)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getall(Request $request, $year = null)
    {
        $presentYears = cwis_mne::distinct()->pluck('year');

        $latestYear = cwis_mne::orderBy('year', 'desc')->pluck('year')->first();

        $selectedYear = $year ?? $latestYear;
        $results = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'EQ - 1')
                   ->select(['data_value','assmntmtrc_dtpnt',  DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
                   $dataValues = $results->pluck('data_value')->map(function ($value) {
                    // Check if there's a decimal point
                    if (strpos($value, '.') !== false) {
                      // Use number_format to display with 2 decimal places
                      return number_format($value, 2, '.', '');
                    } else {
                      // No decimal point, return the original value
                      return $value;
                    }
                  });
        $headings = $results->pluck('assmntmtrc_dtpnt');

        $sf1 = cwis_mne::where('year',$selectedYear)
            ->where('indicator_code', 'SF - 1')
            ->select([
                DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")
            ])
            ->get();

        $sf1a = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1a')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1b = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1b')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1c = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1c')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1d = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1d')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1e = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1e')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1f = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1f')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf1g = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 1g')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf2 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 2')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf2a = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 2a')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf2b = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 2b')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf2c = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 2c')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf3 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 3')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf3a = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 3a')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf3b = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 3b')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf3c = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 3c')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf3e = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 3e')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf4 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 4')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf4a = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 4a')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf4b = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 4b')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf4d = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 4d')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
                'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf5 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 5')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf6 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 6')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf7 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 7')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf8 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 8')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),
        'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf9 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 9')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $sf10 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SF - 10')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'),'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();
        $ss1 = cwis_mne::where('year',$selectedYear)->where('indicator_code', 'SS - 1')->select([DB::raw('ROUND(CAST(data_value AS numeric)) as data_value'), 'assmntmtrc_dtpnt', DB::raw("CONCAT(UPPER(LEFT(heading, 1)), SUBSTRING(heading, 2)) as heading")])->get();


        return view(
            'cwis.cwis-dashboard.chart-layout.cwis-dash-layout',
            compact( 'dataValues','headings','sf1','sf1a','sf1b','sf1c','sf1d','sf1e',
                    'sf1f', 'sf1g','sf2', 'sf2a','sf2b', 'sf2c','sf3','sf3a', 'sf3b',
                     'sf3c','sf3e','sf4','sf4a','sf4b','sf4d','sf10','sf9','sf8',
                    'sf7','sf6','sf5','ss1','presentYears','latestYear' )
        );
    }


    public function exportCsv($year)
    {

        $year = intval($year);
        ob_end_clean();
        ob_start();
        return $this->excel->download(new MneCsvExport($year), 'CWIS M&E ' . $year . '.xlsx');
    }

}
