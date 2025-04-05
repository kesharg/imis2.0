<?php

namespace App\Services\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Helpers\Common;
use App\Models\Fsm\Application;
use App\Models\LayerInfo\Ward;
use App\Models\Swm\CollectionPoint;
use App\Models\Swm\Route;
use App\Models\Swm\ServiceArea;
use App\Models\Swm\ServiceProvider;
use App\Models\Swm\TransferStation;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionPointService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createPartialForm,$createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new LandfillSite object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/

        $this->createPartialForm = 'swm.collection-point.partial-form';
        $this->indexAction = route('collection-point.index');
        $this->exportRoute = route('collection-point.export');
        $this->createFormFields = [
            new FormField(
                label: 'Route',
                labelFor: 'route_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'route_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                selectedValue: null
            ),

            new FormField(
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointTypes(),
                selectedValue: null),

            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'capacity',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'ward',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                selectedValue: null),

            new FormField(
                label: 'Service type',
                labelFor: 'service_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointServiceType(),
                selectedValue: null),

            new FormField(
                label: 'Service provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->pluck("name","id")->toArray(),
                selectedValue: null,
                hidden: true),

            new FormField(
                label: 'Household Served',
                labelFor: 'household_served',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'household_served',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'status',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointStatus(),
                selectedValue: null),

            new FormField(
                label: 'Collection Time',
                labelFor: 'collection_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'collection_time',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Service Area',
                labelFor: 'service_area_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_area_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ServiceArea::all()->pluck('name','id')->toArray(),
                selectedValue: null),

            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'point_geom_drawer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
                ),

        ];
        $this->createFormAction = route('collection-point.store');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Route',
                    labelFor: 'route_id',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'route_id',
                    inputValue: null,
                    selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                    selectedValue: null
                ),

                new FormField(
                    label: 'Type',
                    labelFor: 'type',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'type',
                    inputValue: null,
                    selectValues: Common::getCollectionPointTypes(),
                    selectedValue: null),

                new FormField(
                    label: 'Ward',
                    labelFor: 'ward',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'ward',
                    inputValue: null,
                    selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                    selectedValue: null),
                ],[

                new FormField(
                    label: 'Service type',
                    labelFor: 'service_type',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'service_type',
                    inputValue: null,
                    selectValues: Common::getCollectionPointServiceType(),
                    selectedValue: null),

                new FormField(
                    label: 'Service provider',
                    labelFor: 'service_provider_id',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'service_provider_id',
                    inputValue: null,
                    selectValues: ServiceProvider::all()->pluck("name","id")->toArray(),
                    selectedValue: null,
                    hidden: true),

                new FormField(
                    label: 'Status',
                    labelFor: 'status',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'status',
                    inputValue: null,
                    selectValues: Common::getCollectionPointStatus(),
                    selectedValue: null),
                ],[

                new FormField(
                    label: 'Service Area',
                    labelFor: 'service_area_id',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'service_area_id',
                    inputValue: null,
                    selectValues: ServiceArea::all()->pluck('name','id')->toArray(),
                    selectedValue: null),
            ]
        ];
    }

    /**
     * Get form fields for creating collection point.
     *
     * @return array
     */
    public function getCreateFormFields(){
        return $this->createFormFields;
    }

    /**
     * Get action/route for index page of Applications.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for exporting collection points.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    /**
     * Get form fields for showing collection point.
     *
     * @return array
     */
    public function getShowFormFields($collectionPoint){
        $this->showFormFields = [
            new FormField(
                label: 'Route',
                labelFor: 'route_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'route_id',
                labelValue: $collectionPoint->route->name??'-'
            ),

            new FormField(
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'type',
                labelValue: $collectionPoint->type
                ),

            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'capacity',
                labelValue: $collectionPoint->capacity
            ),

            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'ward',
                labelValue: $collectionPoint->ward
                ),

            new FormField(
                label: 'Service type',
                labelFor: 'service_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'service_type',
                labelValue: $collectionPoint->service_type
            ),

            new FormField(
                label: 'Service provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'service_provider_id',
                labelValue: $collectionPoint->service_provider->name??'-'
            ),

            new FormField(
                label: 'Household Served',
                labelFor: 'household_served',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'household_served',
                labelValue: $collectionPoint->household_served
                ),

            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'status',
                labelValue: $collectionPoint->status
                ),

            new FormField(
                label: 'Collection Time',
                labelFor: 'collection_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'collection_time',
                labelValue: Carbon::parse($collectionPoint->collection_time)->format('h:i A')
                ),

            new FormField(
                label: 'Service Area',
                labelFor: 'service_area_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'service_area_id',
                labelValue: $collectionPoint->service_area->name??"-"
                ),

            new FormField(
                label: 'Location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'geom_viewer',
                inputId: 'geom',
                labelValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.collection_points WHERE id = $collectionPoint->id")[0]->geom
            ),

        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($collectionPoint){
        $this->editFormFields = [
            new FormField(
                label: 'Route',
                labelFor: 'route_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'route_id',
                inputClass: 'form-control',
                selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                selectedValue: $collectionPoint->route_id
            ),

            new FormField(
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type',
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointTypes(),
                selectedValue: $collectionPoint->type),

            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'capacity',
                inputValue: $collectionPoint->capacity,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'ward',
                inputClass: 'form-control',
                selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                selectedValue: $collectionPoint->ward),

            new FormField(
                label: 'Service type',
                labelFor: 'service_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_type',
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointServiceType(),
                selectedValue: $collectionPoint->service_type),

            new FormField(
                label: 'Service provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->pluck("name","id")->toArray(),
                selectedValue: $collectionPoint->service_provider_id,
                hidden: true),

            new FormField(
                label: 'Household Served',
                labelFor: 'household_served',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'household_served',
                inputValue: $collectionPoint->household_served,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'status',
                inputClass: 'form-control',
                selectValues: Common::getCollectionPointStatus(),
                selectedValue: $collectionPoint->status),

            new FormField(
                label: 'Collection Time',
                labelFor: 'collection_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'collection_time',
                inputValue: Carbon::parse($collectionPoint->collection_time)->format('H:i'),
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null),

            new FormField(
                label: 'Service Area',
                labelFor: 'service_area_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_area_id',
                inputClass: 'form-control',
                selectValues: ServiceArea::all()->pluck('name','id')->toArray(),
                selectedValue: $collectionPoint->service_area_id),

            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'point_geom_drawer',
                inputId: 'geom',
                inputValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.collection_points WHERE id = $collectionPoint->id")[0]->geom,
                inputClass: 'form-control',
            ),

        ];
        return $this->editFormFields;
    }

    /**
     * Get action/route for create form.
     *
     * @return String
     */
    public function getCreateFormAction(){
        return $this->createFormAction;
    }

    /**
     * Get action/route for edit form.
     *
     * @return String
     */
    public function getEditFormAction($collectionPoint){
        $this->editFormAction = route('collection-point.update',$collectionPoint);
        return $this->editFormAction;
    }

    /**
     * Get form fields for filter.
     *
     * @return array
     */
    public function getFilterFormFields(){
        return $this->filterFormFields;
    }

    /**
     * Get all the collection points.
     *
     *
     * @return CollectionPoint[]|Collection
     */
    public function getAllCollectionPoints()
    {
        return CollectionPoint::latest()->whereNull('deleted_at');
    }

    /**
     * Export collection points.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $route_id = $request->route_id;
        $type = $request->type;
        $ward = $request->ward;
        $service_type = $request->service_type;
        $status = $request->status;
        $columns = [
            'ID',
            'Route',
            'Type',
            'Capacity',
            'Ward',
            'Service Area',
            'Service Type',
            'Service Provider',
            'Household Served',
            'Status',
            'Collection Time',
        ];
        $query = CollectionPoint::all()->whereNull('deleted_at')->toQuery();
        if (!empty($route_id)) {
            $query->where('route_id',$route_id);
        }
        if (!empty($type)) {
            $query->where('type',$type);
        }
        if (!empty($ward)) {
            $query->where('ward',$ward);
        }
        if (!empty($service_type)) {
            $query->where('service_type',$service_type);
        }
        if (!empty($status)) {
            $query->where('status',$status);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Collection-Points.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($collection_points) use ($writer) {
            foreach($collection_points as $collection_point) {
                $values = [];
                $values[] = $collection_point->id;
                $values[] = $collection_point->route->name??"-";
                $values[] = $collection_point->type;
                $values[] = $collection_point->capacity;
                $values[] = $collection_point->ward;
                $values[] = $collection_point->service_area->name??"-";
                $values[] = $collection_point->service_type;
                $values[] = $collection_point->service_provider->name??"-";
                $values[] = $collection_point->household_served;
                $values[] = $collection_point->status;
                $values[] = Carbon::parse($collection_point->collection_time)->format('h:i A');
                $writer->addRow($values);
            }
        });

        $writer->close();
    }


}
