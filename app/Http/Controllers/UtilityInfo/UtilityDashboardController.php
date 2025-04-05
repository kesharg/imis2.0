<?php
//Last Modified Date: 19-04-2024
//Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)

namespace App\Http\Controllers\UtilityInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UtilityInfo\UtilityDashboardService;

class UtilityDashboardController extends Controller
{


        protected UtilityDashboardService $utilitydashboardService;


        public function __construct(UtilityDashboardService $utilitydashboardService)
        {
            $this->middleware('auth');
            $this->utilitydashboardService = $utilitydashboardService;
        }

        public function index(){

                $page_title = 'Utility Dashboard';
                $roadsSurfaceTypePerWardChart = $this->utilitydashboardService->getRoadsSurfaceTypePerWardChart();
                $roadsHierarchyPerWardChart = $this->utilitydashboardService->getRoadsHierarchyPerWardChart();
                $drainsTypePerWardChart = $this->utilitydashboardService->getDrainsTypePerWardChart();
                $sewerLengthPerWardChart = $this->utilitydashboardService->getSewerLengthPerWard();
                $roadLengthPerWardChart = $this->utilitydashboardService->getRoadLengthPerWardChart();
                $drainLengthPerWardChart = $this->utilitydashboardService->getDrainLengthPerWardChart();
                $sewerWidthPerWardChart = $this->utilitydashboardService->getSewerDiameterPerWardChart();
                $drainWidthPerWardChart =  $this->utilitydashboardService->getDrainDiameterPerWardChart();
                $roadWidthPerWardChart =  $this->utilitydashboardService->getRoadDiameterPerWardChart();
                $watersupplyLenghtPerWardChart = $this->utilitydashboardService->getWaterSupplyLengthPerWardChart();
                $watersupplyTypePerWardChart = $this->utilitydashboardService->getWaterSupplyDiameterPerWardChart();


                return view('dashboard.utilityDashboard', compact('page_title',
                'roadsSurfaceTypePerWardChart','roadsHierarchyPerWardChart','drainsTypePerWardChart',
                'sewerLengthPerWardChart','roadLengthPerWardChart','drainLengthPerWardChart',
                'sewerWidthPerWardChart','drainWidthPerWardChart','roadWidthPerWardChart',
                'watersupplyLenghtPerWardChart','watersupplyTypePerWardChart'

        ));

        }
}
