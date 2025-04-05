<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Fsm\TreatmentplantPerformanceTestRequest;
use App\Services\Fsm\TreatmentplantPerformanceTestService;
use App\Models\Fsm\TreatmentPlantPerformanceTest;
use App\Models\SiteSetting;



class TreatmentplantPerformanceTestController extends Controller
{
    protected TreatmentplantPerformanceTestService $treatmentplantPerformanceTestService;

    public function __construct(TreatmentplantPerformanceTestService $treatmentplantPerformanceTestService)
    {
        $this->middleware('auth');
        $this->treatmentplantPerformanceTestService = $treatmentplantPerformanceTestService;
    }
    /**
    * Display a listing of the treatment plant performance test data.
    *
    * @param TreatmentplantPerformanceTestRequest $request The request object containing the treatment plant performance test data.
    * @return \Illuminate\Contracts\View\View Returns a view with the performance efficiency standards data.
    */
    public function index(TreatmentplantPerformanceTestRequest $request)
    {
        $page_title = "Performance Efficiency Standards";
        $data = TreatmentPlantPerformanceTest::all();
        return view('fsm/treatment-plant-performance-test.index', compact('page_title', 'data'));
    }

    /**
    * Store or update treatment plant performance test data.
    *
    * @param TreatmentplantPerformanceTestRequest $request The request object containing the treatment plant performance test data.
    * @return \Illuminate\Http\RedirectResponse Redirects the user to a specified URL with a success message.
    */
    public function store(TreatmentplantPerformanceTestRequest $request)
    {
        $data = $request->all();
        $result = $this->treatmentplantPerformanceTestService->storeOrUpdate($data);
        return redirect('fsm/treatment-plant-performance-test')->with('success', ' Performance Efficiency Standards updated successfully');
    }
    
}
