<?php
// Last Modified Date: 07-05-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Landing page Route
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return view('landingpage');
    }
});



Route::get('/files', 'FileController@index')->name('files.index');
Route::post('/files/upload', 'FileController@upload')->name('files.upload');

Route::get('/demo', 'KpiDashboardController@store');
// Route::get('/kpi-data', 'KpiDashboardController@data');




Route::group(['middleware' => ['guest']], function () {
    /**
     * Register Routes
     */
    Route::get('/register', 'Auth\RegisterController@show')->name('register.show');
    Route::post('/register', 'Auth\RegisterController@register')->name('register.perform');

    /**
     * Login Routes
     */
    Route::get('/login', 'Auth\LoginController@show')->name('login.show');
    Route::post('/login', 'Auth\LoginController@login')->name('login.perform');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/locale/{locale}', 'LocalController@setLang')->name('setLang');
});

/**
 * Building Routes
 */
Route::group([
    'name' => 'buildings',
    'prefix' => 'building-info',
    'namespace' => 'BuildingInfo'
], function () {
    // building structure routes
    Route::get('buildings/buildingdashboard', 'BuildingDashboardController@index')->name('buildingdashboard');

    Route::get('buildings/data', 'BuildingController@getData')->name('building.getData');
    Route::get('buildings/get-house-numbers', 'BuildingController@getHouseNumbers')->name('building.get-house-numbers-containments');
    Route::get('buildings/get-house-numbers-all', 'BuildingController@getHouseNumbersAll')->name('building.get-house-numbers-all');
    //replace this later when passed
    Route::get('buildings/get-containment-septic', 'BuildingController@getContainmentTypes')->name('building.get-containment-septic');
    Route::get('buildings/get-test', 'BuildingController@testFunc');


    Route::get('buildings/{id}/history', 'BuildingController@history');
    Route::get('buildings/export', 'BuildingController@export');
    Route::get('buildings/{id}/listContainments', 'BuildingController@listContainments')->name('buildings.listContainments');
    Route::resource('buildings', 'BuildingController');

    // building survey routes
    Route::get('building-surveys/data', 'BuildingSurveyController@getData')->name('building.getData');
    Route::get('building-surveys/{id}/approve', 'BuildingSurveyController@approve');

    Route::get('building-surveys/download/{filename}', 'BuildingSurveyController@download')->name('building.download');

    Route::resource('building-surveys', 'BuildingSurveyController');


    /**
     * Sanitation System Types
     */
    Route::get('sanitation-system-types/data', 'SanitationSystemTypeController@getData');
    Route::get('sanitation-system-types/export', 'SanitationSystemTypeController@export');
    Route::get('sanitation-system-types/{id}/history', 'SanitationSystemTypeController@history');
    Route::resource('sanitation-system-types', 'SanitationSystemTypeController');

    /**
     * Sanitation System Technology
     */
    Route::get('sanitation-system-technologies/data', 'SanitationSystemTechnologyController@getData');
    Route::get('sanitation-system-technologies/export', 'SanitationSystemTechnologyController@export');
    Route::get('sanitation-system-technologies/{id}/history', 'SanitationSystemTechnologyController@history');
    Route::resource('sanitation-system-technologies', 'SanitationSystemTechnologyController');



});

    /**
     * Layer Info
     */
    Route::group([
        'name' => 'low-income-communities',
        'prefix' => 'layer-info',
        'namespace' => 'LayerInfo'
    ], function () {
        /**
        * Low Income Community
        */
        Route::get('low-income-communities/data', 'LowIncomeCommunityController@getData');
        Route::get('low-income-communities/export', 'LowIncomeCommunityController@export');
        Route::get('low-income-communities/{id}/history', 'LowIncomeCommunityController@history');
        Route::resource('low-income-communities', 'LowIncomeCommunityController');
    });
/**
 * Users Routes
 */

Route::group([
    'name' => 'users',
    'prefix' => 'auth',
    'namespace' => 'Auth',
    'middleware' => 'auth'
], function () {
    Route::get('users/login-activities/{user_id}', 'UserController@getLoginActivity');
    Route::resource('users', 'UserController');
    Route::get('roles/list-roles', 'RoleController@getRoles');
    Route::resource("roles", "RoleController");
    Route::get('searchPermission/{id}', 'RoleController@searchPermission');
});

