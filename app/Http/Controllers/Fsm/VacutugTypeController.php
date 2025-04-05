<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;

use App\Http\Controllers\Controller;
use App\Models\Fsm\VacutugType;
use App\Http\Requests\Fsm\VacuTugRequest;
use Auth;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Fsm\ServiceProvider;
use App\Services\Fsm\VacutugTypeService;
use App\Enums\VacutugStatus;
use App\Enums\VacutugComplyMaintainStandard;
class VacutugTypeController extends Controller
{
    protected VacutugTypeService $vacutugTypeService;

    public function __construct(VacutugTypeService $vacutugTypeService)
    {
        $this->middleware('auth');
        $this->middleware('permission:List Vacutug Types', ['only' => ['index']]);
        $this->middleware('permission:View Vacutug Type', ['only' => ['show']]);
        $this->middleware('permission:Add Vacutug Type', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit Vacutug Type', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Vacutug Type', ['only' => ['destroy']]);
        $this->vacutugTypeService = $vacutugTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Vacutug is the correct spelling
        $page_title = "Desludging Vehicles";
        $status = VacutugStatus::asSelectArray();
        return view('fsm/vacutug-types.index', compact('page_title','status'));
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        return $this->vacutugTypeService->getAllVacutugTypes($data);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Add Desludging Vehicle";
        $serviceProviders = ServiceProvider::Operational()->orderBy('id')->pluck('company_name', 'id');
        $status = VacutugStatus::asSelectArray();
        $complyMaintainStandard = VacutugComplyMaintainStandard::asSelectArray();
        return view('fsm/vacutug-types.create', compact('page_title', 'serviceProviders', 'status', 'complyMaintainStandard'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacuTugRequest $request)
    {
        $data = $request->all();
        $this->vacutugTypeService->storeOrUpdate(null,$data);

        return redirect('fsm/desludging-vehicles')->with('success','Desludging Vehicle created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vacutugType = VacutugType::find($id);
        if ($vacutugType) {
            $service_provider_id = ServiceProvider::Operational()->find($vacutugType->service_provider_id);
            $serviceProviders = $service_provider_id ? $service_provider_id->company_name : null;
            $status = VacutugStatus::getDescription($vacutugType->status);
            $vacutugComplyMaintainStandard = VacutugComplyMaintainStandard::getDescription($vacutugType->comply_with_maintainance_standards);
            $page_title = "Desludging Vehicle Details";
            return view('fsm/vacutug-types.show', compact('page_title', 'vacutugType', 'serviceProviders', 'status', 'vacutugComplyMaintainStandard'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = VacutugStatus::asSelectArray();
        $vacutugType = VacutugType::find($id);
        $serviceProviders = ServiceProvider::Operational()->orderBy('id')->pluck('company_name', 'id');
        $complyMaintainStandard = VacutugComplyMaintainStandard::asSelectArray();

        if ($vacutugType) {
            $page_title = "Edit Desludging Vehicle";
            return view('fsm/vacutug-types.edit', compact('page_title', 'vacutugType', 'serviceProviders', 'status', 'complyMaintainStandard'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacuTugRequest $request, $id)
    {
        $vacutugType = VacutugType::find($id);
        if ($vacutugType) {
            $data = $request->all();
            $this->vacutugTypeService->storeOrUpdate($vacutugType->id,$data);
            return redirect('fsm/desludging-vehicles')->with('success','Desludging Vehicle updated successfully');
        } else {
            return redirect('fsm/desludging-vehicles')->with('error','Failed to update Desludging Vehicle');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $VacutugType = VacutugType::find($id);
        if ($VacutugType) {

                $VacutugType->delete();
                return redirect('fsm/desludging-vehicles')->with('success','Desludging Vehicle deleted successfully');

        } else {
            return redirect('fsm/desludging-vehicles')->with('error','Failed to delete Desludging Vehicle');
        }
    }

    /**
     * Display history of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($id)
    {
        $vacutugType = VacutugType::find($id);
        if ($vacutugType) {
            $page_title = "Desludging Vehicle History";
            return view('fsm/vacutug-types.history', compact('page_title', 'vacutugType'));
        } else {
            abort(404);
        }
    }

    /**
     * Export a listing of the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $data = $request->all();
        return $this->vacutugTypeService->download($data);
    }
}
