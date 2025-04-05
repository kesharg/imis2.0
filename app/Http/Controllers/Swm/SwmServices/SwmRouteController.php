<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\RouteRequest;
use App\Models\Swm\Route;
use App\Services\Swm\SwmServices\SwmRouteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SwmRouteController extends Controller
{
    protected $routeService;

    public function __construct(SwmRouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    /**
     * Display a listing of swm routes.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Route')?route('route.create'):null;
        $createBtnTitle = 'Add Route';
        $filterFormFields = $this->routeService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Routes')?$this->routeService->getExportRoute():null;
        return view('swm.swm-registrations.route.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $routeData = $this->routeService->getAllRoutes();
        return DataTables::of($routeData)
            ->filter(function ($query) use ($request) {
                if ($request->service_provider_id){
                    $query->where('service_provider_id',$request->service_provider_id);
                }
                if ($request->name){
                    $query->where('name','ILIKE','%'.$request->name.'%');
                }
                if ($request->type){
                    $query->where('type',$request->type);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['route.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Route')){
                    $content .= '<a title="Edit" href="' . route("route.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Route')){
                    $content .= '<a title="Detail" href="' . route("route.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("route.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Route')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for route.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.route.create',[
            'formAction' => $this->routeService->getCreateFormAction(),
            'indexAction' => $this->routeService->getIndexAction(),
            'formFields' => $this->routeService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created route storage.
     *
     * @param RouteRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(RouteRequest $request)
    {
        if ($request->validated()){
            try {
                Route::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Route couldn't be created!");
            }
        }

        return redirect(route('route.index'))->with('success','Route created successfully');
    }

    /**
     * Display the specified route.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $route= Route::find($id);
        if ($route) {
            $page_title = "Route Detail";
            $indexAction = $this->routeService->getIndexAction();
            $formFields = $this->routeService->getShowFormFields($route);
            return view('layouts.show', compact('page_title','route','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified route.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $route= Route::find($id);
        if ($route) {
            $page_title = "Edit Route";
            $formFields = $this->routeService->getEditFormFields($route);
            $indexAction = $this->routeService->getIndexAction();
            $formAction = $this->routeService->getEditFormAction($route);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified Route in storage.
     *
     * @param RouteRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(RouteRequest $request, $id)
    {
        try {
            $route = Route::findOrFail($id)->update($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Route');
        }
        return redirect(route('route.index'))->with('success','Route updated successfully');
    }

    /**
     * Remove the specified Route from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $route = Route::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('route.index'))->with('error','Failed to delete Route!');
        }
        return redirect(route('route.index'))->with('success','Route deleted successfully!');

    }

    /**
     * Export Routes to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->routeService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('route.index'))->with('error','Failed to export Routes.');
        }
    }
}
