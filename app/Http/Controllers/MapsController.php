<?php

namespace App\Http\Controllers;

use App\BuildOwner;
use App\Exports\SummaryInfoMultiSheetExport;
use App\Exports\WaterbodySummaryInfoMultiSheetExport;
use App\Exports\WardBuildingsSummaryInfoMultiSheetExport;
use App\Exports\RoadBuildingsSummaryInfoMultiSheetExport;
use App\Exports\PointBuildingsSummaryInfoMultiSheetExport;
use App\Exports\BuildingsRoadSummaryInfoMultiSheetExport;
use App\Exports\DrainPotentialSummaryInfoMultiSheetExport;
use App\ServiceProvider;
use Auth;
use App\Exports\BuildingsOwnerExport;
use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\LayerInfo\Ward;
use App\Models\LayerInfo\Taxzone;
//use App\Models\WaterSupplyInfo\DueYear;
use App\Models\TaxPaymentInfo\DueYear;
use DB;
use App\Models\BuildingInfo\FunctionalUse;
use App\Models\BuildingInfo\UseCategory;
use App\ContainmentSurvey;
use App\Models\Fsm\Emptying;
use App\Models\Fsm\Feedback;
use Schema;
use App\Services\Maps\MapsService;
use Illuminate\Support\Facades\Validator;


class MapsController extends Controller
{
    private $excel;
    protected MapsService $mapsService;

    /**
     * Constructor method for the class.
     *
     * @param Excel $excel The Excel service instance used for generating Excel files.
     * @param MapsService $mapsService The MapsService instance used for map-related operations.
     * @return void
     */

    public function __construct(Excel $excel,MapsService $mapsService)
    {
        $this->mapsService = $mapsService;
        $this->excel = $excel;
        $this->middleware('auth');//, ['except' => ['index']]);
        //$this->middleware('permission:View Map', ['only' => ['index']]);
    }

    /**
     * Handles the index request for maps.
     * @return \Illuminate\Http\Response The response containing the maps index data.
     */

    public function index()
    {
        header('Access-Control-Allow-Origin: *');
        return $this->mapsService->mapsIndex();

    }

    /**
     * Generates and downloads a summary report in CSV format for a buffered polygon.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Returns a response object for downloading the CSV report.
     */

    public function getBufferPolygonReportCSV()
    {
        ob_end_clean();
        return $this->excel->download(new SummaryInfoMultiSheetExport(request()->buffer_polygon_geom, request()->buffer_polygon_distance), 'Summary Information Buffer Polygon.xlsx');
    }

    /**
     * Generates a CSV report containing summary information for a specific water body.
     * @return \Illuminate\Http\Response
     */

    public function getWaterBodyReportCsv()
    {
        $waterbodyQuery = "SELECT ST_AsText(geom) AS geom from layer_info.waterbodys WHERE id = '" . request()->wb_code . "'";
        $waterbody = DB::select($waterbodyQuery);
         if(request()->wb_distance > 0){
                $bufferDisancePolygon = request()->wb_distance;
            } else {
                $bufferDisancePolygon = 0;
            }
        ob_end_clean();
        return $this->excel->download(new SummaryInfoMultiSheetExport($waterbody[0]->geom, $bufferDisancePolygon), 'Summary Information Buffer Water Body Polygon.xlsx');
    }


    /**
     * Retrieves and generates a CSV report containing summary information about buildings within a specific ward.
     * @return \Illuminate\Http\Response
     */

    public function getWardBuildingsReportCsv()
    {
        $wardQuery = "SELECT ST_AsText(geom) AS geom from layer_info.wards WHERE ward = '" . request()->ward_building_no . "'";
        $ward = DB::select($wardQuery);
        $rowGeom = $ward[0]->geom;
        ob_end_clean();
        return $this->excel->download(new SummaryInfoMultiSheetExport($rowGeom, 0), 'Summary Information Ward Buildings.xlsx');
    }