/**
 * SWM Routes
 */
Route::group([
    'prefix' => 'swm',
    'namespace' => 'Swm',
    'middleware' => 'auth'
], function () {
    Route::get('/', [App\Http\Controllers\Swm\SolidWasteManagementController::class, 'index'])->name('swm.home');
});

Route::group([
    'prefix' => 'swm/registrations',
    'namespace' => 'Swm\SwmRegistrations',
    'middleware' => 'auth'
], function () {
    Route::get('collection-point/get-data', 'CollectionPointController@getData')->name('collection-point.get-data');
    Route::get('landfill-site/get-data', 'LandfillSiteController@getData')->name('landfill-site.get-data');
    Route::get('transfer-station/get-data', 'TransferStationController@getData')->name('transfer-station.get-data');
    Route::get('collection-point/export', 'CollectionPointController@export')->name('collection-point.export');
    Route::get('landfill-site/export', 'LandfillSiteController@export')->name('landfill-site.export');
    Route::get('transfer-station/export', 'TransferStationController@export')->name('transfer-station.export');
    Route::resource('collection-point', 'CollectionPointController');
    Route::resource('landfill-site', 'LandfillSiteController');
    Route::resource('transfer-station', 'TransferStationController');
});

Route::group([
    'prefix' => 'swm/services',
    'namespace' => 'Swm\SwmServices',
    'middleware' => 'auth'
], function () {
    Route::get('transfer-log-in/get-data', 'TransferLogInController@getData')->name('transfer-log-in.get-data');
    Route::get('transfer-log-out/get-data', 'TransferLogOutController@getData')->name('transfer-log-out.get-data');
    Route::get('waste-recycle/get-data', 'WasteRecycleController@getData')->name('waste-recycle.get-data');
    Route::get('route/get-data', 'SwmRouteController@getData')->name('route.get-data');
    Route::get('service-area/get-data', 'SwmServiceAreaController@getData')->name('service-area.get-data');
    Route::get('service-provider/get-data', 'SwmServiceProviderController@getData')->name('service-provider.get-data');
    Route::get('transfer-log-in/export', 'TransferLogInController@export')->name('transfer-log-in.export');
    Route::get('transfer-log-out/export', 'TransferLogOutController@export')->name('transfer-log-out.export');
    Route::get('waste-recycle/export', 'WasteRecycleController@export')->name('waste-recycle.export');
    Route::get('route/export', 'WasteRecycleController@export')->name('route.export');
    Route::get('service-area/export', 'SwmServiceAreaController@export')->name('service-area.export');
    Route::get('service-provider/export', 'WasteRecycleController@export')->name('service-provider.export');
    Route::resource('transfer-log-in', 'TransferLogInController');
    Route::resource('transfer-log-out', 'TransferLogOutController');
    Route::resource('waste-recycle', 'WasteRecycleController');
    Route::resource('route', 'SwmRouteController');
    Route::resource('service-area', 'SwmServiceAreaController');
    Route::resource('service-provider', 'SwmServiceProviderController');
});

// Tax Payment routes
Route::group([
    'name' => 'tax-payment',
    // 'prefix' => 'tax-info-mgmt',
    'namespace' => 'TaxPaymentInfo',
    'middleware' => 'auth'
], function () {
    Route::get('tax-payment/data', 'TaxPaymentController@getData')->name('tax-payment.getData');
    Route::get('tax-payment/export', 'TaxPaymentController@export')->name('tax-payment.export');
    Route::resource('tax-payment', 'TaxPaymentController');
});

// Water Supply Payment routes
Route::group([
    'name' => 'watersupply-payment',
    // 'prefix' => 'watersupply-info-mgmt',
    'namespace' => 'WaterSupplyInfo',
    'middleware' => 'auth'
], function () {
    Route::get('watersupply-payment/data', 'WaterSupplyController@getData')->name('watersupply-payment.getData');
    Route::get('watersupply-payment/export', 'WaterSupplyController@export')->name('watersupply-payment.export');
    Route::resource('watersupply-payment', 'WaterSupplyController');
});


