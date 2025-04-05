<?php
// Last Modified Date: 07-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Http\Controllers\Fsm;

use App\Http\Controllers\Controller;
use App\Models\Fsm\Emptying;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use App\Models\Fsm\TreatmentPlant;
use App\Models\Fsm\SludgeCollection;
use App\Models\Fsm\Application;
use App\Models\Fsm\VacutugType;
use App\Http\Requests\Fsm\SludgeCollectionRequest;
use App\Models\Fsm\ServiceProvider;
use DB;
use DataTables;

use Auth;



class SludgeCollectionController extends Controller
{
    /**
    * Constructor for the SludgeCollectionController.
    * Defines middleware for various permissions related to sludge collections.
    */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:List Sludge Collections', ['only' => ['index']]);
        $this->middleware('permission:View Sludge Collection', ['only' => ['show']]);
        $this->middleware('permission:Add Sludge Collection', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit Sludge Collection', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Sludge Collection', ['only' => ['destroy']]);
        $this->middleware('permission:Export Sludge Collection to Excel', ['only' => ['export']]);
    }
    /**
    * Display a listing of the sludge collections.
    *
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $page_title = "Sludge Collections";
        if(Auth::user()->hasRole('Treatment Plant')) {
            $treatmentPlants = TreatmentPlant::where('id', Auth::user()->treatment_plant_id)->orderBy('id')->pluck('id', 'name');
        }
        else {
            $treatmentPlants = TreatmentPlant::orderBy('id')->pluck('id', 'name');
        }
        $servprov = ServiceProvider::orderBy('company_name','asc')->pluck('company_name','id')->all();
        return view('fsm.sludge-collection.index', compact('page_title', 'treatmentPlants','servprov'));
    }

    public function getData(Request $request)
    {
        if(Auth::user()->hasRole('Treatment Plant'))
        {
            $sludgeCollection = SludgeCollection::join('fsm.applications', function($join) {
             $join->on('fsm.sludge_collections.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.sludge_collections.deleted_at')
            ->where('fsm.sludge_collections.treatment_plant_id',Auth::user()->treatment_plant_id)
            ->select('fsm.sludge_collections.*');
        }
        else if (Auth::user()->hasRole('Service Provider - Admin'))
        {
            $sludgeCollection = SludgeCollection::join('fsm.applications', function($join) {
             $join->on('fsm.sludge_collections.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.sludge_collections.deleted_at')
            ->where('fsm.sludge_collections.service_provider_id',Auth::user()->service_provider_id)
            ->select('fsm.sludge_collections.*');
        }
        else
        {
            $sludgeCollection = SludgeCollection::join('fsm.applications', function($join) {
             $join->on('fsm.sludge_collections.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.sludge_collections.deleted_at')
            ->select('fsm.sludge_collections.*');
        }



        return Datatables::of($sludgeCollection)
                ->filter(function ($query) use ($request) {

                if ($request->treatment_plant_id) {
                    $query->where('fsm.sludge_collections.treatment_plant_id', $request->treatment_plant_id);
                }
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('fsm.sludge_collections.date', [$request->date_from, $request->date_to]);;
                }
                if ($request->application_id) {
                    $query->where('fsm.sludge_collections.application_id', $request->application_id);
                }
                if ($request->servprov) {
                    $query->where('fsm.sludge_collections.service_provider_id', $request->servprov);
                }

                })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['sludge-collection.destroy', $model->id]]);

                // if (Auth::user()->can('Edit Sludge Collection')) {
                //     $content .= '<a title="Edit" href="' . action("Fsm\SludgeCollectionController@edit", [$model->id]) . '" class="btn btn-info btn-sm mb-1 '. ($model->applications->assessment_status && $model->applications->emptying_status && $model->applications->sludge_collection_status ? ' anchor-disabled' : '') . '"><i class="fa fa-edit"></i></a> ';
                // }

                if (Auth::user()->can('View Sludge Collection')) {
                    $content .= '<a title="Detail" href="' . action("Fsm\SludgeCollectionController@show", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }

                if (Auth::user()->can('View Sludge Collection History')) {
                    $content .= '<a title="History" href="' . action("Fsm\SludgeCollectionController@history", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }

                /*if (Auth::user()->can('Delete Sludge Collection')) {
                    $content .= '<a title="Delete" class="delete btn btn-danger btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }*/

                $content .= \Form::close();
                return $content;
            })
            ->editColumn('application_id',function ($model){
                return $model->applications->id??'-';
            })
            ->editColumn('date',function ($model){
                return $model->date??'-';
            })
            ->editColumn('service_provider_id',function ($model){
                return $model->applications?->service_provider()->withTrashed()->first()->company_name ?? 'Not Assigned';
            })
            ->editColumn('treatment_plant_id',function ($model){
                return $model->treatmentplants()->withTrashed()->first()->name??'-';
            })
            ->editColumn('desludging_vehicle_id',function ($model){
                return $model->emptying?->vacutug()->withTrashed()->first()->license_plate_number??'-';
            })
            ->editColumn('volume_of_sludge',function ($model){
                return $model->emptying->volume_of_sludge??'-';
            })
            ->make(true);
    }
    /**
    * Get the data for the sludge collections DataTable.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function create(int $application_id)
    {
        $page_title = "Add Sludge Collection";
        $application = Application::findOrFail($application_id)??null;
        $emptying = Emptying::where('application_id',$application_id)->latest()->first()??null;
        if ($emptying){
            $treatment_plant_id = $emptying->treatment_plant_id??null;
            $service_provider_id = $application->service_provider_id??null;
            $vacutug_id = $emptying->desludging_vehicle_id??null;
            $volume_of_sludge = $emptying->volume_of_sludge??null;
        }
        if(Auth::user()->hasRole('Treatment Plant')) {
            $treatmentPlants = TreatmentPlant::Operational()->where('id', Auth::user()->treatment_plant_id)->orderBy('id')->pluck('name', 'id');
        }
        else {
            $treatmentPlants = TreatmentPlant::Operational()->orderBy('id')->pluck('name', 'id');
        }
        $sludgeCollection = null;
        $serviceProviders = ServiceProvider::orderBy('id')->pluck('company_name', 'id');
        $applications = Application::where('emptying_status', '=', 'true')->where('sludge_collection_status',false)->orderBy('id', 'asc')->pluck('id', 'id')->all();
        $VacutugTypes = VacutugType::orderBy('id', 'asc')->pluck('capacity', 'id')->all();
        return view('fsm.sludge-collection.create', compact('page_title', 'treatmentPlants', 'serviceProviders', 'applications', 'VacutugTypes','sludgeCollection','application_id','service_provider_id','treatment_plant_id','vacutug_id','volume_of_sludge'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SludgeCollectionRequest $request)
    {
        $sludgeCollection = new SludgeCollection();
        $sludgeCollection->application_id = $request->application_id ? $request->application_id : null;
        $sludgeCollection->volume_of_sludge = $request->volume_of_sludge??null;
        $sludgeCollection->date = $request->date ? $request->date : null;
        $sludgeCollection->entry_time = $request->entry_time ? $request->entry_time : null;
        $sludgeCollection->exit_time = $request->exit_time ? $request->exit_time : null;
        $sludgeCollection->treatment_plant_id = $request->treatment_plant_id ? $request->treatment_plant_id : null;
        $sludgeCollection->service_provider_id = $request->service_provider_id??null;
        $sludgeCollection->desludging_vehicle_id = $request->desludging_vehicle_id??null;
        $sludgeCollection->user_id = Auth::user()->id;
        $sludgeCollection->save();
        if($request->application_id){
            $application = Application::find($request->application_id);
            $application->sludge_collection_status = true;
            $application->save();
        }
        return redirect('fsm/application')->with('success','Sludge collection created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sludgeCollection = SludgeCollection::find($id);

        $applications = $sludgeCollection->applications;
        $service_provider_id = $applications['service_provider_id'];
         $serviceProvider = ServiceProvider::withTrashed()
                ->where('id', $service_provider_id)
                ->first();

        $treatmentPlant = TreatmentPlant::withTrashed()
                ->where('id', $sludgeCollection->treatment_plant_id)
                ->first();

        if ($sludgeCollection) {
           if(Auth::user()->hasRole('Treatment Plant')) {
                if($sludgeCollection->treatment_plant_id != Auth::user()->treatment_plant_id) {
                    abort(403);
                }
            }

            $page_title = "Sludge Collection Details";
            return view('fsm.sludge-collection.show', compact('page_title', 'sludgeCollection', 'serviceProvider', 'treatmentPlant'));
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
        $sludgeCollection = SludgeCollection::find($id);

        if ($sludgeCollection) {
           if(Auth::user()->hasRole('Treatment Plant')) {
                if($sludgeCollection->user_id != Auth::user()->id || $sludgeCollection->treatment_plant_id != Auth::user()->treatment_plant_id) {
                    abort(403);
                }
            }
            $page_title = "Edit Sludge Collection";
           if(Auth::user()->hasRole('Treatment Plant')) {
                $treatmentPlants = TreatmentPlant::withTrashed()->where('id', Auth::user()->treatment_plant_id)->orderBy('id')->pluck('name', 'id');
            }
            else {
                $treatmentPlants = TreatmentPlant::withTrashed()->orderBy('id')->pluck('name', 'id');
            }
            $serviceProviders = ServiceProvider::orderBy('id')->pluck('company_name', 'id');
            $applications = Application::where('sludge_collection_status','true')->where('id', $sludgeCollection->applications->id)->orderBy('id', 'asc')->pluck('id', 'id')->all();
            $VacutugTypes = VacutugType::orderBy('id', 'asc')->pluck('capacity', 'id')->all();
            $treatment_plant_id = $sludgeCollection->treatment_plant_id??null;
            $volume_of_sludge = $sludgeCollection->volume_of_sludge??null;
            return view('fsm.sludge-collection.edit', compact('page_title', 'sludgeCollection', 'treatmentPlants', 'serviceProviders', 'applications', 'VacutugTypes','treatment_plant_id','volume_of_sludge'));
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
    public function update(SludgeCollectionRequest $request, $id)
    {
        $sludgeCollection = SludgeCollection::find($id);

        if ($sludgeCollection) {
           if(Auth::user()->hasRole('Treatment Plant')) {
                if($sludgeCollection->user_id != Auth::user()->id || $sludgeCollection->treatment_plant_id != Auth::user()->treatment_plant_id) {
                    abort(403);
                }
            }

            $sludgeCollection->date = $request->date ? $request->date : null;
            $sludgeCollection->entry_time = $request->entry_time? $request->entry_time : null;
            $sludgeCollection->exit_time = $request->exit_time ? $request->exit_time : null;
           if(Auth::user()->hasRole('Treatment Plant')) {
                $sludgeCollection->treatment_plant_id = Auth::user()->treatment_plant_id;
            }
            else {
                $sludgeCollection->treatment_plant_id = $request->treatment_plant_id ? $request->treatment_plant_id : null;
            }
            $sludgeCollection->service_provider_id = $request->service_provider_id ? $request->service_provider_id : null;
            $sludgeCollection->user_id = Auth::user()->id;
            $sludgeCollection->save();


            }
            return redirect('fsm/sludge-collection')->with('success','Sludge collection updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sludgeCollection = SludgeCollection::find($id);

        if ($sludgeCollection) {
           if(Auth::user()->hasRole('Treatment Plant')) {
                if($sludgeCollection->user_id != Auth::user()->id || $sludgeCollection->treatment_plant_id != Auth::user()->treatment_plant_id) {
                    abort(403);
                }
            }
            $application = Application::findOrFail($sludgeCollection->application_id);
            $application->sludge_collection_status=false;
            $application->save();
            $sludgeCollection->delete();
            return redirect('fsm/sludge-collection')->with('success','Sludge collection deleted successfully');
        } else {
            return redirect('fsm/sludge-collection')->with('error','Failed to delete sludge collection');
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
        $sludgeCollection = SludgeCollection::find($id);
        if ($sludgeCollection) {
            $page_title = "Sludge Collection History";
            return view('fsm.sludge-collection.history', compact('page_title', 'sludgeCollection'));
        } else {
            abort(404);
        }
    }


    /**
     * Export a listing of the specified resource from storage.
     *
     * @return void
     */
    public function export()
    {

        $searchData = isset($_GET['searchData']) ? $_GET['searchData'] : null;

        $application_id = $_GET['application_id'] ?? null;
        $date_from = $_GET['date_from'] ?? null;
        $date_to = $_GET['date_to'] ?? null;
        $treatment_plant_id = $_GET['treatment_plant_id'] ?? null;
        $servprov = $_GET['servprov'] ?? null;

       $columns = ['ID', 'Application ID', 'Treatment Plant', 'Sludge Volume', 'Date', 'Entry Time', 'Exit Time', 'Service Provider', 'Desluding Vechicle'];

        $query =  DB::table('fsm.sludge_collections AS sc')
        ->leftJoin('fsm.treatment_plants AS t', 't.id', '=', 'sc.treatment_plant_id')
        ->leftJoin('fsm.service_providers AS s', 's.id', '=', 'sc.service_provider_id')
        ->leftJoin('fsm.applications AS a', 'a.id', '=', 'sc.application_id')
        ->leftJoin('fsm.desludging_vehicles AS dv', 'dv.id', '=', 'sc.desludging_vehicle_id')
        ->select(
            'sc.id',
            'sc.application_id',
            'sc.treatment_plant_id',
            'sc.service_provider_id',
            's.company_name',
            't.name AS treatment_plant_name',
            'sc.volume_of_sludge',
            'sc.date', 'sc.entry_time', 'sc.exit_time','dv.license_plate_number AS desludging_vehicle_license_plate'
        )
        ->orderBy('sc.id')
        ->whereNull('sc.deleted_at');

        if (!empty($application_id)) {
            $query->where('sc.application_id', $application_id);

        }

        if (!empty($date_from) && !empty($date_to)){
            $query->whereBetween('sc.date', [$date_from, $date_to]);
        }

        if (!empty($treatment_plant_id)){
            $query->where('sc.treatment_plant_id', $treatment_plant_id);
        }

        if (!empty($servprov)){
            $query->where('sc.service_provider_id', $servprov);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Sludge Collections.CSV')
            ->addRowWithStyle($columns, $style); //Top row of excel

        $query->chunk(5000, function ($sludgeCollections) use ($writer) {

           foreach ($sludgeCollections as $sludgeCollection){
                $values = [];
                $values[] = $sludgeCollection->id??"-";
                $values[] = $sludgeCollection->application_id??"-";
                $values[] = $sludgeCollection->treatment_plant_name??"-";
                $values[] = $sludgeCollection->volume_of_sludge??"-";
                $values[] = $sludgeCollection->date??"-";
                $values[] = $sludgeCollection->entry_time??"-";
                $values[] = $sludgeCollection->exit_time??"-";
                $values[] = $sludgeCollection->company_name?? "-";
                $values[] = $sludgeCollection->desludging_vehicle_license_plate??"-";
                $writer->addRow($values);
            }
        });

        $writer->close();
    }
}