    /**
     * Retrieves and generates a CSV report containing summary information about buildings within a specific road code.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoadBuildingsReportCsv()
    {
        $roadQuery = "SELECT ST_AsText(geom) AS geom from utility_info.roads WHERE code = '" . request()->road_code . "'";
        $row = DB::select($roadQuery);
         if(request()->rb_distance > 0){
                $bufferDisancePolygon = request()->rb_distance;
            } else {
                $bufferDisancePolygon = 0;
            }

        $rowGeom = $row[0]->geom;
        ob_end_clean();
        return $this->excel->download(new SummaryInfoMultiSheetExport($rowGeom, $bufferDisancePolygon), 'Road Building Buffer Summary Information.xlsx');
    }

     /**
     * Generates a CSV report for point buildings with buffer summary information.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPointBuildingsReportCSV()
    {
        ob_end_clean();
        return $this->excel->download(new PointBuildingsSummaryInfoMultiSheetExport(request()->PTB_long, request()->PTB_lat, request()->PTB_distance), 'Point Buffer Summary Information.xlsx');
    }

    /**
     * Retrieves a summary report in CSV format regarding buildings related to specified roads.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBuildingsRoadReportCsv()
    {
        ob_end_clean();
        return $this->excel->download(new BuildingsRoadSummaryInfoMultiSheetExport(request()->road_codes), 'Buildings to road Summary Information.xlsx');
    }

    /**
     * Retrieves a CSV report summarizing the drain potential.
     * @return \Illuminate\Http\Response The response containing the generated CSV report.
     */
    public function getDrainPotentialReportCSV()
    {
        // Construct SQL query to retrieve sewer geometry
        $roadQuery = "SELECT ST_AsText(geom) AS geom from utility_info.sewers WHERE code = '" . request()->db_code . "'";
        $row = DB::select($roadQuery);
         if(request()->db_distance > 0){
                $bufferDisancePolygon = request()->db_distance;
            } else {
                $bufferDisancePolygon = 0;
            }

        $rowGeom = $row[0]->geom;
        ob_end_clean();
        return $this->excel->download(new DrainPotentialSummaryInfoMultiSheetExport($rowGeom, $bufferDisancePolygon), 'Sewer Potential buildings Buffer Summary Information.xlsx');
    }

    /**
     * Retrieves a CSV report containing information about buildings within a tax zone.
     *
     * @param string $geom The geometry data of the tax zone.
     * @return \Illuminate\Http\Response The CSV report containing buildings owner list.
     */

    public function getBuildingsTaxzoneReportCSV($geom)
    {
        ob_end_clean();
        return $this->excel->download(new BuildingsOwnerExport($geom), 'Buildings Owner List.xlsx');
    }

    /**
     * Retrieves the mapping of buildings to containment areas.
     *  @return mixed The result of the MapsService method call
     */
    public function getBuildingToContainment()
    {
        return $this->mapsService->getBuildingToContainment();
    }

    /**
     * Retrieves the mapping of containment to buildings.
     * @return mixed The result of the MapsService method call
     */
    public function getContainmentToBuildings()
    {
        return $this->mapsService->getContainmentToBuildings();
    }

    /**
     * Retrieves the mapping of building associated to main buildings.
     *  @return mixed The result of the MapsService method call
     */
    public function getAssociatedToMainbuilding()
    {
        return $this->mapsService->getAssociatedToMainbuilding();
    }


    /**
     * Retrieves the extent of a given layer based on the specified attribute and value..
     * @return array The spatial extent of the requested layer.
     */

    public function getExtent()
    {

        $requestedLayer =  request()->layer;
        $atrribute = request()->atrribute;
        $value = request()->value;
        if (in_array($requestedLayer, array("containments_layer"))) {
         $data = $this->mapsService->containmentExtent($atrribute, $value);
        } else if (in_array($requestedLayer, array("buildings_layer"))) {
            $data = $this->mapsService->buildingExtent($atrribute, $value);
        }  else if (in_array($requestedLayer, array("low_income_communities_layer"))) {
            $data = $this->mapsService->polygonExtent('low_income_communities_layer',$atrribute, $value);
        } else if (in_array($requestedLayer, array("roadlines_layer", "drains_layer", "sewerlines_layer", "watersupply_network_layer"))) {

            if($requestedLayer == "roadlines_layer")
            {
                $layer = "utility_info.roads";

            }
            if($requestedLayer == "sewerlines_layer")
            {
                $layer = "utility_info.sewers";

            }
            if($requestedLayer == "drains_layer")
            {
                $layer = "utility_info.drains";

            }
            if($requestedLayer == "watersupply_network_layer")
           {
                $layer = "utility_info.water_supplys";
            }
            $data = $this->mapsService->lineStringExtent($layer, $atrribute, $value);

        } else if (in_array($requestedLayer, array("containment_surveys"))) {
            $data = $this->mapsService->containmentSurveyExtent($atrribute, $value);

        } else {

           if($requestedLayer == 'wards_layer')
           {
               $layer = 'layer_info.wards';
           }
           else if($requestedLayer == "ward_overlay")
            {
                $layer = "layer_info.ward_overlay";

            }
           else if($requestedLayer == "places_layer")
            {
                $layer = "layer_info.places";

            }
            else if($requestedLayer == "treatmentplants_layer")
            {
                $layer = "fsm.treatment_plants";

            }
            else if($requestedLayer == "water_samples_layer")
            {
                $layer = "public_health.water_samples";

            }
            else if($requestedLayer == "toilets_layer")
            {
                $layer = "fsm.toilets";

            }

            $data = $this->mapsService->pointsExtent($layer, $atrribute, $value);

        }

        return $data;


    }

    /**
     * Retrieves containment buildings based on field and value.
     *  @return mixed The result of the MapsService method call
     */