// SWM routes
Route::group([
    'name' => 'swm-payment',
    'namespace' => 'SwmPaymentInfo',
    'middleware' => 'auth'
], function () {
    Route::get('swm-payment/data', 'SwmPaymentController@getData')->name('swm-payment.getData');
    Route::get('swm-payment/export', 'SwmPaymentController@export')->name('swm-payment.export');
    Route::resource('swm-payment', 'SwmPaymentController');
});

Route::group([
    'name' => 'sewerconnection',
    'prefix' => 'sewerconnection',
    'namespace' => 'SewerConnection',
    'middleware' => 'auth'
], function () {
    Route::get('sewerconnection/data', 'SewerConnectionController@getData');
    Route::get('sewerconnection/data/{id}', 'SewerConnectionController@approvesewer');
    Route::get('sewerconnection/datageom/{id}', 'SewerConnectionController@geombin');
    Route::get('sewerconnection/geomsewer/{id}', 'SewerConnectionController@geomsewer');
    Route::resource('sewerconnection', 'SewerConnectionController');
});

/**
 * Utility Info Routes
 */

Route::group([
    'name' => 'utilityinfo',
    'prefix' => 'utilityinfo',
    'namespace' => 'UtilityInfo',
    'middleware' => 'auth'
], function () {
    Route::get('utilitydashboard', 'UtilityDashboardController@index')->name('utilitydashboard');
    Route::get('roadlines/export', 'RoadlineController@export');
    Route::get('roadlines/data', 'RoadlineController@getData');
    Route::get('roadlines/get-road-names', 'RoadlineController@getRoadNames')->name('roadlines.get-road-names');
    Route::get('roadlines/{id}/history', 'RoadlineController@history');
    Route::post('roadlines/add-road', 'RoadlineController@store');
    Route::post('roadlines/update-road-geom', 'RoadlineController@updateRoadGeom');
    Route::resource('roadlines', 'RoadlineController');


    // Route::get('sewerconnection/{id}/approve', 'SewerConnectionController@approve');
    Route::get('drains/get-drain-names','DrainController@getDrainNames')->name('drains.get-drain-names');

    Route::get('drains/export', 'DrainController@export');
    Route::get('drains/data', 'DrainController@getData');
    Route::get('drains/{id}/history', 'DrainController@history');
    Route::resource('drains', 'DrainController');

    Route::get('sewerlines/export', 'SewerLineController@export');
    Route::get('sewerlines/data', 'SewerLineController@getData');
    Route::get('sewerlines/get-sewer-names', 'SewerLineController@getSewerNames')->name('sewerlines.get-sewer-names');
    Route::get('sewerlines/{id}/history', 'SewerLineController@history');
    Route::resource('sewerlines', 'SewerLineController');

    Route::get('watersupplys/export', 'WaterSupplysController@export');
    Route::get('watersupplys/data', 'WaterSupplysController@getData');
    Route::get('watersupplys/get-watersupply-codes', 'WaterSupplysController@getWaterSupplyCode')->name('watersupply.get-watersupply-code');

    Route::get('watersupply/{id}/history', 'WaterSupplysController@history');
    Route::resource('watersupplys', 'WaterSupplysController');
});


/**
 * FSM Info Routes
 */

