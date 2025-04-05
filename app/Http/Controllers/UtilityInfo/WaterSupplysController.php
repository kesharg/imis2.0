<?php
// Last Modified Date: 09-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)
namespace App\Http\Controllers\UtilityInfo;
use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UtilityInfo\WaterSupplys;
use App\Http\Requests\UtilityInfo\WaterSupplysRequest;
use App\Services\UtilityInfo\WaterSupplysService;



class WaterSupplysController extends Controller
{


    protected WaterSupplysService $waterSupplysService;
    public function __construct(WaterSupplysService $waterSupplysService)
    {
        $this->middleware('auth');
        $this->middleware('permission:List WaterSupply Network', ['only' => ['index']]);
        $this->middleware('permission:View WaterSupply Network', ['only' => ['show']]);
        $this->middleware('permission:Add WaterSupply Network', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit WaterSupply Network', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete WaterSupply Network', ['only' => ['destroy']]);
        $this->middleware('permission:Export WaterSupply Network to Excel', ['only' => ['export']]);
        $this->waterSupplysService = $waterSupplysService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Water Supply Network";
        return view('utility-info/water-supplys.index', compact('page_title'));
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        return $this->waterSupplysService->getAllData($data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WaterSupplysRequest $request)
    {
        $data = $request->all();
        $this->waterSuplysService->storeOrUpdate($id = null,$data);
        return redirect('utilityinfo/watersupplys')->with('success','Water Supply created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $waterSupplys = WaterSupplys::find($id);
        if ($waterSupplys) {
            $waterSupplys->diameter = number_format($waterSupplys->diameter, 2);
            // Format the length attribute to display only two decimal places
            $waterSupplys->length = number_format($waterSupplys->length, 2);
            $page_title = "Water Supply Network Details";
            return view('utility-info/water-supplys.show', compact('page_title', 'waterSupplys'));
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
        $waterSupplys = WaterSupplys::find($id);
        if ($waterSupplys) {
            $waterSupplys->diameter = number_format($waterSupplys->diameter, 2);
            // Format the length attribute to display only two decimal places
            $waterSupplys->length = number_format($waterSupplys->length, 2);
            $page_title = "Edit Water Supply Network";
            return view('utility-info/water-supplys.edit', compact('page_title', 'waterSupplys'));
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
    public function update(WaterSupplysRequest $request, $id)
    {
        $waterSupplys = WaterSupplys::find($id);
        if ($waterSupplys) {
            $data = $request->all();
            $this->waterSupplysService->storeOrUpdate($waterSupplys->code,$data);
            return redirect('utilityinfo/watersupplys')->with('success','Water Supply updated successfully');
        } else {
            return redirect('utilityinfo/watersupplys')->with('error','Failed to update water supplys');
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
        $waterSupplys = WaterSupplys::find($id);
        if ($waterSupplys) {
            $waterSupplys->delete();
            return redirect('utilityinfo/watersupplys')->with('success', 'Water Supply deleted successfully');
        } else {
            return redirect('utilityinfo/watersupplys')->with('error','Failed to delete water supplys');
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
        $waterSupplys = WaterSupplys::find($id);
        if ($waterSupplys) {
            $page_title = "Water Supply Network History";
            return view('utility-info/water-supplys.history', compact('page_title', 'waterSupplys'));
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

        return $this->waterSupplysService->download($data);
    }

    public function getWaterSupplyCode(){
        $query = WaterSupplys::all()->toQuery();
        if (request()->search){
            $query->where('code', 'ilike', '%'.request()->search.'%');
        }

        $total = $query->count();


        $limit = 10;
        if (request()->page) {
            $page  = request()->page;
        }
        else{
            $page=1;
        };
        $start_from = ($page-1) * $limit;

        $total_pages = ceil($total / $limit);
        if($page < $total_pages){
            $more = true;
        }
        else
        {
            $more = false;
        }
        $water_supplys = $query->offset($start_from)
            ->limit($limit)
            ->get();
        $json = [];
        foreach($water_supplys as $water_supply)
        {
            $json[] = ['id'=>$water_supply['code'], 'text'=>$water_supply['code']?? $water_supply['code']];
        }

        return response()->json(['results' =>$json, 'pagination' => ['more' => $more] ]);
    }

}
