<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;

use App\Http\Controllers\Controller;
use App\Models\Fsm\ServiceProvider;
use App\Http\Requests\Fsm\ServiceProviderRequest;
use Auth;
use Illuminate\Http\Request;
use App\Models\Fsm\TreatmentPlant;
use App\Models\Fsm\SludgeCollection;
use App\Models\LayerInfo\Ward;
use DB;
use App\Services\Fsm\ServiceProviderService;
use App\Services\Auth\UserService;
use App\Enums\ServiceProviderStatus;

class ServiceProviderController extends Controller
{
    protected ServiceProviderService $serviceProviderService;
    protected UserService $userService;
    public function __construct(ServiceProviderService $serviceProviderService, UserService $userService)
    {
        $this->middleware('auth');
        $this->middleware('permission:List Service Providers', ['only' => ['index']]);
        $this->middleware('permission:View Service Provider', ['only' => ['show']]);
        $this->middleware('permission:Add Service Provider', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit Service Provider', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Service Provider', ['only' => ['destroy']]);
        $this->middleware('permission:Export Service Providers to Excel', ['only' => ['export']]);
        $this->serviceProviderService = $serviceProviderService;
        $this->userService = $userService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Service Providers";
        if(Auth::user()->hasRole('sludge_collection_user')) {
            $treatmentPlants = TreatmentPlant::Operational()->where('id', Auth::user()->treatment_plant_id)->orderBy('id')->pluck('name', 'id');
        }
        else {
            $treatmentPlants = TreatmentPlant::Operational()->orderBy('id')->pluck('name', 'id');
        }
        $ward = Ward::orderBy('ward')->pluck('ward','ward');
        $serviceProviderStatus = ServiceProviderStatus::asSelectArray();
        return view('fsm/service-providers.index', compact('page_title', 'treatmentPlants', 'ward', 'serviceProviderStatus'));
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        return $this->serviceProviderService->getAllServiceProviders($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Add Service Provider";
        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $serviceProvider = null;
        $serviceProviderStatus = ServiceProviderStatus::asSelectArray();
        return view('fsm/service-providers.create', compact('page_title', 'wards','serviceProvider', 'serviceProviderStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceProviderRequest $request)
    {
        $data = $request->all();
        
        $serviceProviderId = $this->serviceProviderService->storeOrUpdate($id = null,$data);
        if(!is_null($request->create_user))
        {
             $data['service_provider_id'] = $serviceProviderId;
             $data['user_type'] = "Service Provider";
             $data['roles']= "Service Provider - Admin";
             $data['gender']= null;
             $data['username']= null;
             $data['name'] = $data['company_name'];
             
             $this->userService->storeOrUpdate($id = null,$data);
             $successMessage = 'Service Provider and Service Provider - Admin User created successfully';
        } else {
             $successMessage = 'Service Provider created successfully';

        }
        return redirect('fsm/service-providers')->with('success',$successMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serviceProvider = ServiceProvider::find($id);
        if ($serviceProvider) {
            $page_title = "Service Provider Details";
            $status = ServiceProviderStatus::getDescription($serviceProvider->status);
            return view('fsm/service-providers.show', compact('page_title', 'serviceProvider', 'status'));
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
        $serviceProvider = ServiceProvider::find($id);
        $wards = Ward::orderBy('ward')->pluck('ward', 'ward')->toArray();
        $serviceProviderStatus = ServiceProviderStatus::asSelectArray();
        if ($serviceProvider) {
            $page_title = "Edit Service Provider";
            return view('fsm/service-providers.edit', compact('page_title', 'serviceProvider', 'wards', 'serviceProviderStatus'));
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
    public function update(ServiceProviderRequest $request, $id)
    {
        $serviceProvider = ServiceProvider::find($id);
        if ($serviceProvider) {
            $data = $request->all();
            $this->serviceProviderService->storeOrUpdate($serviceProvider->id,$data);
            return redirect('fsm/service-providers')->with('success','Service Provider updated successfully');
        } else {
            return redirect('fsm/service-providers')->with('error','Failed to update Servie Provider');
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
        $serviceProvider = ServiceProvider::find($id);
        if ($serviceProvider) {

            if($serviceProvider->applications()->exists()){
                $applicationsCount =  $serviceProvider->applications()->where('emptying_status', 'false')->count();
               
                if($applicationsCount > 0)
                    {
                    /*While deleting service provider, update service_provider_id to null having emptying_status false i.e. if emptying service is not done yet for an application and corresponding service provider is deleted or not in operation, help desk should have privilege to assign another service provider */
                        $results = \App\Models\Fsm\Application::where(['service_provider_id'=>$id,'emptying_status'=>'false'])->get();
                        
                        foreach($results as $result){
                                $application= \App\Models\Fsm\Application::find($result->id);
                                $application->service_provider_id = null;
                                $application->save();
                        }
                    }
                }
                if($serviceProvider->users()){
                    $serviceProvider->users()->delete();
                }
                if($serviceProvider->vacutugTypes()){
                    $serviceProvider->vacutugTypes()->delete();
                }
                $serviceProvider->delete();
                return redirect('fsm/service-providers')->with('success','Service Provider deleted successfully');
            
        } else {
            return redirect('fsm/service-providers')->with('error','Failed to delete service provider');
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
        $serviceProvider = ServiceProvider::find($id);
        if ($serviceProvider) {
            $page_title = "Service Provider History";
            return view('fsm/service-providers.history', compact('page_title', 'serviceProvider'));
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
        return $this->serviceProviderService->download($data);
        
    }
}