Route::group([
    'name' => 'fsm',
    'prefix' => 'fsm',
    'middleware' => 'auth',
    'namespace' => 'Fsm'
], function () {

    Route::get('/cwis-setting/data', 'CwisSettingController@getData');
    Route::resource('/cwis-setting', 'CwisSettingController');

    Route::get('/treatment-plant-performance-test/data', 'TreatmentplantPerformanceTestController@getData');
    Route::resource('/treatment-plant-performance-test', 'TreatmentplantPerformanceTestController');


    Route::get('fsmdashboard', 'FsmDashboardController@index')->name('fsmdashboard');
    Route::get('/data', 'KpiDashboardController@data');
    Route::get('/card', 'KpiDashboardController@card');

    Route::get('generate-report/{year?}/{serviceprovider?}', 'KpiDashboardController@generateReport');
    Route::resource('/kpi-dashboard', 'KpiDashboardController');

    /**
     * Kpi Target Routes
     */
    Route::get('kpi-targets/data', 'KpiTargetController@getData');
    Route::get('kpi-targets/export', 'KpiTargetController@export');
    Route::get('kpi-targets/{id}/history', 'KpiTargetController@history');
    Route::resource('kpi-targets', 'KpiTargetController');
    /**
     * Service Providers Routes
     */
    Route::get('service-providers/data', 'ServiceProviderController@getData');
    Route::get('service-providers/export', 'ServiceProviderController@export');
    Route::get('service-providers/{id}/history', 'ServiceProviderController@history');
    Route::resource('service-providers', 'ServiceProviderController');
    /**
     * Employee Info Routes
     */
    Route::get('employee-infos/data', 'EmployeeInfoController@getData');
    Route::get('employee-infos/export', 'EmployeeInfoController@export');
    Route::get('employee-infos/{id}/history', 'EmployeeInfoController@history');
    Route::resource('employee-infos', 'EmployeeInfoController');

    /**
     * Help Desks Routes
     */
    Route::get('help-desks/data', 'HelpDeskController@getData');
    Route::get('help-desks/export', 'HelpDeskController@export');
    Route::get('help-desks/{id}/history', 'HelpDeskController@history');
    Route::resource('help-desks', 'HelpDeskController');

    /**
     * Treatment Plants Routes
     */
    Route::get('treatment-plants/data', 'TreatmentPlantController@getData');
    Route::get('treatment-plants/export', 'TreatmentPlantController@export');
    Route::get('treatment-plants/{id}/history', 'TreatmentPlantController@history');
    Route::resource('treatment-plants', 'TreatmentPlantController');

    /**
     * CWIS Treatment Plant Effectiveness Routes
     */
    // Route::get('treatment-plant-effectiveness/data', 'TreatmentPlantEffectivenessController@getData');
    // Route::resource('treatment-plant-effectiveness','TreatmentPlantEffectivenessController');

    /**
     *
     * CWIS Treatment Plant Test Routes
     */
    Route::get('treatment-plant-test/data', 'TreatmentPlantTestController@getData');
    Route::get('treatment-plant-test/export', 'TreatmentPlantTestController@export');

    Route::get('treatment-plant-test/{id}/history', 'TreatmentPlantTestController@history');
    Route::resource('treatment-plant-test', 'TreatmentPlantTestController');


    /**
     *

     * Vacutug Types Routes
     */
    Route::get('desludging-vehicles/data', 'VacutugTypeController@getData');
    Route::get('desludging-vehicles/export', 'VacutugTypeController@export');
    Route::get('desludging-vehicles/{id}/history', 'VacutugTypeController@history');
    Route::resource('desludging-vehicles', 'VacutugTypeController');


    /**
     * Containment Routes
     *
     */
    Route::get('containments/data', 'ContainmentController@getData');
    Route::get('containments/get-id', 'ContainmentController@getContainmentID')->name('containment.get-id');

    Route::get('containments/{id}/containmentData', 'ContainmentController@getContainment');
    Route::get('containments/{id}/listBuildings', 'ContainmentController@listBuildings');
    Route::delete('containments/{id}/buildings/{buidlingId}', 'ContainmentController@deleteBuilding');

    Route::get('containments/{id}/create', 'ContainmentController@createContainment');
    Route::post('containments/{id}/store', 'ContainmentController@storeContainment');

    Route::get('containments/export', 'ContainmentController@export');
    Route::get('containments/{id}/history', 'ContainmentController@history');
    Route::get('containments/{id}/type-change-history', 'ContainmentController@typeChangeHistory');
    Route::resource('containments', 'ContainmentController');


    /**
     * CTPT Info Routes
     */
    Route::get('ctpt/data', 'CtptController@getData');
    Route::get('ctpt/export', 'CtptController@export');
    Route::get('ctpt/{id}/buildings', 'CtptController@listBuildings');
    Route::get('ctpt/{id}/buildings/add', 'CtptController@addBuildings');
    Route::patch('ctpt/{id}/buildings', 'CtptController@saveBuildings');
    Route::get('ctpt/buildings/data', 'CtptController@getAllBuildingData');
    Route::get('calculate', 'CtptController@calculate');
    Route::get('ctpt/{id}/history', 'CtptController@history');


    Route::resource('ctpt', 'CtptController');

    /**
     * CTPT Users Info Routes
     */
    Route::get('ctpt-users/data', 'CtptUserController@getData');
    Route::get('ctpt-users/export', 'CtptUserController@export');
    Route::get('ctpt-users/{id}/history', 'CtptUserController@history');

    Route::resource('ctpt-users', 'CtptUserController');

    /**
     *Hotspot Indentification Routes
     */
    Route::get('hotspots/data', 'HotspotController@getData');
    Route::get('hotspots/viewhotspot/{id}', 'HotspotController@viewhotspot');

    Route::get('hotspots/export', 'HotspotController@export');
    Route::get('hotspots/{id}/history', 'HotspotController@history');


    Route::resource('hotspots', 'HotspotController');

    /**
     * Application Routes
     *
     */
    Route::get('application/getData', 'ApplicationController@getData')->name('application.get-data');
    Route::get('application/export', 'ApplicationController@export')->name('application.export');
    Route::get('application/{id}/history', 'ApplicationController@history')->name('application.history');
    Route::get('application/getBuildingDetails', 'ApplicationController@buildingDetails')->name('application.get-building-details');
    Route::get('application/monthly/pdf/{year}/{month}', 'ApplicationController@monthlyApplicationsPdf');
    Route::get('application/{id}/report', 'ApplicationController@applicationReport')->name('application.report');


    Route::resource('application', 'ApplicationController');

    /**
     * Emptying Routes
     *
     */
    Route::get('emptying/create/{id}', 'EmptyingController@create')->name('emptying.create-id');
    Route::get('emptying/{id}/history', 'EmptyingController@history')->name('emptying.history');
    Route::get('emptying/export', 'EmptyingController@export')->name('emptying.export');
    Route::get('emptying/getData', 'EmptyingController@getData')->name('emptying.get-data');
    Route::resource('emptying', 'EmptyingController');

    /**
     * Feedback Routes
     *
     */
    Route::get('feedback/getData', 'FeedbackController@getData')->name('feedback.get-data');
    Route::get('feedback/export', 'FeedbackController@export');
    Route::get('feedback/editFeedback/{id}', 'FeedbackController@createFeedback')->name('feedback.create-Feedback');

    Route::resource('feedback', 'FeedbackController');

    /**
     * Sludge Collection Routes
     *
     */
    Route::get('sludge-collection/create/{id}', 'SludgeCollectionController@create')->name('sludge-collection.create-id');
    Route::get('sludge-collection/getData', 'SludgeCollectionController@getData')->name('sludge-collection.get-data');
    Route::get('sludge-collection/export', 'SludgeCollectionController@export');
    Route::get('sludge-collection/{id}/history', 'SludgeCollectionController@history');


    Route::resource('sludge-collection', 'SludgeCollectionController');


});

