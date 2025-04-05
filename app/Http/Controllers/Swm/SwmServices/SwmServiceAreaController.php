<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\ServiceAreaRequest;
use App\Models\Swm\ServiceArea;
use App\Services\Swm\SwmServices\SwmServiceAreaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SwmServiceAreaController extends Controller
{
    protected $swmServiceAreaService;

    public function __construct(SwmServiceAreaService $swmServiceAreaService)
    {
        $this->swmServiceAreaService = $swmServiceAreaService;
    }

    /**
     * Display a listing of service areas.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Service Area')?route('service-area.create'):null;
        $createBtnTitle = 'Add Service Area';
        $filterFormFields = $this->swmServiceAreaService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export service areas')?$this->swmServiceAreaService->getExportRoute():null;
        return view('swm.swm-registrations.service-area.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
    }

    /**
     * Prepare data for the DataTable.
     *
     * @param Request $request
     * @return DataTables
     * @throws \Exception
     */
    public function getData(Request $request)
    {
        $serviceAreaData = $this->swmServiceAreaService->getAllServiceAreas();
        return DataTables::of($serviceAreaData)
            ->filter(function ($query) use ($request) {
                if ($request->service_provider_id){
                    $query->where('service_provider_id',$request->service_provider_id);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['service-area.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Service Area')){
                    $content .= '<a title="Edit" href="' . route("service-area.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Service Area')){
                    $content .= '<a title="Detail" href="' . route("service-area.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("service-area.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Service Area')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for Service Area.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.service-area.create',[
            'formAction' => $this->swmServiceAreaService->getCreateFormAction(),
            'indexAction' => $this->swmServiceAreaService->getIndexAction(),
            'formFields' => $this->swmServiceAreaService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created Service Area in storage.
     *
     * @param ServiceAreaRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(ServiceAreaRequest $request)
    {
        if ($request->validated()){
            try {
                ServiceArea::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Service Area couldn't be created!");
            }
        }

        return redirect(route('service-area.index'))->with('success','Service Area created successfully');
    }

    /**
     * Display the specified Service Area.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $serviceArea= ServiceArea::find($id);
        if ($serviceArea) {
            $page_title = "Service Area Detail";
            $indexAction = $this->swmServiceAreaService->getIndexAction();
            $formFields = $this->swmServiceAreaService->getShowFormFields($serviceArea);
            return view('swm.swm-registrations.service-area.show', compact('page_title','serviceArea','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified Service Area.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $serviceArea= ServiceArea::find($id);
        if ($serviceArea) {
            $page_title = "Edit Service Area";
            $formFields = $this->swmServiceAreaService->getEditFormFields($serviceArea);
            $indexAction = $this->swmServiceAreaService->getIndexAction();
            $formAction = $this->swmServiceAreaService->getEditFormAction($serviceArea);
            return view('swm.swm-registrations.service-area.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified Service Area in storage.
     *
     * @param ServiceAreaRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(ServiceAreaRequest $request, $id)
    {
        try {
            $serviceArea = ServiceArea::findOrFail($id)->update($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Service Area');
        }
        return redirect(route('service-area.index'))->with('success','Service Area updated successfully');
    }

    /**
     * Remove the specified Service Area from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $serviceArea = ServiceArea::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('service-area.index'))->with('error','Failed to delete Service Area!');
        }
        return redirect(route('service-area.index'))->with('success','Service Area deleted successfully!');

    }

    /**
     * Export service areas to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->swmServiceAreaService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('service-area.index'))->with('error','Failed to export service areas.');
        }
    }
}
