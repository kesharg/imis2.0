<?php

namespace App\Http\Controllers\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\CollectionPointRequest;
use App\Http\Requests\swm\registrations\TransferStationRequest;
use App\Models\Swm\TransferStation;
use App\Services\Swm\SwmRegistrations\TransferStationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransferStationController extends Controller
{
    protected TransferStationService $transferStationService;

    public function __construct(TransferStationService $transferStationService)
    {
        $this->transferStationService = $transferStationService;
    }

    /**
     * Display a listing of transfer stations.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Transfer Station')?route('transfer-station.create'):null;
        $createBtnTitle = 'Add Transfer Station';
        $filterFormFields = $this->transferStationService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Transfer Stations')?$this->transferStationService->getExportRoute():null;
        return view('swm.swm-registrations.transfer-station.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
    }

    /**
     * Prepare data for the DataTable.
     *
     * @return View
     * @param Request $request
     * @throws \Exception
     */
    public function getData(Request $request)
    {
        $transferStationData = $this->transferStationService->getAllTransferStations();
        return DataTables::of($transferStationData)
            ->filter(function ($query) use ($request) {
                if ($request->name){
                    $query->where('name','ILIKE','%'.$request->name.'%');
                }
                if ($request->ward){
                    $query->where('ward',$request->ward);
                }
                if (!is_null($request->separation_facility)){
                    $query->where('separation_facility',$request->separation_facility);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['transfer-station.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Transfer Station')){
                    $content .= '<a title="Edit" href="' . route('transfer-station.edit', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Transfer Station')){
                    $content .= '<a title="Detail" href="' . route('transfer-station.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route('transfer-station.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Transfer Station')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for transfer station.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.transfer-station.create',[
            'formAction' => $this->transferStationService->getCreateFormAction(),
            'indexAction' => $this->transferStationService->getIndexAction(),
            'formFields' => $this->transferStationService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created transfer station in storage.
     *
     * @param TransferStationRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(TransferStationRequest $request)
    {
        $transferStation = null;
        try {
            $transferStation = TransferStation::create($request->all());
            if($request->longitude && $request->latitude) {
                $transferStation->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
            }
            $transferStation->save();
            } catch (\Throwable $e) {
            if ($transferStation){
                $transferStation->forceDelete();
            }
            return redirect()->back()->withInput()->with('error',"Error! Transfer Station couldn't be created!");
        }
        return redirect(route('transfer-station.index'))->with('success','Transfer Station created successfully');
    }

    /**
     * Display the specified transfer station.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $transferStation = TransferStation::find($id);
        if ($transferStation) {
            $page_title = "Transfer Station Details";
            $indexAction = $this->transferStationService->getIndexAction();
            $formFields = $this->transferStationService->getShowFormFields($transferStation);
            return view('layouts.show', compact('page_title', 'formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified transfer station.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $transferStation = TransferStation::find($id);
        if ($transferStation) {
            $page_title = "Edit Transfer Station";
            $formFields = $this->transferStationService->getEditFormFields($transferStation);
            $indexAction = $this->transferStationService->getIndexAction();
            $formAction = $this->transferStationService->getEditFormAction($transferStation);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified transfer station in storage.
     *
     * @param TransferStationRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(TransferStationRequest $request, $id)
    {
        try {
            TransferStation::findOrFail($id)->update($request->all());
            $transferStation = TransferStation::findOrFail($id);
            if($request->longitude && $request->latitude) {
                $transferStation->geom = DB::raw("ST_GeomFromText('POINT(" . $request->longitude . " " . $request->latitude .  ")', 4326)");
            }
            $transferStation->save();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Transfer Station');
        }
        return redirect(route('transfer-station.index'))->with('success','Transfer Station updated successfully');
    }

    /**
     * Remove the specified transfer station from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $transferStation = TransferStation::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('transfer-station.index'))->with('error','Failed to delete Transfer Station');
        }
        return redirect(route('transfer-station.index'))->with('success','Transfer Station deleted successfully');

    }

    /**
     * Export transfer stations to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->transferStationService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('transfer-station.index'))->with('error','Failed to export transfer stations.');
        }
    }
}