    public function getContainmentBuildings()
    {
        return $this->mapsService->getContainmentBuildings(request()->field, request()->val);

    }

    /**
     * Retrieves containment road information based on field and value.
     *  @return mixed The result of the MapsService method call
     */

    public function getContainmentRoad()
    {
        return $this->mapsService->getContainmentRoadInfo(request()->field, request()->val);
    }

     /**
     * Retrieves building road information based on field and value.
     *  @return mixed The result of the MapsService method call
     */
    public function getBuildingRoad()
    {
        return $this->mapsService->getBuildingRoadInfo(request()->field, request()->val);
    }

    /**
     * Retrieves the nearest road based on the provided latitude and longitude coordinates.
     *  @return mixed The result of the MapsService method call
     */

    public function getNearestRoad()
    {

        $lat = request()->lat;
        $long = request()->long;
        return $this->mapsService->getNearestRoad(request()->lat, request()->long);

    }

    /**
     * Retrieves information about proposed emptying containments within a specified date range.
     *  @return mixed The result of the MapsService method call
     */

    public function getProposedEmptyingContainments()
    {
        return $this->mapsService->getProposedEmptyingContainmentsInfo(request()->start_date, request()->end_date);
    }

    /**
     * Retrieves information about buildings that are due for certain actions.
     *  @return mixed The result of the MapsService method call
     */

    public function getDueBuildings()
    {
        return $this->mapsService->getDueBuildingsInfo();
    }

    /**
     * Retrieves information about due buildings based on selected wards and tax zones.
     *
     * @param Request $request The HTTP request object containing selectedWards and selectedTaxZones.
     * @return mixed Information about due buildings within the specified wards and tax zones.
     */

    public function getDueBuildingsWardTaxzone(Request $request)
    {
        $where = "";
        if (!empty($request->selectedWards)) {
            $wards = $request->selectedWards;
            $wardvalues = implode(', ', $wards);
            $where .= " AND b.ward in ($wardvalues)";
        }
        if (!empty($request->selectedTaxZones)) {
            $taxzones = $request->selectedTaxZones;
            $i = 0;
            foreach ($taxzones as $taxzone) {
                if ($i == 0) {
                    $operator = " AND ";
                } else {
                    $operator = " OR ";
                }
                $where .= " $operator b.taxzoneid = '" . $taxzone . "'";
                $i++;
            }
        }
        return $this->mapsService->getDueBuildingsWardTaxzoneInfo($where);
    }

    /**
     * Retrieves containment information for a specific application based on provided latitude and longitude coordinates.
     *  @return mixed The result of the MapsService method call
     */

    public function getApplicationContainments()
    {
        return $this->mapsService->getApplicationContainments(request()->lat, request()->long);
    }

    /**
     * Retrieves containment information for a specified year and month from the MapsService.
     *
     * @param Request $request The HTTP request object containing the 'year' and 'month' parameters.
     * @return mixed The result of the MapsService method call
     */

    public function getApplicationContainmentsYearMonth(Request $request)
    {
        return $this->mapsService->getApplicationContainmentsYearMonth($request->year, $request->month);
    }

    /**
     * Retrieves applications that are not yet approved as TP on a specified date.
     *
     * @return mixed The result of the mapsService method call
     */

    public function getApplicationNotTPOnDate()
    {
        return $this->mapsService->getApplicationNotTPOnDate(request()->start_date);

    }

    /**
     * Retrieves application information for a specified date.
     *
     * @return mixed The result of the mapsService method call
     */

    public function getApplicationOnDate()
    {
        return $this->mapsService->getApplicationOnDate(request()->start_date);
    }

    /**
     * Retrieves applications from the MapsService.
     *
     * @return mixed The result of the mapsService method call
     */

    public function getApplicationNotTP()
    {
        return $this->mapsService->getApplicationNotTP();
    }

    /**
     * Retrieves application not TP containments for a specific year and month.
     *
     * @param Request $request The HTTP request object containing the year and month parameters.
     * @return mixed The result of the mapsService method call.
     */

    public function getApplicationNotTPContainmentsYearMonth(Request $request)
    {
        return $this->mapsService->getApplicationNotTPContainmentsYearMonth($request->year,$request->month);
    }

    /**
     * Retrieves feedback report data based on the provided geometry.
     *
     * @param Request $request The HTTP request object containing the geometry parameter.
     * @return array|string Array containing feedback report data in chart format if geometry is provided; otherwise, a string indicating the required 'geom' field.
     */

