<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\Fsm\EmployeeInfo;
use App\Http\Requests\Fsm\EmployeeInfoRequest;
use App\Models\Fsm\ServiceProvider;
use Auth;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Fsm\EmployeeInfoService;
use App\Enums\EmployeeStatus;
use Carbon\Carbon;

class EmployeeInfoController extends Controller
{
    protected EmployeeInfoService $employeeInfoService;

    public function __construct(EmployeeInfoService $employeeInfoService)
    {
        $this->middleware('auth');
        $this->middleware('permission:List Employee Infos', ['only' => ['index']]);
        $this->middleware('permission:View Employee Info', ['only' => ['show']]);
        $this->middleware('permission:Add Employee Info', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit Employee Info', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Employee Info', ['only' => ['destroy']]);
        $this->middleware('permission:Export Employee Infos', ['only' => ['export']]);

        $this->employeeInfoService = $employeeInfoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Employee Information";
        $status = EmployeeStatus::asSelectArray();
        return view('fsm/employee-infos.index', compact('page_title', 'status'));
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        return $this->employeeInfoService->getAllEmployeeInfo($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Add Employee Information";
        if(Auth::user()->service_provider_id)
        {
        $id = Auth::user()->service_provider_id;
        $service_provider_id = ServiceProvider::Operational()->where('id', $id)->pluck('id');
        $service_providers = ServiceProvider::Operational()->pluck('company_name','id');
        }
        else
        {
        $service_provider_id = ServiceProvider::Operational()->pluck('company_name','id');
        $service_providers = null;
        }
        $employeeInfos = null;
        $status = EmployeeStatus::asSelectArray();
        return view('fsm/employee-infos.create', compact('page_title','service_provider_id','service_providers','employeeInfos', 'status'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeInfoRequest $request)
    {

        $data = $request->only(['service_provider_id', 'name', 'gender', 'contact_number','dob', 'address', 'employee_type', 'year_of_experience', 'wage', 'license_number', 'license_issue_date', 'training_status', 'employment_start','status', 'employment_end']);
        $employeeInfos = $this->employeeInfoService->storeOrUpdate($id = null,$data);

        return redirect('fsm/employee-infos')->with('success','Employee Information created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $employeeInfos = EmployeeInfo::find($id);
        if ($employeeInfos) {
            $service_provider = ServiceProvider::Operational()->find($employeeInfos->service_provider_id);
            $service_provider_id = $service_provider ? $service_provider->company_name : null;
            $page_title = "Employee Information Details";
            $status = EmployeeStatus::getDescription($employeeInfos->status);
            return view('fsm/employee-infos.show', compact('page_title', 'employeeInfos','status', 'service_provider_id' ));
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
        $employeeInfos = EmployeeInfo::find($id);
        if ($employeeInfos) {
            $start = Carbon::parse($employeeInfos->employment_start)->format('m/d/Y');
            $end = Carbon::parse($employeeInfos->employment_end)->format('m/d/Y');
            $page_title = "Edit Employee Information";
            $service_provider_id = ServiceProvider::Operational()->pluck('company_name','id');
            $status = EmployeeStatus::asSelectArray();
        return view('fsm/employee-infos.edit', compact('page_title', 'employeeInfos','service_provider_id', 'status','start','end'));
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
    public function update(EmployeeInfoRequest $request, EmployeeInfo $employeeInfo)
    {
        $employeeInfos = EmployeeInfo::find($employeeInfo->id);

        if ($employeeInfos) {
            $data = $request->all();

            $employeeInfos = $this->employeeInfoService->storeOrUpdate($employeeInfo->id,$data);

            return redirect('fsm/employee-infos')->with('success','Employee Information updated successfully');
        } else {
            return redirect('fsm/employee-infos')->with('error','Failed to update Employee Info');
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
        $employeeInfos = EmployeeInfo::find($id);

        if ($employeeInfos) {

                $employeeInfos->delete();
                return redirect('fsm/employee-infos')->with('success','Employee Information deleted successfully');

        } else {
            return redirect('fsm/employee-infos')->with('error','Failed to delete Employee Information');
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
        $employeeInfos = EmployeeInfo::find($id);
        if ($employeeInfos) {
            $page_title = "Employee Information History";
            return view('fsm/employee-infos.history', compact('page_title', 'employeeInfos'));
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
        return $this->employeeInfoService->download($data);
    }
}
