<?php

namespace App\Services\Swm\SwmServices;

use App\Classes\FormField;
use App\Helpers\Common;
use App\Models\Fsm\Application;
use App\Models\Swm\Route;
use App\Models\Swm\TransferLogIn;
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
use Yajra\DataTables\Contracts\DataTable;

class TransferLogInService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new TransferLogIn object.
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
                label: 'Transfer Station',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                selectedValue: null
            ),
            new FormField(
                label: 'Type of Waste',
                labelFor: 'type_of_waste',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type_of_waste',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getWasteTypes(),
                selectedValue: null
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'volume',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Date',
                labelFor: 'date',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date',
                inputId: 'date',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'time',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
        ];
        $this->indexAction = route('transfer-log-in.index');
        $this->createFormAction = route('transfer-log-in.store');
        $this->exportRoute = route('transfer-log-in.export');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Route',
                    labelFor: 'route_id',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'route_id',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                    selectedValue: null
                ),
                new FormField(
                    label: 'Transfer Station',
                    labelFor: 'transfer_station_id',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'transfer_station_id',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                    selectedValue: null
                ),
                new FormField(
                    label: 'Type of Waste',
                    labelFor: 'type_of_waste',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'type_of_waste',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: Common::getWasteTypes(),
                    selectedValue: null
                ),
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
     * Get action/route for exporting transfer log ins.
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
    public function getShowFormFields($transferLogIn){
        $this->showFormFields = [
            new FormField(
                label: 'Route',
                labelFor: 'route_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'route_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                labelValue: $transferLogIn->route->name??"-"
            ),
            new FormField(
                label: 'Transfer Station',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                labelValue: $transferLogIn->transfer_station->name??"-"
            ),
            new FormField(
                label: 'Type of Waste',
                labelFor: 'type_of_waste',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'type_of_waste',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getWasteTypes(),
                labelValue: $transferLogIn->type_of_waste
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'volume',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogIn->volume
            ),
            new FormField(
                label: 'Date',
                labelFor: 'date',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'date',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogIn->date
            ),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'time',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: Carbon::parse($transferLogIn->time)->format('h:s A')
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($transferLogIn){
        $this->editFormFields = [
            new FormField(
                label: 'Route',
                labelFor: 'route_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'route_id',
                inputClass: 'form-control',
                selectValues: Route::getRoutes()->pluck('name','id')->toArray(),
                selectedValue: $transferLogIn->route_id,
            ),
            new FormField(
                label: 'Transfer Station',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                selectedValue: $transferLogIn->transfer_station_id
            ),
            new FormField(
                label: 'Type of Waste',
                labelFor: 'type_of_waste',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'type_of_waste',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getWasteTypes(),
                selectedValue: $transferLogIn->type_of_waste
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'volume',
                inputValue: $transferLogIn->volume,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Date',
                labelFor: 'date',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date',
                inputId: 'date',
                inputValue: $transferLogIn->date,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Time',
                labelFor: 'time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'time',
                inputId: 'time',
                inputValue: $transferLogIn->time,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
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
    public function getEditFormAction($transferLogIn){
        $this->editFormAction = route('transfer-log-in.update',$transferLogIn);
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
     * @return TransferLogIn[]|Collection
     */
    public function getAllTransferLogIns(){
        return TransferLogIn::latest()->whereNull('deleted_at');
    }

    /**
     * Export transfer log ins.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $route_id = $request->route_id;
        $transfer_station_id = $request->transfer_station_id;
        $type_of_waste = $request->type_of_waste;
        $columns = [
            'ID',
            'Route',
            'Transfer Station',
            'Type of Waste',
            'Volume',
            'Date',
            'Time',
        ];
        $query = TransferLogIn::all()->whereNull('deleted_at')->toQuery();
        if (!empty($route_id)) {
            $query->where('route_id',$route_id);
        }
        if (!empty($transfer_station_id)) {
            $query->where('transfer_station_id',$transfer_station_id);
        }
        if (!empty($type_of_waste)) {
            $query->where('type_of_waste',$type_of_waste);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Transfer-Log-In.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($transfer_log_ins) use ($writer) {
            foreach($transfer_log_ins as $transfer_log_in) {
                $values = [];
                $values[] = $transfer_log_in->id;
                $values[] = $transfer_log_in->route->name;
                $values[] = $transfer_log_in->transfer_station->name;
                $values[] = $transfer_log_in->type_of_waste;
                $values[] = $transfer_log_in->volume;
                $values[] = $transfer_log_in->date;
                $values[] = $transfer_log_in->time;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }




}
