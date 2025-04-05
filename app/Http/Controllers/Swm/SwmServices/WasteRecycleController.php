<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\services\WasteRecycleRequest;
use App\Models\Swm\WasteRecycle;
use App\Services\Swm\SwmServices\WasteRecycleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class WasteRecycleController extends Controller
{
    protected $wasteRecycleService;

    public function __construct(WasteRecycleService $wasteRecycleService)
    {
        $this->wasteRecycleService = $wasteRecycleService;
    }

    /**
     * Display a listing of waste recycles.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Waste Recycle')?route('waste-recycle.create'):null;
        $createBtnTitle = 'Add Waste Recycle';
        $filterFormFields = $this->wasteRecycleService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Waste Recycles')?$this->wasteRecycleService->getExportRoute():null;
        return view('swm.swm-services.waste-recycle.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $wasteRecycleData = $this->wasteRecycleService->getAllTransferLogOuts();
        return DataTables::of($wasteRecycleData)
            ->filter(function ($query) use ($request) {
                if(!Auth::user()->hasRole('Super Admin') && !Auth::user()->hasRole('Municipality - IT Admin') && !Auth::user()->hasRole('Municipality - Executive') && !Auth::user()->hasRole('Solid Waste - Admin')) {
                    if (Auth::user()->hasRole('Solid Waste - Transfer Station')) {
                        $query->where('swm.waste_recycles.transfer_station_id', "=", Auth::user()->transfer_station_id);
                    }
                }
                if ($request->transfer_station_id){
                    $query->where('transfer_station_id',$request->transfer_station_id);
                }
                if ($request->waste_type){
                    $query->where('waste_type',$request->waste_type);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['waste-recycle.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Waste Recycle')){
                    $content .= '<a title="Edit" href="' . route("waste-recycle.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Waste Recycle')){
                    $content .= '<a title="Detail" href="' . route("waste-recycle.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("waste-recycle.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Waste Recycle')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for waste recycle.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-services.waste-recycle.create',[
            'formAction' => $this->wasteRecycleService->getCreateFormAction(),
            'indexAction' => $this->wasteRecycleService->getIndexAction(),
            'formFields' => $this->wasteRecycleService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created waste recycle in storage.
     *
     * @param WasteRecycleRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(WasteRecycleRequest $request)
    {
        if ($request->validated()){
            try {
                WasteRecycle::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Waste Recycle couldn't be created!");
            }
        }
        return redirect(route('waste-recycle.index'))->with('success','Waste Recycle created successfully');
    }

    /**
     * Display the specified waste recycle.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $wasteRecycle = WasteRecycle::find($id);
        if ($wasteRecycle) {
            $page_title = "Waste Recycle Details";
            $indexAction = $this->wasteRecycleService->getIndexAction();
            $formFields = $this->wasteRecycleService->getShowFormFields($wasteRecycle);
            return view('layouts.show', compact('page_title', 'formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified waste recycle.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $wasteRecycle = WasteRecycle::find($id);
        if ($wasteRecycle) {
            $page_title = "Edit Waste Recycle";
            $formFields = $this->wasteRecycleService->getEditFormFields($wasteRecycle);
            $indexAction = $this->wasteRecycleService->getIndexAction();
            $formAction = $this->wasteRecycleService->getEditFormAction($wasteRecycle);
            return view('swm.swm-services.waste-recycle.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified waste recycle from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function update(WasteRecycleRequest $request, $id)
    {
        try {
            $wasteRecycle = WasteRecycle::findOrFail($id)->update($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Waste Recycle');
        }
        return redirect(route('waste-recycle.index'))->with('success','Waste Recycle updated successfully');
    }

    /**
     * Remove the specified waste recycle from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $wasteRecycle = WasteRecycle::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('waste-recycle.index'))->with('error','Failed to delete Waste Recycle');
        }
        return redirect(route('waste-recycle.index'))->with('success','Waste Recycle deleted successfully');

    }

    /**
     * Export waste recycles to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->wasteRecycleService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('waste-recycle.index'))->with('error','Failed to export waste recycles.');
        }
    }
}
