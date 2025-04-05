<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\ServiceProviderRequest;
use App\Models\Swm\ServiceProvider;
use App\Services\Swm\SwmServices\SwmServiceProviderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SwmServiceProviderController extends Controller
{
    protected $serviceProviderService;

    public function __construct(SwmServiceProviderService $serviceProviderService)
    {
        $this->serviceProviderService = $serviceProviderService;
    }

    /**
     * Display a listing of service providers.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Service Provider')?route('service-provider.create'):null;
        $createBtnTitle = 'Add Service Provider';
        $filterFormFields = $this->serviceProviderService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export service providers')?$this->serviceProviderService->getExportRoute():null;
        return view('swm.swm-registrations.service-provider.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $serviceProviderData = $this->serviceProviderService->getAllServiceProviders();
        return DataTables::of($serviceProviderData)
            ->filter(function ($query) use ($request) {
                if ($request->route_id){
                    $query->where('route_id',$request->route_id);
                }
                if ($request->type_of_waste){
                    $query->where('type_of_waste',$request->type_of_waste);
                }
                if ($request->transfer_station_id){
                    $query->where('transfer_station_id',$request->transfer_station_id);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['service-provider.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Service Provider')){
                    $content .= '<a title="Edit" href="' . route("service-provider.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Service Provider')){
                    $content .= '<a title="Detail" href="' . route("service-provider.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("service-provider.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Service Provider')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for Service Provider.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.service-provider.create',[
            'formAction' => $this->serviceProviderService->getCreateFormAction(),
            'indexAction' => $this->serviceProviderService->getIndexAction(),
            'formFields' => $this->serviceProviderService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created Service Provider in storage.
     *
     * @param ServiceProviderRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(ServiceProviderRequest $request)
    {
        $serviceProvider = null;
        if ($request->validated()){
            try {
                $serviceProvider = ServiceProvider::create($request->all());
                if($request->longitude && $request->latitude) {
                    $serviceProvider->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
                }
                $serviceProvider->save();
            } catch (\Throwable $e) {
                if ($serviceProvider){
                    $serviceProvider->forceDelete();
                }
                return redirect()->back()->withInput()->with('error',"Error! Service Provider couldn't be created!");
            }
        }

        return redirect(route('service-provider.index'))->with('success','Service Provider created successfully');
    }

    /**
     * Display the specified Service Provider.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $serviceProvider= ServiceProvider::find($id);
        if ($serviceProvider) {
            $page_title = "Service Provider Detail";
            $indexAction = $this->serviceProviderService->getIndexAction();
            $formFields = $this->serviceProviderService->getShowFormFields($serviceProvider);
            return view('layouts.show', compact('page_title','serviceProvider','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified Service Provider.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $serviceProvider= ServiceProvider::find($id);
        if ($serviceProvider) {
            $page_title = "Edit Service Provider";
            $formFields = $this->serviceProviderService->getEditFormFields($serviceProvider);
            $indexAction = $this->serviceProviderService->getIndexAction();
            $formAction = $this->serviceProviderService->getEditFormAction($serviceProvider);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified Service Provider in storage.
     *
     * @param ServiceProviderRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(ServiceProviderRequest $request, $id)
    {
        try {
            ServiceProvider::findOrFail($id)->update($request->all());
            $serviceProvider = ServiceProvider::findOrFail($id);
            if($request->longitude && $request->latitude) {
                $serviceProvider->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
            }
            $serviceProvider->save();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Service Provider');
        }
        return redirect(route('service-provider.index'))->with('success','Service Provider updated successfully');
    }

    /**
     * Remove the specified Service Provider from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $serviceProvider = ServiceProvider::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('service-provider.index'))->with('error','Failed to delete Service Provider!');
        }
        return redirect(route('service-provider.index'))->with('success','Service Provider deleted successfully!');

    }

    /**
     * Export service providers to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->serviceProviderService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('service-provider.index'))->with('error','Failed to export service providers.');
        }
    }
}
