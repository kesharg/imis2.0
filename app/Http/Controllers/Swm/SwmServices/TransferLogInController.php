<?php

namespace App\Http\Controllers\Swm\SwmServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\swm\services\TransferLogInRequest;
use App\Models\Swm\TransferLogIn;
use App\Services\Swm\SwmServices\TransferLogInService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
class TransferLogInController extends Controller
{
    protected $transferLogInService;

    public function __construct(TransferLogInService $transferLogInService)
    {
        $this->transferLogInService = $transferLogInService;
    }

    /**
     * Display a listing of transfer log ins.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Transfer Log In')?route('transfer-log-in.create'):null;
        $createBtnTitle = 'Add Transfer Log In';
        $filterFormFields = $this->transferLogInService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Transfer Log Ins')?$this->transferLogInService->getExportRoute():null;
        return view('swm.swm-services.transfer-log-in.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
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
        $transferLogInData = $this->transferLogInService->getAllTransferLogIns();
        return DataTables::of($transferLogInData)
            ->filter(function ($query) use ($request) {
                if(!Auth::user()->hasRole('Super Admin') && !Auth::user()->hasRole('Municipality - IT Admin') && !Auth::user()->hasRole('Municipality - Executive') && !Auth::user()->hasRole('Solid Waste - Admin')) {
                    if (Auth::user()->hasRole('Solid Waste - Transfer Station')) {
                        $query->where('swm.transfer_log_ins.transfer_station_id', "=", Auth::user()->transfer_station_id);
                    }
                }
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
                $content = \Form::open(['method' => 'DELETE', 'route' => ['transfer-log-in.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Transfer Log In')){
                    $content .= '<a title="Edit" href="' . route("transfer-log-in.edit", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Transfer Log In')){
                    $content .= '<a title="Detail" href="' . route("transfer-log-in.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route("transfer-log-in.show", [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Transfer Log In')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for transfer log in.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-services.transfer-log-in.create',[
            'formAction' => $this->transferLogInService->getCreateFormAction(),
            'indexAction' => $this->transferLogInService->getIndexAction(),
            'formFields' => $this->transferLogInService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created transfer log in in storage.
     *
     * @param TransferLogInRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(TransferLogInRequest $request)
    {
        if ($request->validated()){
            try {
                $transferLogIn = TransferLogIn::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Transfer Log In couldn't be created!");
            }
        }

        return redirect(route('transfer-log-in.index'))->with('success','Transfer Log In created successfully');
    }

    /**
     * Display the specified transfer log in.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $transferLogIn= TransferLogIn::find($id);
        if ($transferLogIn) {
            $page_title = "Transfer Log In Detail";
            $indexAction = $this->transferLogInService->getIndexAction();
            $formFields = $this->transferLogInService->getShowFormFields($transferLogIn);
            return view('layouts.show', compact('page_title','transferLogIn','formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified transfer log in.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $transferLogIn= TransferLogIn::find($id);
        if ($transferLogIn) {
            $page_title = "Edit Transfer Log In";
            $formFields = $this->transferLogInService->getEditFormFields($transferLogIn);
            $indexAction = $this->transferLogInService->getIndexAction();
            $formAction = $this->transferLogInService->getEditFormAction($transferLogIn);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified transfer log in in storage.
     *
     * @param TransferLogInRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(TransferLogInRequest $request, $id)
    {
                try {
                    $transferLogIn = TransferLogIn::findOrFail($id)->update($request->all());
                } catch (\Throwable $e) {
                    return redirect()->back()->withInput()->with('error','Failed to update Transfer Log In');
                }
                return redirect(route('transfer-log-in.index'))->with('success','Transfer Log In updated successfully');
    }

    /**
     * Remove the specified transfer log in from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
               try {
                   $transferLogIn = TransferLogIn::findOrFail($id)->delete();
               } catch (\Throwable $e) {
                   return redirect(route('transfer-log-in.index'))->with('error','Failed to delete Transfer Log In!');
               }
               return redirect(route('transfer-log-in.index'))->with('success','Transfer Log In deleted successfully!');

    }

    /**
     * Export transfer log ins to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->transferLogInService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('transfer-log-in.index'))->with('error','Failed to export transfer log ins.');
        }
    }
}
