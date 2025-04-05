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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;

class SwmRouteService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new Route object.
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
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->whereNull('deleted_at')->pluck('name','id')->toArray(),
                selectedValue: null),
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
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ["A"=>"A","B"=>"B","C"=>"C"],
                selectedValue: null),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'time',
                inputValue: null,
                inputClass: 'form-control',
                ),
        ];
        $this->indexAction = route('route.index');
        $this->createFormAction = route('route.store');
        $this->exportRoute = route('route.export');

        $this->filterFormFields = [[
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
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-1',
                inputType: 'select',
                inputId: 'type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ["A"=>"A","B"=>"B","C"=>"C"],
                selectedValue: null)
        ]];
    }

    /**
     * Get form fields for creating route.
     *
     * @return array
     */
    public function getCreateFormFields(){
        return $this->createFormFields;
    }

    /**
     * Get action/route for index page of Routes.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for exporting routes.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    /**
     * Get form fields for showing route.
     *
     * @return array
     */
    public function getShowFormFields($route){
        $this->showFormFields = [
            new FormField(
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'service_provider_id',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $route->service_provider->name),
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'name',
                inputClass: 'form-control',
                labelValue: $route->name,
            ),
            new FormField(
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: ["A"=>"A","B"=>"B","C"=>"C"],
                labelValue: $route->type),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'time',
                inputClass: 'form-control',
                labelValue: $route->time,
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing route.
     *
     * @return array
     */
    public function getEditFormFields($route){
        $this->editFormFields = [
            new FormField(
                label: 'Service Provider',
                labelFor: 'service_provider_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'service_provider_id',
                inputValue: $route->service_provider_id,
                inputClass: 'form-control',
                selectValues: ServiceProvider::all()->whereNull('deleted_at')->pluck('name','id')->toArray(),
                selectedValue: $route->service_provider_id),
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: $route->name,
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Type',
                labelFor: 'type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type',
                inputValue: $route->type,
                inputClass: 'form-control',
                selectValues: ["A"=>"A","B"=>"B","C"=>"C"],
                selectedValue: null),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'time',
                inputValue: $route->time,
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
    public function getEditFormAction($route){
        $this->editFormAction = route('route.update',$route);
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
     * Get all routes.
     *
     * @return array
     */
    public function getAllRoutes(){
        return Route::latest()->whereNull('deleted_at');
    }

    /**
     * Export routes.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $service_provider_id = $request->service_provider_id;
        $name = $request->name;
        $type = $request->type;
        $time = $request->time;
        $columns = [
            'ID',
            'Service Provider',
            'Name',
            'Type',
            'Time'
        ];
        $query = ServiceProvider::all()->whereNull('deleted_at')->toQuery();
        if (!empty($service_provider_id)) {
            $query->where('service_provider_id',$service_provider_id);
        }
        if (!empty($name)) {
            $query->where('name',$name);
        }
        if (!empty($type)) {
            $query->where('type',$type);
        }
        if (!empty($time)) {
            $query->where('time',$time);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Routes.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($routes) use ($writer) {
            foreach($routes as $route) {
                $values = [];
                $values[] = $route->id;
                $values[] = $route->service_provider->name;
                $values[] = $route->name;
                $values[] = $route->type;
                $values[] = $route->time;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }




}
