<?php

namespace App\Http\Controllers\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\CollectionPointRequest;
use App\Models\Swm\CollectionPoint;
use App\Services\Swm\SwmRegistrations\CollectionPointService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CollectionPointController extends Controller
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
        $createBtnLink = Auth::user()->can('Add Collection Point')?route('collection-point.create'):null;
        $createBtnTitle = 'Add Collection point';
        $filterFormFields = $this->collectionPointService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Collection Points')?$this->collectionPointService->getExportRoute():null;
        return view('swm.swm-registrations.collection-point.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $collectionPointData = $this->collectionPointService->getAllCollectionPoints();
        return DataTables::of($collectionPointData)
            ->filter(function ($query) use ($request) {
                if ($request->route_id){
                    $query->where('route_id',$request->route_id);
                }
                if ($request->type){
                    $query->where('type',$request->type);
                }
                if ($request->ward){
                    $query->where('ward',$request->ward);
                }
                if ($request->service_type){
                    $query->where('service_type',$request->service_type);
                }
                if ($request->status){
                    $query->where('status',$request->status);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['collection-point.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Collection Point')){
                    $content .= '<a title="Edit" href="' . route('collection-point.edit', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Collection Point')){
                    $content .= '<a title="Detail" href="' . route('collection-point.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route('collection-point.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Collection Point')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for collection point.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.collection-point.create',[
            'formAction' => $this->collectionPointService->getCreateFormAction(),
            'indexAction' => $this->collectionPointService->getIndexAction(),
            'formFields' => $this->collectionPointService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created collection point in storage.
     *
     * @param CollectionPointRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(CollectionPointRequest $request)
    {
        $collectionPoint = null;
        if ($request->validated()){
            try {
                $collectionPoint = CollectionPoint::create($request->all());
                if($request->longitude && $request->latitude) {
                    $collectionPoint->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
                }
                $collectionPoint->save();
            } catch (\Throwable $e) {
                if($collectionPoint){
                    $collectionPoint->forceDelete();
                }
                return redirect()->back()->withInput()->with('error',"Error! Collection point couldn't be created.");
            }
        }
        return redirect(route('collection-point.index'))->with('success','Collection point created successfully');
    }

    /**
     * Display the specified collection point.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $collectionPoint = CollectionPoint::find($id);
        if ($collectionPoint) {
            $page_title = "Collection point information";
            $indexAction = $this->collectionPointService->getIndexAction();
            $formFields = $this->collectionPointService->getShowFormFields($collectionPoint);
            return view('swm.swm-registrations.collection-point.show',compact('page_title','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified collection point.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $collectionPoint = CollectionPoint::find($id);
        if ($collectionPoint) {
            $page_title = "Edit Collection Point";
            $formFields = $this->collectionPointService->getEditFormFields($collectionPoint);
            $indexAction = $this->collectionPointService->getIndexAction();
            $formAction = $this->collectionPointService->getEditFormAction($collectionPoint);
            return view('swm.swm-registrations.collection-point.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified collection point in storage.
     *
     * @param CollectionPointRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(CollectionPointRequest $request, $id)
    {
        try {
            CollectionPoint::findOrFail($id)->update($request->all());
            $collectionPoint = CollectionPoint::findOrFail($id);
            if($request->longitude && $request->latitude) {
                $collectionPoint->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
            }
            $collectionPoint->save();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Collection point');
        }
        return redirect(route('collection-point.index'))->with('success','Collection point updated successfully');
    }

    /**
     * Remove the specified collection point from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
       try {
           $collectionPoint = CollectionPoint::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('collection-point.index'))->with('error','Failed to delete Collection point');
        }
        return redirect(route('collection-point.index'))->with('success','Collection point deleted successfully');

    }

    /**
     * Export collection points to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->collectionPointService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('collection-point.index'))->with('error','Failed to export collection points.');
        }
    }

}