    public function getFeedbackReport(Request $request)
    {

        if ($request->geom) {

        if (Auth::user()->hasRole('Service Provider - Admin')){
            $whereUser = " AND fb.service_provider_id =" . Auth::user()->service_provider_id;
        }else{
            $whereUser = " AND 1=1";
        }

            /**No of containment emptied**/

            $uniqueContainCodeEmptiedCount = $this->mapsService->getUniqueContainmentEmptiedCount($request->geom, $whereUser);



        /**No of feedbacks submitted**/
            $feedbackCount = $this->mapsService->getFeedbacksCount($request->geom, $whereUser);


            /**chart type FSM Service Quality**/
            $results = $this->mapsService->getFeedbackFsmServiceQuality($request->geom, $whereUser);
            $labels = array('Yes', 'No');
            $values = array($results[0]->yes, $results[0]->no);
            $colors = ['rgba(153, 202, 60, 0.8)', 'rgba(251, 176, 64, 0.8)'];
            $borderColor = ['rgba(57, 142, 61, 0.65)', 'rgba(62, 199, 68, 0.8)', 'rgba(255, 229, 0, 0.8)', 'rgba(255, 179, 3, 0.8)', 'rgba(219, 61, 61, 0.65)'];
            $hoverBackgroundColor = ['rgba(57, 142, 61, 0.45)', '"rgba(62, 199, 68, 0.45)', 'rgba(255, 229, 0, 0.45)', 'rgba(255, 179, 3, 0.45)', 'rgba(219, 61, 61, 0.45)'];
            $hoverBorderColor = ['rgba(57, 142, 61, 1)', 'rgba(62, 199, 68, 1)', 'rgba(255, 229, 0, 1)', 'rgba(255, 179, 3, 1)', 'rgba(219, 61, 61, 1)'];

            /**chart type Sanitation Workers Wearing PPE**/
            $results7 = $this->mapsService->getFeedbackSanitationWorkersPpe($request->geom, $whereUser);

            $labels7 = array('Yes', 'No');
            $values7 = array($results7[0]->yes, $results7[0]->no);

            $colors7 = ['rgba(153, 202, 60, 0.8)', 'rgba(251, 176, 64, 0.8)'];


            $chart = [
                'labels' => $labels,
                'values' => $values,
                'colors' => $colors,
                'borderColor' => $borderColor,
                'hoverBackgroundColor' => $hoverBackgroundColor,
                'hoverBorderColor' => $hoverBorderColor,
                'labels7' => $labels7,
                'values7' => $values7,
                'colors7' => $colors7,
                'uniqueContainCodeEmptiedCount' => $uniqueContainCodeEmptiedCount,
                'feedbackCount' => $feedbackCount,
            ];


            return $chart;

        } else {
            return "The 'geom' field is required";
        }
    }

    /**
     * Retrieves information about buildings associated with specified drain codes.
     *
     * @param Request $request The HTTP request containing drain codes.
     * @return array Building information associated with the specified drain codes.
     */

    public function getDrainBuildings(Request $request)
    {
        $drainCodes = $request->drain_codes;
        $data = array();
        // Check if drain codes is an array and contains elements
        if (is_array($drainCodes) && count($drainCodes) > 0) {
            $drainCodes = array_map(function ($value) {
                return "'" . $value . "'";
            }, $drainCodes);
            // Construct SQL query to select buildings based on drain codes
            $building_query = "SELECT bin, ST_AsText(geom) AS geom"
                . " FROM building_info.buildings"
                . " WHERE sewer_code IN (" . implode(',', $drainCodes) . ")";
            $results = DB::select($building_query);
            foreach ($results as $row) {
                $building = array();
                $building['bin'] = $row->bin;
                $building['geom'] = $row->geom;
                $data[] = $building;
            }
        }

        return $data;
    }

