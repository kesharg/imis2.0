<?php

namespace App\Services\Swm\SwmServices;

use App\Classes\FormField;
use App\Models\Fsm\Application;
use App\Models\Swm\Route;
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
use Yajra\DataTables\Contracts\DataTable;

class SwmServiceProviderService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new ServiceProvider object.
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
                label: 'Start date',
                labelFor: 'start_date',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date',
                inputId: 'start_date',
                inputValue: null,
                inputClass: 'form-control',
            ),
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
        $this->indexAction = route('service-provider.index');
        $this->createFormAction = route('service-provider.store');
        $this->exportRoute = route('service-provider.export');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Name',
                    labelFor: 'name',
                    labelClass: 'col-sm-1',
                    inputType: 'text',
                    inputId: 'name',
                    inputValue: null,
                    inputClass: 'form-control',
                ),
                new FormField(
                    label: 'Start date',
                    labelFor: 'start_date',
                    labelClass: 'col-sm-1',
                    inputType: 'date',
                    inputId: 'start_date',
                    inputValue: null,
                    inputClass: 'form-control',
                ),
            ]
        ];
    }

    /**
     * Get form fields for creating service provider.
     *
     * @return array
     */
    public function getCreateFormFields(){
        return $this->createFormFields;
    }

    /**
     * Get action/route for index page of Service provider.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for exporting service providers.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    /**
     * Get form fields for showing service provider.
     *
     * @return array
     */
    public function getShowFormFields($serviceProvider){
        $this->showFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'name',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $serviceProvider->name
            ),
            new FormField(
                label: 'Start date',
                labelFor: 'start_date',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'start_date',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $serviceProvider->start_date
            ),
            new FormField(
                label: 'Location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'geom_viewer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.service_providers WHERE id = $serviceProvider->id")[0]->geom
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($serviceProvider){
        $this->editFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-1',
                inputType: 'text',
                inputId: 'name',
                inputValue: $serviceProvider->name,
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Start date',
                labelFor: 'start_date',
                labelClass: 'col-sm-1',
                inputType: 'date',
                inputId: 'start_date',
                inputValue: Carbon::parse($serviceProvider->start_date)->format('Y-m-d'),
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-1',
                inputType: 'point_geom_drawer',
                inputId: 'geom',
                inputValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.service_providers WHERE id = $serviceProvider->id")[0]->geom,
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
    public function getEditFormAction($serviceProvider){
        $this->editFormAction = route('service-provider.update',$serviceProvider);
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
     * Get all service providers.
     *
     * @return array
     */
    public function getAllServiceProviders(){
        return ServiceProvider::latest()->whereNull('deleted_at');
    }

    /**
     * Export service providers.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $name = $request->name;
        $start_date = $request->start_date;
        $columns = [
            'ID',
            'Name',
            'Start Date'
        ];
        $query = ServiceProvider::all()->whereNull('deleted_at')->toQuery();
        if (!empty($name)) {
            $query->where('name',$name);
        }
        if (!empty($start_date)) {
            $query->where('start_date',$start_date);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Service-Providers.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($serviceProviders) use ($writer) {
            foreach($serviceProviders as $serviceProvider) {
                $values = [];
                $values[] = $serviceProvider->id;
                $values[] = $serviceProvider->name;
                $values[] = $serviceProvider->start_date;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }




}