/**
 * FSM Info Routes
 */

Route::group([
    'name' => 'cwis',
    'prefix' => 'cwis',
    'namespace' => 'Cwis',
    'middleware' => 'auth'
], function () {

    /**
     * CWIS JMP Routes
     */
    Route::get('cwis-df-jmp/export-jmp-csv', 'CwisJmpController@exportJmpCsv');
    Route::get('cwis-df-jmp/newsurvey', 'CwisJmpController@createIndex');
    Route::post('cwis-df-jmp/newsurvey', 'CwisJmpController@createStore');
    Route::resource('cwis-df-jmp', 'CwisJmpController');

    /**
     * CWIS MNE Routes
     */
    Route::get('cwis-df-mne/newsurvey', 'CwisMneController@createIndex');
    Route::post('cwis-df-mne/newsurvey', 'CwisMneController@createStore');
    Route::get('cwis-df-mne/export-mne-csv', 'CwisMneController@exportMneCsv');
    Route::get('cwis/getall/{year?}', 'CwisNewDashboardController@getall');
    Route::get('cwis/export/{year?}', 'CwisNewDashboardController@exportCSV');

    Route::post('/chart/download-pdf', [ChartController::class, 'downloadPDF']);
    /**
     * CWIS General Information Routes
     */
    Route::get('general-info/data', 'CwisGeneralInfoController@getData');
    Route::get('general-info/export', 'CwisGeneralInfoController@export');
    Route::resource('general-info', 'CwisGeneralInfoController');

    /**
     * CWIS Mof User Routes
     */
    Route::get('mof-users/data', 'CwisMofUserController@getData');
    Route::get('mof-users/export', 'CwisMofUserController@export');
    Route::resource('mof-users', 'CwisMofUserController');

    /**
     * CWIS Criteria Routes
     */
    Route::get('criteria/data', 'CwisCriteriaController@getData');
    Route::resource('criteria', 'CwisCriteriaController');

    /**
     * CWIS MNE Information Routes
     */

    Route::get('getall/{year?}', 'CwisDashboardController@getAll');



    Route::get('cwis-mne-dashboard', 'Cwis\CwisDashboardController@indexMe');


    Route::get('cwis-jmp-dashboard', 'CwisDashboardController@indexJmp');


    //THis is done to debug routing problem//
    Route::get('export-csv/{year}', 'CwisNewDashboardController@exportCsv');
    Route::get('view-to-image/{year}', 'CwisNewDashboardController@viewToImage');
    Route::get('pdf-cwis/{year}', 'CwisNewDashboardController@generatePdf');


    Route::resource('cwis/test', 'CwisNewDashboardController');
    Route::resource('cwis/cwis-df-mne', 'CwisMneController');
    /**
     * Report Generation Routes
     */
    Route::post('cwis-mne-dashboard/report', 'ReportGenerationController@mneReport');
});
/**
 * Maps Routes
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('maps', 'MapsController@index');
    Route::get('maps/building-containment', 'MapsController@getBuildingToContainment');
    Route::get('maps/containment-buildings', 'MapsController@getContainmentToBuildings');
    Route::get('maps/getassociated-mainbuilding', 'MapsController@getAssociatedToMainbuilding');
    Route::get('maps/filterward/ward-center-coordinates', 'MapsController@getWardCenterCoordinates');
    Route::get('maps/clipward/ward-center-coordinates', 'MapsController@getClipWardCenterCoordinates');
    Route::get('maps/export-buffer-polygon', 'MapsController@getBufferPolygonReportCSV');
    Route::get('maps/export-buffer-polygon-waterbody', 'MapsController@getWaterBodyReportCsv');
    Route::get('maps/export-ward-buildings', 'MapsController@getWardBuildingsReportCsv');
    Route::get('maps/export-road-buildings', 'MapsController@getRoadBuildingsReportCsv');
    Route::get('maps/export-point-buildings', 'MapsController@getPointBuildingsReportCsv');
    Route::get('maps/export-buildings-road', 'MapsController@getBuildingsRoadReportCsv');
    Route::get('maps/export-drain-potential-buildings', 'MapsController@getDrainPotentialReportCSV');
    Route::get('maps/export-buildings-taxzone/{geom}', 'MapsController@getBuildingsTaxzoneReportCSV');
    Route::get('maps/extent/{layer}/{atrribute}/{value}', 'MapsController@getExtent');
    Route::get('maps/containment-buildings/{field}/{val}', 'MapsController@getContainmentBuildings');
    Route::get('maps/containment-road/{field}/{val}', 'MapsController@getContainmentRoad');
    Route::get('maps/building-road/{field}/{val}', 'MapsController@getBuildingRoad');
    Route::get('maps/nearest-road/{long}/{lat}', 'MapsController@getNearestRoad');
    Route::get('maps/proposed-emptying-containments/{start_date}/{end_date}', 'MapsController@getProposedEmptyingContainments');
    Route::get('getContainmentsByRadius/{long}/{lat}', 'MapsController@getContainmentsByRadius');
    Route::get('maps/due-buildings', 'MapsController@getDueBuildings');
    Route::post('maps/due-buildings-ward-taxzone', 'MapsController@getDueBuildingsWardTaxzone');
    Route::get('maps/application-containments', 'MapsController@getApplicationContainments');
    Route::post('maps/application-containments-year-month', 'MapsController@getApplicationContainmentsYearMonth');
    Route::get('maps/application-not-tp-on-date/{start_date}', 'MapsController@getApplicationNotTPOnDate');
    Route::get('maps/application-on-date/{start_date}', 'MapsController@getApplicationOnDate');
    Route::get('maps/application-not-tp', 'MapsController@getApplicationNotTP');
    Route::post('maps/application-not-tp-containments-year-month', 'MapsController@getApplicationNotTPContainmentsYearMonth');
    Route::post('maps/feedback-report', 'MapsController@getFeedbackReport');
    Route::post('maps/drain-buildings', 'MapsController@getDrainBuildings');
    Route::post('maps/buildings-to-road', 'MapsController@getBuildingsToRoad');
    Route::post('maps/drain-potential-buildings', 'MapsController@getDrainPotentialBuildings');
    Route::post('maps/water-bodies-buildings', 'MapsController@getWaterBodiesBuildings');
    Route::get('maps/point-buffer-buildings', 'MapsController@getPointBufferBuildings');
    Route::post('maps/road-buildings', 'MapsController@getRoadBuildings');
    Route::get('maps/search-by-keywords', 'MapsController@searchByKeywords');
    Route::get('maps/search-building/{field}/{val}', 'MapsController@searchBuilding');
    Route::get('maps/search-auto-complete/{layer}/{keywords}', 'MapsController@searchAutoComplete');
    Route::post('maps/buffer-polygon-buildings', 'MapsController@getBufferPolygonBuildings');
    Route::post('maps/ward-buildings', 'MapsController@getWardBuildings');
    Route::post('maps/area-population-Polygon-sum', 'MapsController@getAreaPopulationPolygonSum');
    Route::post('maps/road-inaccessible-summary-info', 'MapsController@roadInaccesibleISummaryInfo');
    Route::get('export-shp-kml', 'ExportShpKmlController@index');
    Route::get('export-shp-kml/export-shape', 'ExportShpKmlController@exportShape');
    Route::post('maps/road-inaccessible-buildings', 'MapsController@roadInaccessibleBuildings');
    Route::get('maps/road-inaccessible-buildings-reports', 'MapsController@getPolygonRoadInaccessibleReport');
    Route::get('maps/export-wfs-request', 'MapsController@exportWfsRequest');
    Route::post('maps/waterbody-inaccessible-buildings', 'MapsController@waterbodyInaccessibleBuildings');
    Route::get('maps/waterbody-inaccessible-buildings-reports', 'MapsController@getPolygonWaterbodyInaccessibleReport');
    // Route::post('maps/road-inaccessible-buildings', 'MapsController@roadInaccessibleBuildings');
    Route::get('maps/buildings-toilet-network', 'MapsController@getBuildingsToiletNetwork');
});


/**
 * Utility Info Routes
 */

Route::group([
    'name' => 'publichealth',
    'prefix' => 'publichealth',
    'namespace' => 'PublicHealth',
    'middleware' => 'auth'
], function () {

    Route::get('dengue-cases/data', 'DengueCaseController@getData');
    Route::resource('dengue-cases', 'DengueCaseController');


    Route::get('waterborne/data', 'YearlyWaterborneController@getData');
    Route::get('waterborne/export', 'YearlyWaterborneController@export');
    Route::get('waterborne/{id}/history', 'YearlyWaterborneController@history');
    Route::resource('waterborne', 'YearlyWaterborneController');

    /**
     * Water Samples Routes
     *
     */
    Route::get('water-samples/data', 'WaterSamplesController@getData')->name('water-samples.get-data');
    Route::get('water-samples/export', 'WaterSamplesController@export');
    Route::get('water-samples/{id}/history', 'WaterSamplesController@history');

    Route::resource('water-samples', 'WaterSamplesController');
});
Route::group(['middleware' => ['auth']], function () {
    /**
     * Logout Routes
     */
    Route::post('/logout', 'Auth\LogoutController@perform')->name('logout.perform');
});
