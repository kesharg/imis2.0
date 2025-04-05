<?php

namespace App\Http\Controllers\Swm;

use App\Http\Controllers\Controller;
use App\Services\Swm\SwmRegistrations\CollectionPointService;
use App\Models\Fsm\KeyPerformanceIndicator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\BuildingInfo\Building;
use App\Models\Fsm\Containment;
use App\Models\Fsm\Emptying;
use App\Models\Fsm\ServiceProvider;
use App\Models\Fsm\Application;
use App\Models\Fsm\Feedback;
use App\Models\Fsm\TreatmentPlant;
use App\Models\Fsm\SludgeCollection;






use App\Services\DashboardService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class SolidWasteManagementController extends Controller
{
    protected CollectionPointService $collectionPointService;

    public function __construct(CollectionPointService $collectionPointService)
    {
        $this->collectionPointService = $collectionPointService;
    }

    /**
     * Display a list of collection points.
     *
     * @return View
     */
    public function index()
    {
        
        $transferStationChart = $this->getTransferStationChart();
        $landFillSitesChart = $this->getLandFillSitesChart();
        $noOfTripsTransferStations = $this->getNoOfTripsTransferStations();
        $noOfCollectionPointReached = $this->getNoOfCollectionPointReached();
        $noOfCollectionPointHouseHoldServed = $this->getNoOfCollectionPointHouseHoldServed();
        $volumeOfWasteRecycled = $this->getVolumeOfWasteRecycled();
        return view('swm.index', compact('transferStationChart', 'landFillSitesChart', 'noOfTripsTransferStations', 'noOfCollectionPointReached', 'noOfCollectionPointHouseHoldServed', 'volumeOfWasteRecycled'));
    }
    
    public function getTransferStationChart()
    {
       
        $queryLogins = "SELECT sum(volume) AS total_volume FROM swm.transfer_log_ins";
        $resultsLogins = DB::select($queryLogins);
        $queryLogOuts = "SELECT sum(volume) AS total_volume FROM swm.transfer_log_outs";
        $resultsLogouts = DB::select($queryLogOuts);

        $labels = array('"\Transfer Logins"', '"\Transfer Logouts"');
        $values = array($resultsLogins[0]->total_volume, $resultsLogouts[0]->total_volume);
        $colors = array('"rgba(122, 195, 106, 0.8)"', '"rgba(90, 155, 212, 0.8)"');

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }
    
    public function getLandFillSitesChart()
    {
        
        $queryLandFillSites = "SELECT sum(volume) AS total_volume FROM swm.transfer_log_outs WHERE received = true";
        $resultsLandFillSites = DB::select($queryLandFillSites);
        
        $queryLogouts = "SELECT sum(volume) AS total_volume FROM swm.transfer_log_outs WHERE received = false";
        $resultsLogouts = DB::select($queryLogouts);

        $labels = array('"\Landfill Sties"', '"\Transfer Logouts"');
        $values = array($resultsLandFillSites[0]->total_volume, $resultsLogouts[0]->total_volume);
        $colors = array('"rgba(122, 195, 106, 0.8)"', '"rgba(90, 155, 212, 0.8)"');

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }
    
    public function getNoOfTripsTransferStations()
    {
        
        $queryLandFillSites = "SELECT route_id, count(*) AS total_count FROM swm.transfer_log_ins group by route_id";
        $resultsLandFillSites = DB::select($queryLandFillSites);

        $labels = array();
        $values = array();

        foreach ($resultsLandFillSites as $row) {
            $labels[] = '"' . $row->route_id . '"';
            $values[] = $row->total_count;
        }
        $colors = array('"rgba(122, 195, 106, 0.8)"', '"rgba(90, 155, 212, 0.8)"');

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }
    public function getNoOfCollectionPointReached() {
        $chart = array();
        
        $query = 'SELECT sp.name AS service_provider, COUNT(cp.id) AS count'
                . ' FROM swm.collection_points cp'
                . ' LEFT JOIN swm.service_providers sp'
                . ' ON sp.id = cp.service_provider_id'
                . ' GROUP BY cp.service_provider_id, sp.name'
                . ' ORDER BY cp.service_provider_id';
       
        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach($results as $row) {
            $labels[] = '"' . $row->service_provider . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }
    
    public function getNoOfCollectionPointHouseHoldServed() {
        $chart = array();
        
        $query = 'SELECT sp.name AS service_provider, sum(cp.household_served) AS count'
                . ' FROM swm.collection_points cp'
                . ' LEFT JOIN swm.service_providers sp'
                . ' ON sp.id = cp.service_provider_id'
                . ' GROUP BY cp.service_provider_id, sp.name'
                . ' ORDER BY cp.service_provider_id';
       
        $results = DB::select($query);

        $labels = array();
        $values = array();
        foreach($results as $row) {
            $labels[] = '"' . $row->service_provider . '"';
            $values[] = $row->count;
        }

        $chart = array(
            'labels' => $labels,
            'values' => $values,
        );

        return $chart;
    }
    
    public function getVolumeOfWasteRecycled() {
        
        
        $wasteRecycle = "SELECT sum(volume) AS total_volume FROM swm.waste_recycles";
        $resultWasteRecycle = DB::select($wasteRecycle);
        
        $logouts = "SELECT sum(volume) AS total_volume FROM swm.transfer_log_outs";
        $resultsLogouts = DB::select($logouts);

        $labels = array('"\Waste Recycle"', '"\Transfer Logouts"');
        $values = array($resultWasteRecycle[0]->total_volume, $resultsLogouts[0]->total_volume);
        $colors = array('"rgba(122, 195, 106, 0.8)"', '"rgba(90, 155, 212, 0.8)"');

        $chart = [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors
        ];

        return $chart;
    }
}
