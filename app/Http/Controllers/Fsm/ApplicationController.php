<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fsm\ApplicationRequest;
use App\Models\BuildingInfo\Building;
use App\Models\Fsm\Application;
use App\Services\Fsm\ApplicationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Venturecraft\Revisionable\Revision;
use Yajra\DataTables\Facades\DataTables;
use DB;
class ApplicationController extends Controller
{
    protected ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Display a list of applications.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Application')?$this->applicationService->getCreateRoute():null;
        $createBtnTitle = 'Add Application';
        $exportBtnLink = Auth::user()->can('Export Applications')?$this->applicationService->getExportRoute():null;
        $reportBtnLink = Auth::user()->can('Generate Application Report')?$this->applicationService->getReportRoute():null;
        $filterFormFields = $this->applicationService->getFilterFormFields();
        $application_months = DB::select("select distinct extract(month from application_date) as date1 from fsm.applications where deleted_at is null order by date1 asc");
        $application_years = DB::select("select distinct extract(year from application_date) as date1 from fsm.applications where deleted_at is null order by date1 desc");

        return view('fsm.applications.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink','reportBtnLink', 'application_months', 'application_years'));
    }

    /**
     * Prepare data for the DataTable.
     *
     * @param Request $request
     * @return DataTables
     * @throws Exception
     */
    public function getData(Request $request)
    {
        return $this->applicationService->getDatatable($request);
    }

    /**
     * Display the create form for application.
     *
     * @return View
     */
    public function create()
    {
        return view('fsm.applications.create',[
            'formAction' => $this->applicationService->getCreateFormAction(),
            'formFields' => $this->applicationService->getCreateFormFields(),
            'indexAction' => $this->applicationService->getIndexAction()
        ]);
    }

    /**
     * Get the building details for the selected address.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function buildingDetails(Request $request)
    {
        return $this->applicationService->getBuildingDetails($request);
    }

    /**
     * Store a newly created application in storage.
     *
     * @param ApplicationRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(ApplicationRequest $request)
    {
        return $this->applicationService->createApplication($request);
    }

    /**
     * Display the specified application.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $application = Application::find($id);
        if ($application) {
            $page_title = "Application Details";
            $formFields = $this->applicationService->getShowFormFields($application);
            $indexAction = $this->applicationService->getIndexAction();
            return view('layouts.show',compact('page_title','formFields','application','indexAction'),['cardForm'=>true]);
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified application.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $application = Application::find($id);
        if ($application) {
            $page_title = "Edit Application";
            $formFields = $this->applicationService->getEditFormFields($application);
            $formAction = $this->applicationService->getEditFormAction($application);
            $indexAction = $this->applicationService->getIndexAction();
            return view('fsm.applications.edit',compact('page_title','formFields','formAction','indexAction','application'),['cardForm'=>true]);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified application in storage.
     *
     * @param ApplicationRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(ApplicationRequest $request, $id)
    {
        return $this->applicationService->updateApplication($request,$id);
    }

    /**
     * Remove the specified application from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {

            $application = Application::findOrFail($id);
            if($application->emptying){
                $application->emptying->delete();
            }
            if($application->feedback){
                $application->feedback->delete();
            }
            if($application->sludge_collection){
                $application->sludge_collection->delete();
            }
            $application->delete();
        } catch (\Throwable $e) {
            return redirect(route('application.index'))->with('error','Failed to delete Application');
        }
        return redirect(route('application.index'))->with('success','Application deleted successfully');

    }

    /**
     * Get the history of changes on the specified application.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function history($id)
    {
       return $this->applicationService->getApplicationHistory($id);
    }

    /**
     * Export applications to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->applicationService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('application.index'))->with('error','Failed to export applications');
        }
    }
    /**
    * Generate a PDF report for monthly applications.
    *
    * @param int $year The year for the report.
    * @param int $month The month for the report.
    * @return \Illuminate\Http\Response The generated PDF report.
    */
    public function monthlyApplicationsPdf($year, $month)
    {
        return $this->applicationService->fethMonthlyReport($year, $month);
    }
    /**
    * Retrieve a report for a specific application.
    *
    * @param int $id The ID of the application.
    * @return \Illuminate\Http\Response The application report.
    */
    public function applicationReport($id)
    {
        return $this->applicationService->getApplicationReport($id);
    }
}
