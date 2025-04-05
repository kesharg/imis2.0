<?php

namespace App\Services\Swm\SwmServices;

use App\Classes\FormField;
use App\Models\Fsm\Application;
use App\Models\Swm\Route;
use App\Models\Swm\ServiceArea;
use App\Models\Swm\ServiceProvider;
use App\Models\Swm\TransferStation;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;

class SwmServiceAreaService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new ServiceArea object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/

        $this->createFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: null,
                inputClass: 'form-control',
                ),
            new FormField(
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->whereNull('deleted_at')->pluck('name','id')->toArray(),
                selectedValue: null
            ),
            new FormField(
                label: 'Set the Service area',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'poly_geom_drawer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
            ),

        ];
        $this->indexAction = route('service-area.index');
        $this->createFormAction = route('service-area.store');
        $this->exportRoute = route('service-area.export');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Service Provider',
                    labelFor: 'service_provider_id',
                    labelClass: 'col-sm-1',
                    inputType: 'select',
                    inputId: 'service_provider_id',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: ServiceProvider::all()->whereNull('deleted_at')->pluck('name','id')->toArray(),
                    selectedValue: null),
                ]
        ];
    }

    /**
     * Get form fields for creating service area.
     *
     * @return array
     */
    public function getCreateFormFields(){
        return $this->createFormFields;
    }

    /**
     * Get action/route for index page of Service areas.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for exporting service areas.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    /**
     * Get form fields for showing service area.
     *
     * @return array
     */
    public function getShowFormFields($serviceArea){
        $this->showFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'name',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $serviceArea->name
            ),
            new FormField(
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'service_provider_id',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $serviceArea->service_provider->name
            ),
            new FormField(
                label: 'Service Area',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'geom_viewer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.service_areas WHERE id = $serviceArea->id")[0]->geom,
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing service area.
     *
     * @return array
     */
    public function getEditFormFields($serviceArea){
        $this->editFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: $serviceArea->name,
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputValue: $serviceArea->service_provider_id,
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->whereNull('deleted_at')->pluck('name','id')->toArray(),
                selectedValue: $serviceArea->service_provider_id
            ),
            new FormField(
                label: 'Set the Service area',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'poly_geom_drawer',
                inputId: 'geom',
                inputValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.service_areas WHERE id = $serviceArea->id")[0]->geom,
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
    public function getEditFormAction($serviceArea){
        $this->editFormAction = route('service-area.update',$serviceArea);
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
     * Get all the service areas.
     *
     *
     * @return ServiceArea[]|Collection
     */
    public function getAllServiceAreas(){
        return ServiceArea::latest()->whereNull('deleted_at');
    }

    /**
     * Export service areas.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $service_provider_id = $request->service_provider_id;

        $columns = [
            'ID',
            'Name',
            'Service Provider'
        ];
        $query = ServiceArea::all()->whereNull('deleted_at')->toQuery();
        if (!empty($service_provider_id)) {
            $query->where('service_provider_id',$service_provider_id);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Service-Areas.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($serviceAreas) use ($writer) {
            foreach($serviceAreas as $serviceArea) {
                $values = [];
                $values[] = $serviceArea->id;
                $values[] = $serviceArea->name;
                $values[] = $serviceArea->service_provider->name??'-';
                $writer->addRow($values);
            }
        });

        $writer->close();
    }




}
