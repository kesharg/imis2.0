<?php
// Last Modified Date: 19-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers;

use App\Models\Fsm\KeyPerformanceIndicator;
use Illuminate\Http\Request;
use App\Models\Fsm\VacutugType;
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
use App\Models\UtilityInfo\Roadline;
use App\Models\UtilityInfo\SewerLine;
use App\Models\UtilityInfo\Drain;
use App\Models\Fsm\Ctpt;
use App\Models\Fsm\CtptUsers;
use App\Models\Fsm\Hotspots;
use App\Models\PublicHealth\YearlyWaterborne;
use App\Models\UtilityInfo\WaterSupplys;
use App\Models\WaterSupplyInfo\WaterSupply;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected DashboardService $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page_title = 'IMIS Dashboard';
        //buildingCount
        // Total number of buildings (Displayed as "Total Building" in UI)
        $buildingCount = Building::whereNull('deleted_at')->count();
        // Number of commercial buildings (Displayed as "Commercial" in UI)
        $commercialBuildCount = (Building::where('functional_use_id', 2)->whereNull('deleted_at')->count());
        // Number of residential buildings (Displayed as "Residential" in UI)
        $residentialBuildingCount = Building::where('functional_use_id', 1)->whereNull('deleted_at')->count();
        // Number of mixed-use buildings (Residential + Commercial) (Displayed as "Mixed (Residential + Commercial)" in UI)
        $mixedBuildCount = Building::where('functional_use_id', 4)->whereNull('deleted_at')->count();
        // Number of government buildings (Displayed as "Institutions" in UI)
        $governmentBuildingCount = Building::where('functional_use_id', 5)->whereNull('deleted_at')->count();
        // Number of industrial buildings (Displayed as "Industrial" in UI)
        $industrialBuildingCount = Building::where('functional_use_id', 7)->whereNull('deleted_at')->count();
        // Number of buildings with other functional uses (Displayed as "Others" in UI)
        $othersCount = $buildingCount - ($commercialBuildCount + $residentialBuildingCount + $mixedBuildCount + $governmentBuildingCount + $industrialBuildingCount);
        //Sanitation Systems Count
        // Total number of sanitation systems (Displayed as "Total Containments" in UI)
        $containmentCount = Containment::whereNull('deleted_at')->count();
        //Utility Count
        // Calculate the total length of roads (Displayed as "Total length (m) of roads" in UI)
        $sumRoads = Roadline::sum('length');
        // Calculate the total length of sewers (Displayed as "Total length (m) of sewers" in UI)
        $sumSewers = SewerLine::sum('length');
        // Calculate the total length of drains (Displayed as "Total length (m) of drains" in UI)
        $sumDrains = Drain::sum('length');
        // Calculate the total length of water supply (Displayed as "Total length (m) of water supply" in UI)
        $sumWatersupply = WaterSupplys::sum('length');
        //Ptct count
        $ctCount = Ctpt::where('type', 'Community Toilet')->where('status', true)->whereNull('deleted_at')->count();
        $ptCount = Ctpt::where('type', 'Public Toilet')->where('status', true)->whereNull('deleted_at')->count();
        // Query to calculate the total number of users served by community toilets for the specified year
        $communityToilet = DB::table('fsm.toilets as t')
        ->select(DB::raw('sum(b.population_served) as toilet_users')) // Selecting the sum of users served by community toilets
        ->leftJoin('fsm.build_toilets as bt', 'bt.toilet_id', '=', 't.id') // Joining with build_toilets table
        ->leftJoin('building_info.buildings as b', 'b.bin', '=', 'bt.bin') // Joining with buildings table
        ->where('t.type', '=', 'Community Toilet')
        ->where('t.status',true)
        ->whereNull('t.deleted_at')->first(); // Retrieve the first row

        // Extracting the total number of users served by community toilets
        $totalCtUser = $communityToilet->toilet_users;

        // Query to calculate the total number of users served by public toilets for the specified year
        $publicToilet = DB::table('fsm.toilets as t')
        ->join('fsm.ctpt_users as u', 't.id', '=', 'u.toilet_id') // Joining with ctpt_users table
        ->selectRaw('sum(u.no_male_user) as total_male_user, sum(u.no_female_user) as total_female_user') // Selecting the sum of male and female users
        ->where('t.type', 'Public Toilet') // Filtering for public toilets
        ->where('t.status', true)
        ->whereNull('t.deleted_at')->whereNull('u.deleted_at')->first(); // Retrieve the first row

        // Calculating the total number of users served by public toilets
        $totalPtUser = $publicToilet->total_male_user + $publicToilet->total_female_user;
        $maxDate = date('Y');
        $minDate = date('Y') - 4;
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk')) {
            $whereRawEmptyingsServiceProvider = "emptyings.service_provider_id = " . Auth::user()->service_provider_id;
            $whereRawApplicationsServiceProvider = "applications.service_provider_id = " . Auth::user()->service_provider_id;
            $whereRawSludgeCollectionServiceProvider = "sludge_collections.service_provider_id = " . Auth::user()->service_provider_id;
            $whereRawDesludgingVehicleServiceProvider = "service_provider_id = " . Auth::user()->service_provider_id;
            $whereRawFeedbackServiceProvider = "feedbacks.service_provider_id = " . Auth::user()->service_provider_id;
            $whereUserId = "users.id = " . Auth::user()->id;
            $whereRawSludgeServiceProvider = "sludge_collections.service_provider_id = " . Auth::user()->service_provider_id;
        } else {
            $whereRawEmptyingsServiceProvider = "1 = 1";
            $whereRawApplicationsServiceProvider = "1 = 1";
            $whereRawSludgeCollectionServiceProvider = "1 = 1";
            $whereRawDesludgingVehicleServiceProvider = "1 = 1";
            $whereRawFeedbackServiceProvider = "1 = 1";
            $whereUserId = "1 = 1";
            $whereRawSludgeServiceProvider = "1 = 1";
        }
        if (Auth::user()->hasRole('Treatment Plant')) {
            $whereRawTreatmentPlant = "treatment_plant_id = " . Auth::user()->treatment_plant_id;
        } else {
            $whereRawTreatmentPlant = "1 = 1";
        }



        //FSM Service Dashboard
        // Check if a specific year is provided
        if (request()->year) {
            // Set the maximum date to the requested year
            $maxDate = request()->year;

            // Retrieve monthly application requests by operators for the requested year
            $monthlyAppRequestByoperators = $this->dashboardService->getMonthlyAppRequestByoperators(request()->year);

            // FSM services

            // Calculate the count of unique containment codes emptied for the requested year
            $uniqueContainCodeEmptiedCount = Application::whereYear('created_at', '=', request()->year)
                ->where('emptying_status', true)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawApplicationsServiceProvider)
                ->distinct('containment_id')
                ->count('containment_id');

            // Calculate the count of emptying services for the requested year
            $emptyingServiceCount = Application::whereYear( 'created_at', '=' , request()->year )->where('emptying_status', true)->whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->count('id');


            // Calculate the count of active service providers
           $serviceProvidersRs = ServiceProvider::leftJoin('auth.users', 'service_providers.id', '=', 'users.service_provider_id')
                    ->select('service_providers.id')
                    ->where('service_providers.status', 1)
                    ->whereRaw($whereUserId)
                    ->whereYear('service_providers.created_at', '<=', request()->year)
                    ->whereNull('service_providers.deleted_at')
                    ->groupBy(['service_providers.id', 'service_providers.company_name'])
                    ->get();

                // Calculate the count of active service providers
            $serviceProviderCount = count($serviceProvidersRs);

            // Calculate the count of applications for the requested year
            $applicationCount = Application::whereYear('created_at', '=', request()->year)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawApplicationsServiceProvider)
                ->count();

            // Retrieve the cost paid by containment owners per ward for the requested year
            $costPaidByContainmentOwnerPerwardChart = $this->dashboardService->getCostPaidByContainmentOwnerPerward(request()->year);

            // Calculate the sum of sludge collected from emptying services for the requested year
            $sludgeCollectionEmptyingServices = Emptying::whereYear('created_at', '=', request()->year)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawEmptyingsServiceProvider)
                ->sum('volume_of_sludge');

            // Calculate the sum of sludge collected from sludge collections for the requested year
            $sludgeCollectionsCount = SludgeCollection::whereYear('date', '=', request()->year)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawSludgeCollectionServiceProvider)
                ->sum('volume_of_sludge');

            // Calculate the count of desludging vehicles
            $desludgingVehicleCount = VacutugType::whereYear( 'created_at', '<=', request()->year )->where('status', 1)->whereNull('deleted_at')->whereRaw($whereRawDesludgingVehicleServiceProvider)->count();


            // Calculate the count of treatment plants
            $treatmentPlantCount = TreatmentPlant::leftJoin('auth.users', 'treatment_plants.id','=', 'users.treatment_plant_id')->where('treatment_plants.status', 1)->whereYear( 'treatment_plants.created_at', '<=' , request()->year )->whereNull('treatment_plants.deleted_at')->distinct('treatment_plants.id')->whereRaw($whereUserId)->distinct('treatment_plants.id')->count('treatment_plants.id');

            // Calculate the cost paid by owners with receipts for the requested year
            $costPaidByOwnerWithReceipt = Emptying::whereYear('created_at', '=', request()->year)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawEmptyingsServiceProvider)
                ->sum('total_cost');

            // Retrieve data for emptying service per wards, assessment, and feedback for the requested year
            $emptyingServicePerWardsAssessmentFeedbackChart = $this->dashboardService->getEmptyingServicePerWardsAssessmentFeedback(request()->year);

            // Retrieve data for feedback charts for the requested year
            $fsmSrvcQltyChart = $this->dashboardService->getFsmSrvcQltyChart(request()->year);

            $ppe = $this->dashboardService->getppeChart(request()->year);

            $sludgeCollectionByTreatmentPlantChart = $this->dashboardService->getSludgeCollectionByTreatmentPlantChart(request()->year);
            $hotspotsPerWardChart = $this->dashboardService->getHotspotsPerWard(request()->year);

            $fsmCampaignsPerWardChart = $this->dashboardService->getFsmCampaignsPerWard(request()->year);
            $fsmCampaignsSupportedByChart = $this->dashboardService->getFsmCampaignsSupportedBy(request()->year);
            $numberOfEmptyingbyMonthsChart = $this->dashboardService->getNumberOfEmptyingbyMonths(request()->year);
            $totalHotspot = Hotspots::count();
            $totalWaterborne = YearlyWaterborne::count();


        }
         else {
             // Fetching the number of emptying by months chart data
            $numberOfEmptyingbyMonthsChart = $this->dashboardService->getNumberOfEmptyingbyMonths(null);
            // Counting the unique containment codes emptied
            $uniqueContainCodeEmptiedCount = Application::where('emptying_status', true)
                ->whereNull('deleted_at')
                ->whereRaw($whereRawApplicationsServiceProvider)
                ->distinct('containment_id')
                ->count('containment_id');
            // Counting the number of emptying services
            $emptyingServiceCount = Application::whereRaw($whereRawApplicationsServiceProvider)->where('emptying_status', true)->whereNull('deleted_at')->count('id');

            // Fetching service providers and their IDs
            $serviceProvidersRs = ServiceProvider::leftJoin('auth.users', 'service_providers.id', '=', 'users.service_provider_id')
                ->select('service_providers.id')
                ->where('service_providers.status', 1)
                ->whereRaw($whereUserId)
                ->whereNull('service_providers.deleted_at')
                ->groupBy(['service_providers.id', 'service_providers.company_name'])
                ->get();
            // Counting the number of service providers
            $serviceProviderCount = (count($serviceProvidersRs));

           // Counting the total number of applications
            $applicationCount = Application::whereNull('deleted_at')
            ->whereRaw($whereRawApplicationsServiceProvider)
            ->count();

            // Fetching the cost paid by containment owner per ward chart data
            $costPaidByContainmentOwnerPerwardChart = $this->dashboardService->getCostPaidByContainmentOwnerPerward(null);

            // Summing the volume of sludge collected from emptying services
            $sludgeCollectionEmptyingServices = Emptying::whereNull('deleted_at')
            ->whereRaw($whereRawEmptyingsServiceProvider)
            ->sum('volume_of_sludge');

            // Summing the total volume of sludge collected from sludge collection services
            $sludgeCollectionsCount = SludgeCollection::whereNull('deleted_at')
            ->whereRaw($whereRawSludgeCollectionServiceProvider)
            ->sum('volume_of_sludge');

            // Counting the number of treatment plants
            $treatmentPlantCount = TreatmentPlant::leftJoin('auth.users', 'treatment_plants.id','=', 'users.treatment_plant_id')->where('treatment_plants.status', 1)->whereNull('treatment_plants.deleted_at')->whereRaw($whereUserId)->distinct('treatment_plants.id')->count('treatment_plants.id');

            // Counting the number of desludging vehicles
            $desludgingVehicleCount = VacutugType::where('status', 1)->whereNull('deleted_at')->whereRaw($whereRawDesludgingVehicleServiceProvider)->count();

            // Summing the total cost paid by owner with receipts
            $costPaidByOwnerWithReceipt = Emptying::whereNull('deleted_at')
            ->whereRaw($whereRawEmptyingsServiceProvider)
            ->sum('total_cost');

            // Fetching data for the emptying service per wards assessment feedback chart
            $emptyingServicePerWardsAssessmentFeedbackChart = $this->dashboardService->getEmptyingServicePerWardsAssessmentFeedback(null);

            // Fetching monthly application requests by operators
            $monthlyAppRequestByoperators = $this->dashboardService->getMonthlyAppRequestByoperators(null);

            // Fetching data for FSM service quality chart
            $fsmSrvcQltyChart = $this->dashboardService->getFsmSrvcQltyChart(null);

            // Fetching data for Personal Protective Equipment (PPE) chart
            $ppe = $this->dashboardService->getppeChart(null);

            // Fetching data for hotspots per ward chart
            $hotspotsPerWardChart = $this->dashboardService->getHotspotsPerWard(null);

            // Fetching data for sludge collection by treatment plant chart
            $sludgeCollectionByTreatmentPlantChart = $this->dashboardService->getSludgeCollectionByTreatmentPlantChart(null);

            // Fetching data for FSM campaigns per ward chart
            $fsmCampaignsPerWardChart = $this->dashboardService->getFsmCampaignsPerWard(null);

            // Fetching data for FSM campaigns supported by chart
            $fsmCampaignsSupportedByChart = $this->dashboardService->getFsmCampaignsSupportedBy(null);

            // Counting the total number of hotspots
            $totalHotspot = Hotspots::count();

            // Counting the total number of yearly waterborne diseases
            $totalWaterborne = YearlyWaterborne::count();
        }

       // Fetching data for buildings per ward chart
        $buildingsPerWardChart = $this->dashboardService->getBuildingsPerWardChart();

        // Fetching data for sanitation systems chart
        $sanitationSystemsChart = $this->dashboardService->getSanitationSystemsChart();

        // Fetching data for emptying requests by structure types chart
        $emptyingRequestsbyStructureTypesChart = $this->dashboardService->getEmptyingRequestsPerStructureTypeChart();

        // Fetching data for containment types per ward chart
        $containmentTypesPerWardChart = $this->dashboardService->getContainmentTypesPerWard();

       // Fetching data for containment types by structure types chart
        $containmentTypesByStructypesChart = $this->dashboardService->getContainmentTypesByStructypes();

        // Fetching data for containment types by building uses chart
        $containmentTypesByBldgUsesChart = $this->dashboardService->getContainmentTypesByBldgUse();

        // Fetching data for containment types by building uses (residentials) chart
        $containmentTypesByBldgUsesResidentialsChart = $this->dashboardService->getContainmentTypesByBldgUseResidentials();

        // Fetching data for containment types by land use chart
        $containmentTypesByLanduseChart = $this->dashboardService->getContainmentTypesByLanduse();

       // Fetching data for emptying service by type and year chart
        $emptyingServiceByTypeYearChart = $this->dashboardService->getEmptyingServiceByTypeYear();

        // Fetching data for containment emptied by ward chart
        $containmentEmptiedByWardChart = $this->dashboardService->getcontainmentEmptiedByWard();

        // Fetching data for containment type chart
        $containTypeChart = $this->dashboardService->getContainTypeChart();

        // Fetching data for building use chart
        $buildingUseChart = $this->dashboardService->getBuildingUseChart();

        // Fetching data for next emptying containments chart
        $nextEmptyingContainmentsChart = $this->dashboardService->getNextEmptyingContainmentsChart();

        // Fetching data for tax revenue chart
        $taxRevenueChart = $this->dashboardService->getTaxRevenueChart();

        // Fetching data for water supply payment chart
        $waterSupplyPaymentChart = $this->dashboardService->getWaterSupplyPaymentChart();

        // Fetching data for proposed emptying date containments chart
        $proposedEmptyingDateContainmentsChart = $this->dashboardService->getproposedEmptyingDateContainmentsChart();

        // Fetching data for proposed emptied date containments by ward chart
        $proposedEmptiedDateContainmentsByWardChart = $this->dashboardService->getProposedEmptiedDateContainmentsByWard();

        // Fetching data for sewer length per ward chart
        $sewerLengthPerWardChart = $this->dashboardService->getSewerLengthPerWard();

        // Setting max and min date variables
        $maxDate = date('Y');
        $minDate = date('Y') - 4;


        /**
         * Key Performance Indicators
         */
        $noOfEmptying = Emptying::whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->distinct('application_id')->count('application_id');
        $noOfEmptyingReachedToTreatment = SludgeCollection::distinct('application_id')->whereRaw($whereRawSludgeServiceProvider)->whereNull('deleted_at')->count('application_id');

        $noOfFeedback = Feedback::whereNull('deleted_at')->whereRaw($whereRawFeedbackServiceProvider)->distinct('application_id')->count('application_id');
        $noOfPpeWear = $this->dashboardService->getTotalFeedbackPpeWear();


        $keyPerformanceData = [];
        $keyPerformanceIndicators = KeyPerformanceIndicator::all()->pluck("target", "indicator");
        foreach ($keyPerformanceIndicators as $indicator => $target) {
            switch ($indicator) {
                case 'Application Response Efficiency':
                    array_push($keyPerformanceData, [
                        "indicator" => $indicator,
                        "target" => $target,
                        "value" => $applicationCount == 0 ? "0" : ceil(($noOfEmptying / $applicationCount) * 100),
                        "icon" => '<i class="fa-solid fa-calendar-check"></i>'
                    ]);
                    break;
                case 'Safe Desludging':
                    array_push($keyPerformanceData, [
                        "indicator" => $indicator,
                        "target" => $target,
                        "value" => $noOfEmptying == 0 ? "0" : ceil(($noOfEmptyingReachedToTreatment / $noOfEmptying) * 100),
                        "icon" => '<i class="fa-solid fa-house-circle-check"></i>'
                    ]);
                    break;
                case 'Customer Satisfaction':
                    $satisfaction_data = DB::select("select count(*) AS total_count,sum(fsm_service_quality::int) as total_sum from fsm.feedbacks;");
                    $noOfFeedbackCategories = 3;
                    $noOfFeedbackRate = 5;
                    array_push($keyPerformanceData, [
                        "indicator" => $indicator,
                        "target" => $target,
                        "value" => $noOfFeedback == 0 ? "0" : (ceil($satisfaction_data[0]->total_sum / ($satisfaction_data[0]->total_count * $noOfFeedbackCategories)) / $noOfFeedbackRate) * 100,
                        "icon" => '<i class="fa-solid fa-users"></i>'
                    ]);
                    break;
                case 'OHS Compliance(PPE)':
                    array_push($keyPerformanceData, [
                        "indicator" => $indicator,
                        "target" => $target,
                        "value" => ($noOfFeedback) == 0 ? "0" : ceil(($noOfPpeWear / $noOfFeedback) * 100),
                        "icon" => '<i class="fa-solid fa-user-shield"></i>'
                    ]);
                    break;
            }
        }



        // Fetching data for road length per ward chart
        $roadLengthPerWardChart = $this->dashboardService->getRoadLengthPerWardChart();

        // Fetching data for waterborne cases chart
        $waterborneCasesChart = $this->dashboardService->getWaterborneCasesChart();
        // Fetching count for sanitation systems
        $sanitationSystems = $this->dashboardService->getBuildingSanitationSystem();
        $sanitationSystemsOthers = $this->dashboardService->getBuildingSanitationSystemOthers();
        return view('dashboard.indexAdmin', compact(
            'page_title',
            'buildingCount',
            'commercialBuildCount',
            'residentialBuildingCount',
            'mixedBuildCount',
            'governmentBuildingCount',
            'containmentCount',
            'emptyingServiceCount',
            'serviceProviderCount',
            'sludgeCollectionsCount',
            'uniqueContainCodeEmptiedCount',
            'desludgingVehicleCount',
            'applicationCount',
            'buildingsPerWardChart',
            'sanitationSystemsChart',
            'numberOfEmptyingbyMonthsChart',
            'emptyingRequestsbyStructureTypesChart',
            'containmentTypesPerWardChart',
            'emptyingServicePerWardsAssessmentFeedbackChart',
            'emptyingServiceByTypeYearChart',
            'containmentEmptiedByWardChart',
            'containTypeChart',
            'buildingUseChart',
            'nextEmptyingContainmentsChart',
            'sludgeCollectionByTreatmentPlantChart',
            'fsmSrvcQltyChart',
            'ppe',
            'taxRevenueChart',
            'waterSupplyPaymentChart',
            'proposedEmptyingDateContainmentsChart',
            'proposedEmptiedDateContainmentsByWardChart',
            'maxDate',
            'minDate',
            'containmentTypesByStructypesChart',
            'containmentTypesByBldgUsesChart',
            'monthlyAppRequestByoperators',
            'containmentTypesByBldgUsesResidentialsChart',
            'containmentTypesByLanduseChart',
            'costPaidByOwnerWithReceipt',
            'costPaidByContainmentOwnerPerwardChart',
            'sludgeCollectionEmptyingServices',
            'treatmentPlantCount',
            'hotspotsPerWardChart',
            'fsmCampaignsPerWardChart',
            'fsmCampaignsSupportedByChart',
            'sewerLengthPerWardChart',
            'keyPerformanceData',
            'industrialBuildingCount',
            'othersCount',
            'sumRoads',
            'sumDrains',
            'sumSewers',
            'sumWatersupply',
            'ctCount',
            'ptCount',
            'totalCtUser',
            'totalPtUser',
            'totalHotspot',
            'totalWaterborne',
            'roadLengthPerWardChart',
            'waterborneCasesChart',
            'minDate',
            'maxDate',
            'sanitationSystems',
            'sanitationSystemsOthers'
        ));
    }
}
