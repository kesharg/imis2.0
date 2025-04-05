<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\services\TransferLogOutRequest;
use App\Models\Swm\TransferLogOut;
use App\Services\Swm\SwmServices\TransferLogOutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TransferLogOutController extends Controller
{
    protected $transferLogOutService;

    public function __construct(TransferLogOutService $transferLogOutService)
    {
        $this->transferLogOutService = $transferLogOutService;
    }

    /**
     * Display a listing of transfer log outs.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Transfer Log Out')?route('transfer-log-out.create'):null;
        $createBtnTitle = 'Add Transfer Log Out';
        $filterFormFields = $this->transferLogOutService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Transfer Log Outs')?$this->transferLogOutService->getExportRoute():null;
        return view('swm.swm-services.transfer-log-out.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $transferLogOutData = $this->transferLogOutService->getAllTransferLogOuts();
        return DataTables::of($transferLogOutData)
            ->filter(function ($query) use ($request) {
                if(!Auth::user()->hasRole('Super Admin') && !Auth::user()->hasRole('Municipality - IT Admin') && !Auth::user()->hasRole('Municipality - Executive') && !Auth::user()->hasRole('Solid Waste - Admin')) {
                    if (Auth::user()->hasRole('Solid Waste - Transfer Station')){
                        $query->where('swm.transfer_log_outs.transfer_station_id',"=",Auth::user()->transfer_station_id);
                    }else if (Auth::user()->hasRole('Solid Waste - Landfill')){
                        $query->where('swm.transfer_log_outs.landfill_site_id',"=",Auth::user()->landfill_site_id)
                        ->where('swm.transfer_log_outs.received',"=",true);
                    }
                }
                if ($request->transfer_station_id){
                    $query->where('transfer_station_id',$request->transfer_station_id);
                }
                if ($request->landfill_site_id){
                    $query->where('landfill_site_id',$request->landfill_site_id);
                }
                if ($request->type_of_waste){
                    $query->where('type_of_waste',$request->type_of_waste);
                }
                if (!is_null($request->received)){
                    $query->where('received',$request->received);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['transfer-log-out.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Transfer Log Out')){
                    $content .= '<a title="Edit" href="' . route("transfer-log-out.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Transfer Log Out')){
                    $content .= '<a title="Detail" href="' . route("transfer-log-out.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("transfer-log-out.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Transfer Log Out')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->editColumn('received', function ($model){
                return $model->received?$model->received_datetime : '<i class="fa fa-times"></i>';
            })
            ->rawColumns(['received','action'])
            ->make(true);
    }

    /**
     * Display the create form for transfer log out.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-services.transfer-log-out.create',[
            'formAction' => $this->transferLogOutService->getCreateFormAction(),
            'indexAction' => $this->transferLogOutService->getIndexAction(),
            'formFields' => $this->transferLogOutService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created transfer log out in storage.
     *
     * @param TransferLogOutRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(TransferLogOutRequest $request)
    {
        if ($request->validated()){
            try {
                TransferLogOut::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Transfer Log Out couldn't be created!");
            }
        }
        return redirect(route('transfer-log-out.index'))->with('success','Transfer Log Out created successfully');
    }

    /**
     * Display the specified transfer log out.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $transferLogOut = TransferLogOut::find($id);
        if ($transferLogOut) {
            $page_title = "Transfer Log Out Details";
            $indexAction = $this->transferLogOutService->getIndexAction();
            $formFields = $this->transferLogOutService->getShowFormFields($transferLogOut);
            return view('layouts.show', compact('page_title','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified transfer log out.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $transferLogOut = TransferLogOut::find($id);
        if ($transferLogOut) {
            $page_title = "Edit Transfer Log Out";
            $formFields = $this->transferLogOutService->getEditFormFields($transferLogOut);
            $indexAction = $this->transferLogOutService->getIndexAction();
            $formAction = $this->transferLogOutService->getEditFormAction($transferLogOut);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified transfer log out in storage.
     *
     * @param TransferLogOutRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(TransferLogOutRequest $request, $id)
    {
        try {
            $transferLogOut = TransferLogOut::findOrFail($id)->update($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Transfer Log Out');
        }
        return redirect(route('transfer-log-out.index'))->with('success','Transfer Log Out updated successfully');
    }

    /**
     * Remove the specified transfer log out from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $transferLogOut = TransferLogOut::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('transfer-log-out.index'))->with('error','Failed to delete Transfer Log Out');
        }
        return redirect(route('transfer-log-out.index'))->with('success','FSM campaign deleted Transfer Log Out');

    }

    /**
     * Export transfer log outs to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->transferLogOutService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('transfer-log-out.index'))->with('error','Failed to export transfer log outs.');
        }
    }
}