    /**
     * Retrieves buildings associated with the specified road codes and their summary information.
     *
     * @param Request $request The HTTP request object containing road codes.
     * @return array Associative array containing buildings and their summary information.
     */
    public function getBuildingsToRoad(Request $request)
    {
        $roadCodes = $request->road_codes;
        // Check if road codes is an array and contains elements
        if (is_array($roadCodes) && count($roadCodes) > 0) {
            // If road codes are provided, map each code to a format suitable for database query
            $roadCodes = array_map(function ($value) {
                return "'" . $value . "'";
            }, $roadCodes);
             // Call mapsService to get buildings related to the provided road codes
            $results = $this->mapsService->getBuildingsToRoadSummary($roadCodes);

        }
         // Returning an array containing buildings and population summary HTML
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['summary']
        ];
    }

    /**
     * Retrieves the potential buildings within the drainage area.
     *
     * @param Request $request The HTTP request containing the drainage code and distance.
     * @return array Returns an array containing buildings, popup content HTML, and polygon data.
     */

    public function getDrainPotentialBuildings(Request $request)
    {
        $sewerId = $request->drain_code;
        // Check if distance is greater than 0, if not set it to 0
        if ($request->distance > 0) {
            $bufferDisancePolygon = $request->distance;
        } else {
            $bufferDisancePolygon = 0;
        }
         // Query to retrieve the geometry of the sewer based on its code
        $query = "SELECT ST_AsText(geom) AS geom from utility_info.sewers WHERE code = '" . $sewerId . "'";
        $sewer = DB::select($query);
        // Call the buildingsPopContentPolygon method of mapsService to get buildings, pop contents, and polygon
        $results = $this->mapsService->buildingsPopContentPolygon($bufferDisancePolygon, $sewer[0]->geom);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];
    }

    /**
     * Retrieves buildings and related information within a specified distance from a water body.
     *
     * @param Request $request The HTTP request containing water body code and distance information.
     * @return array Array containing buildings, pop contents HTML, and polygon information.
     */
    public function getWaterBodiesBuildings(Request $request)
    {
        $watbodycode = $request->waterbody_code;
        if ($request->distance > 0) {
            $distance = $request->distance;
        } else {
            $distance = 0;
        }
        // Construct SQL query to retrieve geometry of the waterbody with the given code
        $wbQuery = "SELECT ST_AsText(geom) AS geom from layer_info.waterbodys WHERE id = '" . $watbodycode . "'";
        $waterbody = DB::select($wbQuery);
        $results = $this->mapsService->buildingsPopContentPolygon($distance, $waterbody[0]->geom);
        // Return the buildings, pop content HTML, and polygon geometry
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];

    }

    /**
     * Retrieves summary information about buildings within a specified buffer from a given point.
     *
     * @param Request $request The HTTP request containing parameters.
     * @return array An array containing information about buildings, pop-up contents HTML, and the polygon.
     */
    public function getPointBufferBuildings(Request $request)
    {
        if (request()->distance > 0) {
            $distance = request()->distance;
        } else {
            $distance = 0;
        }
        $long = $request->long;
        $lat = $request->lat;
        // Retrieve building data within the specified distance from the Maps Service
        $results = $this->mapsService->getPointBufferBuildingsSummary($distance, $long, $lat);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];

    }

    /**
     * Retrieves information about buildings along a road and returns relevant data.
     *
     * @param Request $request The HTTP request object containing road code and distance.
     * @return array An array containing information about buildings, pop contents HTML, and polygon.
     */
    public function getRoadBuildings(Request $request)
    {
        $roadcode = $request->road_code;
        if ($request->distance > 0) {
            $bufferDisancePolygon = $request->distance;
        } else {
            $bufferDisancePolygon = 0;
        }
        // Construct SQL query to select road geometry based on road code
        $query = "SELECT ST_AsText(geom) AS geom from utility_info.roads WHERE code = '" . $roadcode . "'";
        $road = DB::select($query);
        // Call a method from mapsService to retrieve buildings and other data based on buffer distance and road geometry
        $results = $this->mapsService->buildingsPopContentPolygon($bufferDisancePolygon, $road[0]->geom, );
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];

    }

    /**
     * Searches for keywords within specified layers and retrieves associated data.
     * @return array An array containing the following keys:
     *               - 'gid': The ID associated with the matched keyword (or null if not found).
     *               - 'point': The point geometry associated with the matched keyword (or null if not found).
     *               - 'geom': The geometric data associated with the matched keyword (or null if not found).
     */

    public function searchByKeywords()
    {

        $layer = trim(request()->layer);
        $keywords = trim(request()->keywords);

        $gid = null;
        $point = null;
        $geom = null;
        // Check if layer is valid and keywords are provided
        if (in_array($layer, ['places_layer', 'roadlines_layer']) && $keywords) {
            if ($layer == 'places_layer') {
                 // Search for places matching the keywords
                $results = DB::select("select id, ST_AsText(geom) AS point from layer_info.places where deleted_at is null AND lower(name) = lower(?) AND geom IS NOT NULL LIMIT 1", [$keywords]);
                if (count($results) > 0) {
                    $row = $results[0];
                    $gid = $row->id;
                    $point = $row->point;
                }
            } else if ($layer == 'roadlines_layer') {
                 // Search for roadlines matching the keywords
                $results = DB::select("select code, ST_AsText(geom) AS geom from utility_info.roads where deleted_at is null AND lower(name) = lower(?) AND geom IS NOT NULL LIMIT 1", [$keywords]);
                if (count($results) > 0) {
                    $row = $results[0];
                    $gid = $row->code;
                    $geom = $row->geom;
                }
            }
        }

        return array(
            'gid' => $gid,
            'point' => $point,
            'geom' => $geom
        );
    }

    /**
     * Searches for buildings based on a specified field and value.
     *
     * @return array|null An array of buildings matching the search criteria, or null if no matching buildings are found.
     */

    public function searchBuilding()
    {
        $field = request()->field;
        $val = request()->val;
          // Trimming field and value
        $field = trim($field);
        $val = trim($val);
        $data = array();
        // Checking if the provided field is valid and if the value is not empty
        if (in_array($field, ['bin', 'holding', 'taxcd']) && $val) {
             // Constructing the SQL query to search for buildings
            $building_query = "SELECT bin, ST_AsText(geom) AS geom, building_associated_to FROM building_info.buildings WHERE deleted_at is null AND " . $field . " = ?";
            $results = DB::select($building_query, [$val]);
            if (count($results) > 0) {

                foreach ($results as $row) {
                    $building = array();
                    $building['bin'] = $row->bin;
                    $building['geom'] = $row->geom;
                    $building['building_associated_to'] = $row->building_associated_to;
                    $data[] = $building;
                }
            }
        }

        return count($data) > 0 ? $data : null;
    }

    /**
     * Performs autocomplete search based on provided layer and keywords.
     *
     * @return array Results of autocomplete search.
     */

    public function searchAutoComplete()
    {
        $layer = request()->layer;
        $keywords = request()->keywords;
        // Trimming whitespace from the layer and keywords
        $layer = trim($layer);
        $keywords = trim($keywords);

        $results = [];
        if (in_array($layer, ['places_layer', 'roadlines_layer']) && $keywords) {
            if ($layer == 'places_layer') {
                // Querying the database for places matching the provided keywords
                $results = array_pluck(DB::select("SELECT DISTINCT name FROM layer_info.places WHERE deleted_at is null AND LOWER(name) LIKE LOWER('%" . $keywords . "%') AND geom IS NOT NULL LIMIT 10"), 'name');
            } else if ($layer == 'roadlines_layer') {
                 // Querying the database for roadlines matching the provided keywords
                $results = array_pluck(DB::select("SELECT DISTINCT name FROM utility_info.roads WHERE deleted_at is null AND LOWER(name) LIKE LOWER('%" . $keywords . "%') AND geom IS NOT NULL LIMIT 10"), 'name');
            }
        }

        return $results;
    }

    /**
     * Retrieves buildings within a buffered polygon and their corresponding population content HTML.
     *
     * @param Request $request The request object containing buffer distance and polygon geometry.
     * @return array Array containing buildings, population content HTML, and polygon information.
     */

    public function getBufferPolygonBuildings(Request $request)
    {
        if ($request->bufferDisancePolygon > 0) {
            $bufferDistancePolygon = $request->bufferDisancePolygon;
        } else {
            $bufferDistancePolygon = 0;
        }
        $bufferPolygonGeom = $request->bufferPolygonGeom;
        // Call the maps service to retrieve buildings and population contents within the buffered polygon
        $results = $this->mapsService->buildingsPopContentPolygon($bufferDistancePolygon, $bufferPolygonGeom);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];
    }

    /**
     * Retrieves buildings within a specific ward and their corresponding population content HTML.
     *
     * @param Request $request The request object containing the ward and optional buffer distance.
     * @return array Array containing buildings and population content HTML.
     */
    public function getWardBuildings(Request $request)
    {
        $ward = $request->ward;
        // Check if buffer distance is provided, if not set it to 0
        if ($request->bufferDisancePolygon > 0) {
            $bufferDisancePolygon = $request->bufferDisancePolygon;
            } else {
                $bufferDisancePolygon = 0;
            }
             // Query to fetch the geometry of the specified ward
        $wardQuery = "SELECT ST_AsText(geom) AS geom from layer_info.wards WHERE ward = '" . $ward . "'";
        $ward = DB::select($wardQuery);
         // Return the results including buildings and population contents HTML
        $results = $this->mapsService->buildingsPopContentPolygon($bufferDisancePolygon, $ward[0]->geom);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
        ];
    }

    /**
     * Retrieves the sum of population within a specified polygon area.
     *
     * @param Request $request The request object containing the geometry of the polygon.
     * @return mixed The sum of population within the polygon area or an error message if the 'geom' field is missing.
     */
    public function getAreaPopulationPolygonSum(Request $request)
    {
        if ($request->geom) {
            $results = $this->mapsService->getAreaPopulationPolygonSumInfo($request->geom);
            return $results;
        } else {
            return "The 'geom' field is required";
        }
    }

    /**
     * Retrieves the center coordinates of a specified ward.
     *
     * @param Request $request The request object containing the ward identifier.
     * @return array Array containing the geometry and identifier of the specified ward.
     */

    public function getWardCenterCoordinates(Request $request)
    {
        //  query to fetch ward geometry based on the provided ward number
        $wardQuery = "SELECT ward, ST_AsText(geom) AS geom FROM layer_info.wards WHERE ward = $request->ward";
    	$results = DB::select($wardQuery);
        if(count($results) > 0) {
            $row = $results[0];
        }
        return array(
            'geom' => $row->geom,
            'ward' => $request->ward
        );
    }

    /**
     * Retrieves the center coordinates of the clipped ward geometry.
     *
     * @param Request $request The request object containing the ward information for clipping.
     * @return array Array containing the geometry and ward identifier.
     */
    public function getClipWardCenterCoordinates(Request $request)
    {
        // SQL query to collect geometry of wards that are not in the provided list or are null
        $wardQuery = "SELECT ST_AsText(ST_Collect(geom)) AS geom FROM layer_info.ward_overlay WHERE ward NOT IN ($request->ward) or ward is null";
    	$results = DB::select($wardQuery);
        // Check if there are any results
        if(count($results) > 0) {
            $row = $results[0];
        }
        return array(
            'geom' => $row->geom,
            'ward' => $request->ward
        );
    }

    /**
     * Retrieves the owner information of a building based on its BIN (Building Identification Number).
     *
     * @param Request $request The request object containing the BIN of the building.
     * @return BuildOwner|null The owner information of the building, or null if not found.
     */
    public function getOwnerOfBuilding(Request $request){
         // Retrieve the owner information of the building based on the provided BIN (Building Identification Number)
        $owner = BuildOwner::select('*')->where('bin', $request->bin)->first();
        return $owner;
    }

    /**
     * Checks the type of geometry provided in the request.
     *
     * @param Request $request The request object containing geometry information.
     * @return string The type of geometry.
     */
    public function checkGeometryType(Request $request){
        // Construct the SQL query to check the geometry type based on the provided geometry
        $checkGeometryQuery = "SELECT ST_GeometryType(ST_GeomFromText('". $request->geom ."')) AS geometry_type;";
        $result = DB::select($checkGeometryQuery);
        return $result[0]->geometry_type;
    }

    /**
     * Retrieves summary information about road inaccessibility based on provided road width and vacuum range.
     *
     * @param Request $request The request object containing road width and vacuum range parameters.
     * @return array Array containing buildings, population content HTML, and polygon information.
     */
    public function roadInaccesibleISummaryInfo(Request $request)
    {
         // Check if road width is provided, if not set it to a default value of 2 meters
        if ($request->roadWidth > 0) {
            $roadWidth = $request->roadWidth;
        } else {
            $roadWidth = 2;
        }
         // Check if vacutug range is provided, if not set it to a default value of 100 meters
        if($request->vacutugRange > 0) {
            $vacutugRange = $request->vacutugRange;
        } else {
            $vacutugRange = 100;
        }
        $roadWidth = 2;
        // Convert vacutug range from feet to meters
        $vacutugRange = 100 * 0.3048; //30.48
         // Call the maps service to retrieve road inaccessible summary information
        $results = $this->mapsService->getRoadInaccesibleISummaryInfo($roadWidth, $vacutugRange);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];
    }

    /**
     * Retrieves a report of buildings close to water bodies within a specified range.
     *
     * @param Request $request The request object containing parameters for generating the report.
     * @return \Illuminate\Http\Response Excel file containing summary information of buildings close to water bodies.
     */
    public function getPolygonWaterbodyInaccessibleReport(Request $request)
    {
        ob_end_clean();
        ini_set('memory_limit', '8192M');

        //converting to meter if in feet
        if($request->waterbody_hose_length_unit_report == 'feet')
        {
            $range = ( $request->waterbody_hose_length_report ) / 3.28;
        }
        else{
            $range = ( $request->waterbody_hose_length_report );
        }

         // Query to retrieve the union of waterbody geometries
        $query = "SELECT ST_AsText(ST_Union(geom)) AS geom FROM layer_info.waterbodys";
        $bufferQuery = DB::select($query);
        $row = $bufferQuery[0];

        // Query to create a buffer polygon around the waterbody geometries
        $polygon_query = "SELECT ST_AsText(ST_Buffer(ST_GeomFromText('" . $row->geom . "', 4326)::GEOGRAPHY, " . $range . ")) AS circle_geog";
        $polygon_result = DB::select($polygon_query);
        $polygon = $polygon_result[0]->circle_geog;

        // Download Excel report with summary information about buildings close to waterbodies
        return $this->excel->download(new SummaryInfoMultiSheetExport($polygon, $range), 'Summary Information buildings close to waterbodys.xlsx');
    }

    /**
     * Retrieves buildings within a water body's inaccessible zone along with their corresponding population content HTML.
     *
     * @param Request $request The request object containing hose length and its unit.
     * @return array Array containing buildings, population content HTML, and polygon information.
     */
    public function waterbodyInaccessibleBuildings(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'hose_length' => 'required|numeric',
        ]);

        //converting to meter if in feet

        if($request->house_length_unit == 'feet')
        {
            $hose_length = ( $request->hose_length ) / 3.28;
        }
        else{
            $hose_length = ( $request->hose_length );
        }

        // Call the maps service to get summary information about buildings inaccessible due to water bodies
        $results = $this->mapsService->getWaterbodyInaccesibleISummaryInfo($hose_length);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];
    }

    /**
     * Retrieves inaccessible buildings along a road based on provided road width and house length.
     *
     * @param Request $request The request object containing road width, road width unit, house length, and house length unit.
     * @return array Array containing buildings, population content HTML, and polygon information.
     */
    public function roadInaccessibleBuildings(Request $request)
    {
        Validator::extend('lte_db', function ($attribute, $value, $parameters, $validator) {
            // Extract table name and column name from parameters
            $tableName = $parameters[0];
            $columnName = $parameters[1];
        
            // Query the database to get the maximum value from the column
            $maxValue = DB::table($tableName)
                            ->max($columnName);
            // If the maximum value is numeric
            if (is_numeric($maxValue)) {
                // Compare the input value with the maximum value
                if ($value <= $maxValue) {
                    return true; // Validation passes
                }
            }
            // If validation fails, construct the error message dynamically
            $errorMessage = 'The carrying width must be less than or equal to ' . $maxValue;

            // Manually add the error message to the validator
            $validator->errors()->add('road_width', $errorMessage);

            return false; // Validation fails
        }, 'lte_db');
        
        $this->validate($request, [
            'road_width' => 'required|numeric|lte_db:utility_info.roads,carrying_width',
            'hose_length' => 'required|numeric',
        ]);


        //converting to meter if in feet
        if($request->road_width_unit == 'feet')
        {
            $road_width = ( $request->road_width ) / 3.28;
        }
        else{
            $road_width = ( $request->road_width );
        }

        if($request->house_length_unit == 'feet')
        {
            $hose_length = ( $request->hose_length ) / 3.28;
        }
        else{
            $hose_length = ( $request->hose_length );
        }

        // Call the maps service to get summary information about road inaccessible buildings
        $results = $this->mapsService->getRoadInaccesibleISummaryInfo($road_width, $hose_length);
        return [
            'buildings' => $results['buildings'],
            'popContentsHtml' => $results['popContentsHtml'],
            'polygon' => $results['polygon']
        ];
    }

    /**
     * Retrieves a report of inaccessible road areas within a specified range and width.
     *
     * @param Request $request The request object containing road width, width unit, hose length, and length unit.
     * @return \Illuminate\Http\Response Excel download response containing the summary information of inaccessible road areas.
     */
    public function getPolygonRoadInaccessibleReport(Request $request)
    {

        ob_end_clean();
        ini_set('max_execution_time', 180); 
        ini_set('memory_limit', '8192M');
        //converting to meter if in feet
        if($request->road_width_unit_report == 'feet')
        {
            $width = ( $request->road_width_report ) / 3.28;
        }
        else{
            $width = ( $request->road_width_report );
        }

        if($request->road_hose_length_unit_report == 'feet')
        {
            $range = ( $request->road_hose_length_report ) / 3.28;
        }
        else{
            $range = ( $request->road_hose_length_report );
        }

         // query to get the buffered polygon of roads wider than the specified width
        $query = "SELECT ST_AsText(ST_Union(geom)) AS geom FROM utility_info.roads WHERE carrying_width >= $width";
        $bufferQuery = DB::select($query);
        $row = $bufferQuery[0];

          // Construct a buffer polygon based on the specified range
        $polygon_query = "SELECT ST_AsText(ST_Buffer(ST_GeomFromText('" . $row->geom . "', 4326)::GEOGRAPHY, " . $range . ")) AS circle_geog";
        $polygon_result = DB::select($polygon_query);
        $polygon = $polygon_result[0]->circle_geog;

        // Get the geometry of the city polygon
        $cityPoly = "SELECT ST_AsText(geom) AS geom FROM layer_info.citypolys";
        $cityPolyResult = DB::select($cityPoly);
        $cityPolygon = $cityPolyResult[0]->geom;

         // Calculate the remaining area by differencing the city polygon and the buffered road polygon
        $remainingPolygonQuery = "SELECT ST_AsText(ST_Difference(ST_GeomFromText('$cityPolygon'), ST_GeomFromText('$polygon'))) AS geom";
        $remainingPolygon = DB::select($remainingPolygonQuery);
        $remainingPolygonGeom = $remainingPolygon[0]->geom;

        // Download the Excel file containing summary information of the remaining polygon
        return $this->excel->download(new SummaryInfoMultiSheetExport($remainingPolygonGeom, 0), 'Summary Information for hard to reach building.xlsx');
    }

    /**
     * Retrieves buildings with toilet network information based on the provided bin.
     *
     * @param Request $request The request object containing the BIN (Building Identification Number).
     * @return array|null Returns an array of buildings with toilet network information if found, otherwise returns null.
     */
    public function getBuildingsToiletNetwork(Request $request) {
        $bin = $request->bin;

        //query to retrieve buildings with toilet network information based on the provided bin
        $query = "Select ST_AsText(get_ctpt_dependent_buildings_wReturnGeom_Linestring('$bin')) AS geom";
        $result = DB::select($query);
        if($result[0]->geom){
            return $result;
        } else {
            return null;
        }

    }

}
