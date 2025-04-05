<?php
// Last Modified Date: 11-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)  
namespace App\Http\Controllers\fsm;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Fsm\KeyPerformanceIndicator;
use Illuminate\Support\Facades\Auth;
use App\Models\BuildingInfo\Building;
use App\Models\Fsm\Containment;
use App\Models\Fsm\Emptying;
use App\Models\Fsm\ServiceProvider;
use App\Models\Fsm\Application;
use App\Models\Fsm\Feedback;
use App\Models\Fsm\TreatmentPlant;
use App\Models\Fsm\SludgeCollection;
use Illuminate\Support\Facades\DB;
use App\Services\Fsm\FsmDashboardService;
use App\Services\DashboardService;
use App\Models\Fsm\VacutugType;


class FsmDashboardController extends Controller
{
    protected FsmDashboardService $fsmdashboardService;
    protected DashboardService $dashboardService;

    public function __construct(FsmDashboardService $fsmdashboardService,DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->fsmdashboardService = $fsmdashboardService;
        $this->dashboardService = $dashboardService;
    }
    public function index(){
        {

            $page_title = 'FSM Dashboard';
            //buildings
            $buildingCount = Building::whereNull('deleted_at')->count();
            $commercialBuildCount = (Building::where('functional_use_id', 2)->whereNull('deleted_at')->count());
            $residentialBuildingCount = Building::where('functional_use_id', 1)->whereNull('deleted_at')->count();
            $mixedBuildCount = Building::where('functional_use_id', 4)->whereNull('deleted_at')->count();
            $governmentBuildingCount = Building::where('functional_use_id', 5)->whereNull('deleted_at')->count();
            $industrialBuildingCount = Building::where('functional_use_id', 7)->whereNull('deleted_at')->count();
            $othersCount = $buildingCount - ($commercialBuildCount + $residentialBuildingCount + $mixedBuildCount + $governmentBuildingCount + $industrialBuildingCount);
            //sanitation systems
            $containmentCount = Containment::whereNull('deleted_at')->count();
            if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk'))
                {
                    $whereRawEmptyingsServiceProvider = "emptyings.service_provider_id = " .Auth::user()->service_provider_id;
                    $whereRawApplicationsServiceProvider = "applications.service_provider_id = " .Auth::user()->service_provider_id;
                    $whereRawSludgeCollectionServiceProvider = "sludge_collections.service_provider_id = " .Auth::user()->service_provider_id;
                    $whereRawFeedbackServiceProvider = "feedbacks.service_provider_id = " .Auth::user()->service_provider_id;
                    $whereRawDesludgingVehicleServiceProvider= "service_provider_id = " .Auth::user()->service_provider_id;
                    $whereUserId = "users.id = " . Auth::user()->id;
                    $whereRawSludgeServiceProvider = "sludge_collections.service_provider_id = " .Auth::user()->service_provider_id;


                }
                else{

                    $whereRawEmptyingsServiceProvider = "1 = 1";
                    $whereRawApplicationsServiceProvider = "1 = 1";
                    $whereRawSludgeCollectionServiceProvider = "1 = 1";
                    $whereRawFeedbackServiceProvider = "1 = 1";
                    $whereRawDesludgingVehicleServiceProvider = "1 = 1";
                    $whereUserId = "1 = 1";
                    $whereRawSludgeServiceProvider = "1 = 1";

                }

                if (Auth::user()->hasRole('Treatment Plant'))
                {
                    $whereRawTreatmentPlant = "treatment_plant_id = " .Auth::user()->treatment_plant_id;
                }
                else {
                    $whereRawTreatmentPlant = "1 = 1";
                }
            if(request()->year){

            $monthlyAppRequestByoperators = $this->fsmdashboardService->getMonthlyAppRequestByoperators(request()->year);

                //fsm services
                $uniqueContainCodeEmptiedCount = Application::whereYear( 'created_at', '=' , request()->year )->where('emptying_status', true)->whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->distinct('containment_id')->count('containment_id');
                //$uniqueContainCodeEmptiedCount = Emptying::whereYear( 'created_at', '=' , request()->year )->whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->distinct('application_id')->count('application_id');
                $emptyingServiceCount = Application::whereYear( 'created_at', '=' , request()->year )->where('emptying_status', true)->whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->count('id');
                //$serviceProviderCount = ServiceProvider::leftJoin('auth.users', 'service_providers.id','=', 'users.service_provider_id')->where('service_providers.status', 1)->whereRaw($whereUserId)->whereYear( 'service_providers.created_at', '<=' , request()->year )->whereNull('service_providers.deleted_at')->count();
                
                // Calculate the count of active service providers
                // Fetching service providers and their IDs
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
                
                $applicationCount = Application::whereYear( 'created_at', '=' , request()->year )->whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->count();
                $costPaidByContainmentOwnerPerwardChart = $this->fsmdashboardService->getCostPaidByContainmentOwnerPerward(request()->year);
                $sludgeCollectionEmptyingServices = Emptying::whereYear( 'created_at', '=' , request()->year )->whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->sum('volume_of_sludge');
                $sludgeCollectionsCount = SludgeCollection::whereYear( 'date', '=' , request()->year )->whereNull('deleted_at')->whereRaw($whereRawSludgeCollectionServiceProvider)->sum('volume_of_sludge');
                $treatmentPlantCount = TreatmentPlant::leftJoin('auth.users', 'treatment_plants.id','=', 'users.treatment_plant_id')->where('treatment_plants.status', 1)->whereYear( 'treatment_plants.created_at', '<=' , request()->year )->whereNull('treatment_plants.deleted_at')->distinct('treatment_plants.id')->whereRaw($whereUserId)->distinct('treatment_plants.id')->count('treatment_plants.id');
                $costPaidByOwnerWithReceipt = Emptying::whereYear( 'created_at', '=' , request()->year )->whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->sum('total_cost');
                $emptyingServicePerWardsAssessmentFeedbackChart = $this->fsmdashboardService->getEmptyingServicePerWardsAssessmentFeedback(request()->year);
                $desludgingVehicleCount = VacutugType::whereYear( 'created_at', '<=', request()->year )->where('status', 1)->whereNull('deleted_at')->whereRaw($whereRawDesludgingVehicleServiceProvider)->count();

                // feedback charts

                $fsmSrvcQltyChart = $this->fsmdashboardService->getFsmSrvcQltyChart(request()->year);

                $behaviorOfTheServiceProviderChart = $this->fsmdashboardService->getBehaviorOfTheServiceProviderChart(request()->year);
                $ppe = $this->fsmdashboardService->getppeChart(request()->year);

                $sludgeCollectionByTreatmentPlantChart = $this->fsmdashboardService->getSludgeCollectionByTreatmentPlantChart(request()->year);
                $hotspotsPerWardChart = $this->fsmdashboardService->getHotspotsPerWard(request()->year);

                $fsmCampaignsPerWardChart = $this->fsmdashboardService->getFsmCampaignsPerWard(request()->year);
                $fsmCampaignsSupportedByChart = $this->fsmdashboardService->getFsmCampaignsSupportedBy(request()->year);
                $numberOfEmptyingbyMonthsChart = $this->fsmdashboardService->getNumberOfEmptyingbyMonths(request()->year);
            }
                else {

                $numberOfEmptyingbyMonthsChart = $this->fsmdashboardService->getNumberOfEmptyingbyMonths(null);
                $uniqueContainCodeEmptiedCount = Application::Where('emptying_status', true)->whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->distinct('containment_id')->count('containment_id');
                $emptyingServiceCount = Application::where('emptying_status', true)->whereRaw($whereRawApplicationsServiceProvider)->whereNull('deleted_at')->count('id');
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
                $applicationCount = Application::whereNull('deleted_at')->whereRaw($whereRawApplicationsServiceProvider)->count();
                $costPaidByContainmentOwnerPerwardChart = $this->fsmdashboardService->getCostPaidByContainmentOwnerPerward(null);
                $sludgeCollectionEmptyingServices = Emptying::whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->sum('volume_of_sludge');
                $sludgeCollectionsCount = SludgeCollection::whereNull('deleted_at')->whereRaw($whereRawSludgeCollectionServiceProvider)->sum('volume_of_sludge');
                $treatmentPlantCount = TreatmentPlant::leftJoin('auth.users', 'treatment_plants.id','=', 'users.treatment_plant_id')->where('treatment_plants.status', 1)->whereNull('treatment_plants.deleted_at')->whereRaw($whereUserId)->distinct('treatment_plants.id')->count('treatment_plants.id');

                $desludgingVehicleCount = VacutugType::where('status', 1)->whereNull('deleted_at')->whereRaw($whereRawDesludgingVehicleServiceProvider)->count();

                $costPaidByOwnerWithReceipt = Emptying::whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->sum('total_cost');
                $emptyingServicePerWardsAssessmentFeedbackChart = $this->fsmdashboardService->getEmptyingServicePerWardsAssessmentFeedback(null);
                $monthlyAppRequestByoperators = $this->fsmdashboardService->getMonthlyAppRequestByoperators(null);
                $fsmSrvcQltyChart = $this->fsmdashboardService->getFsmSrvcQltyChart(null);

                $behaviorOfTheServiceProviderChart = $this->fsmdashboardService->getBehaviorOfTheServiceProviderChart(null);
                $ppe = $this->fsmdashboardService->getppeChart(null);

                $hotspotsPerWardChart = $this->fsmdashboardService->getHotspotsPerWard(null);
                $sludgeCollectionByTreatmentPlantChart = $this->fsmdashboardService->getSludgeCollectionByTreatmentPlantChart(null);
                $fsmCampaignsPerWardChart = $this->fsmdashboardService->getFsmCampaignsPerWard(null);
                $fsmCampaignsSupportedByChart = $this->fsmdashboardService->getFsmCampaignsSupportedBy(null);

                }

            $buildingsPerWardChart = $this->fsmdashboardService->getBuildingsPerWardChart();
            $emptyingRequestsbyStructureTypesChart = $this->fsmdashboardService->getEmptyingRequestsPerStructureTypeChart();

            $containmentTypesPerWardChart = $this->dashboardService->getContainmentTypesPerWard();

            $containmentTypesByStructypesChart = $this->dashboardService->getContainmentTypesByStructypes();
            $containmentTypesByBldgUsesChart = $this->dashboardService->getContainmentTypesByBldgUse();
            $containmentTypesByBldgUsesResidentialsChart = $this->dashboardService->getContainmentTypesByBldgUseResidentials();
            $containmentTypesByLanduseChart = $this->dashboardService->getContainmentTypesByLanduse();
            $emptyingServiceByTypeYearChart = $this->dashboardService->getEmptyingServiceByTypeYear();
            $containmentEmptiedByWardChart = $this->fsmdashboardService->getcontainmentEmptiedByWard();
            $containTypeChart = $this->dashboardService->getContainTypeChart();
            $buildingUseChart = $this->fsmdashboardService->getBuildingUseChart();
            $nextEmptyingContainmentsChart = $this->fsmdashboardService->getNextEmptyingContainmentsChart();
            $taxRevenueChart = $this->fsmdashboardService->getTaxRevenueChart();
            $waterSupplyPaymentChart = $this->fsmdashboardService->getWaterSupplyPaymentChart();
            $proposedEmptyingDateContainmentsChart = $this->fsmdashboardService->getproposedEmptyingDateContainmentsChart();
            $proposedEmptiedDateContainmentsByWardChart = $this->fsmdashboardService->getProposedEmptiedDateContainmentsByWard();
            $sewerLengthPerWardChart = $this->fsmdashboardService->getSewerLengthPerWard();

            $maxDate = date('Y') ;
            $minDate = date('Y') - 4;


            /**
             * Key Performance Indicators
             */
            $noOfEmptying = Emptying::whereNull('deleted_at')->whereRaw($whereRawEmptyingsServiceProvider)->distinct('application_id')->count('application_id');
            $noOfEmptyingReachedToTreatment = SludgeCollection::distinct('application_id')->whereRaw($whereRawSludgeServiceProvider)->whereNull('deleted_at')->count('application_id');

            $noOfFeedback = Feedback::whereNull('deleted_at')->whereRaw($whereRawFeedbackServiceProvider)->distinct('application_id')->count('application_id');
            $noOfPpeWear = $this->fsmdashboardService->getTotalFeedbackPpeWear();
            $keyPerformanceData = [];
            $keyPerformanceIndicators = KeyPerformanceIndicator::all()->pluck("target","indicator");
            foreach ($keyPerformanceIndicators as $indicator=>$target){
                switch ($indicator){
                    case 'Application Response Efficiency':
                        array_push($keyPerformanceData,[
                            "indicator" => $indicator,
                            "target" => $target,
                            "value" => $applicationCount==0?"0":ceil(($noOfEmptying/$applicationCount)*100),
                            "icon" => '<i class="fa-solid fa-calendar-check"></i>'
                        ]);
                        break;
                    case 'Safe Desludging':
                        array_push($keyPerformanceData,[
                            "indicator" => $indicator,
                            "target" => $target,
                            "value" => $noOfEmptying==0?"0":ceil(($noOfEmptyingReachedToTreatment/$noOfEmptying)*100),
                            "icon" => '<i class="fa-solid fa-house-circle-check"></i>'
                        ]);
                        break;
                    case 'Customer Satisfaction':
                        $satisfaction_data = DB::select("select count(*) AS total_count,sum(fsm_service_quality::int) as total_sum from fsm.feedbacks;");
                        $noOfFeedbackCategories = 3;
                        $noOfFeedbackRate = 5;
                        array_push($keyPerformanceData,[
                            "indicator" => $indicator,
                            "target" => $target,
                            "value" => $noOfFeedback==0?"0":(ceil($satisfaction_data[0]->total_sum/($satisfaction_data[0]->total_count * $noOfFeedbackCategories))/$noOfFeedbackRate) *100,
                            "icon" => '<i class="fa-solid fa-users"></i>'
                        ]);
                        break;
                    case 'OHS Compliance(PPE)':
                        array_push($keyPerformanceData,[
                            "indicator" => $indicator,
                            "target" => $target,
                            "value" => ($noOfFeedback)==0?"0":ceil(($noOfPpeWear/$noOfFeedback)*100),
                            "icon" => '<i class="fa-solid fa-user-shield"></i>'
                        ]);
                        break;
                }
            }

            return view('dashboard.fsmDashboard', compact(
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
                'applicationCount',
                'desludgingVehicleCount',
                'buildingsPerWardChart',
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
                'behaviorOfTheServiceProviderChart',
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
                ));
        }

    }
}
