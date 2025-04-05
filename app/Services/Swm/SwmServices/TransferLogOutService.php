<?php

namespace App\Services\Swm\SwmServices;

use App\Classes\FormField;
use App\Helpers\Common;
use App\Models\Fsm\Application;
use App\Models\Swm\LandfillSite;
use App\Models\Swm\TransferLogIn;
use App\Models\Swm\TransferLogOut;
use App\Models\Swm\TransferStation;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;

class TransferLogOutService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new TransferLogOut object.
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
                label: 'Source (Transfer Station)',
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
                label: 'Destination (Landfill Site)',
                labelFor: 'landfill_site_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'landfill_site_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: LandfillSite::getLandfillSites()->pluck('name','id')->toArray(),
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
                label: 'Date & Time',
                labelFor: 'date_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date_time',
                inputId: 'date_time',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
        ];
        $this->indexAction = route('transfer-log-out.index');
        $this->createFormAction = route('transfer-log-out.store');
        $this->exportRoute = route('transfer-log-out.export');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Source (Transfer Station)',
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
                    label: 'Destination (Landfill Site)',
                    labelFor: 'landfill_site_id',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'landfill_site_id',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: LandfillSite::getLandfillSites()->pluck('name','id')->toArray(),
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
            ],
            [
            new FormField(
                label: 'Received?',
                labelFor: 'received',
                labelClass: 'col-sm-1 control-label',
                inputType: 'select',
                inputId: 'received',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [true=>'Yes',false=>'No'],
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
     * Get action/route for exporting transfer log outs.
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
    public function getShowFormFields($transferLogOut)
    {
        $this->showFormFields = [
            new FormField(
                label: 'Source (Transfer Station)',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogOut->transfer_station->name??"-"
            ),
            new FormField(
                label: 'Destination (Landfill Site)',
                labelFor: 'landfill_site_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'landfill_site_id',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogOut->landfill_site->name??"-"
            ),
            new FormField(
                label: 'Type of Waste',
                labelFor: 'type_of_waste',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'type_of_waste',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogOut->type_of_waste
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'volume',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogOut->volume
            ),
            new FormField(
                label: 'Date & Time',
                labelFor: 'date_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'date_time',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferLogOut->date_time
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($transferLogOut){
        $this->editFormFields = [
            new FormField(
                label: 'Source (Transfer Station)',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                selectedValue: $transferLogOut->transfer_station_id
            ),
            new FormField(
                label: 'Destination (Landfill Site)',
                labelFor: 'landfill_site_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'landfill_site_id',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: LandfillSite::getLandfillSites()->pluck('name','id')->toArray(),
                selectedValue: $transferLogOut->landfill_site_id
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
                selectedValue: $transferLogOut->type_of_waste
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'volume',
                inputValue: $transferLogOut->volume,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Date & Time',
                labelFor: 'date_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date_time',
                inputId: 'date_time',
                inputValue: $transferLogOut->date_time,
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
    public function getEditFormAction($transferLogOut){
        $this->editFormAction = route('transfer-log-out.update',$transferLogOut);
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
     * @return TransferLogOut[]|Collection
     */
    public function getAllTransferLogOuts(){
        return TransferLogOut::latest()->whereNull('deleted_at');
    }

    /**
     * Export transfer log outs.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $transfer_station_id = $request->transfer_station_id;
        $landfill_site_id = $request->landfill_site_id;
        $type_of_waste = $request->type_of_waste;
        $verification = $request->verification;
        $columns = [
            'ID',
            'Transfer Station',
            'Landfill Site',
            'Type of Waste',
            'Volume',
            'Date & Time',
            'Received',
            'Received Date & Time'
        ];
        $query = TransferLogOut::all()->whereNull('deleted_at')->toQuery();
        if (!empty($transfer_station_id)) {
            $query->where('transfer_station_id',$transfer_station_id);
        }
        if (!empty($landfill_site_id)) {
            $query->where('landfill_site_id',$landfill_site_id);
        }
        if (!empty($type_of_waste)) {
            $query->where('type_of_waste',$type_of_waste);
        }
        if (!empty($verification)) {
            $query->where('verification',$verification);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Transfer-Log-Outs.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($transfer_log_outs) use ($writer) {
            foreach($transfer_log_outs as $transfer_log_out) {
                $values = [];
                $values[] = $transfer_log_out->id;
                $values[] = $transfer_log_out->transfer_station->name;
                $values[] = $transfer_log_out->landfill_site->name;
                $values[] = $transfer_log_out->type_of_waste;
                $values[] = $transfer_log_out->volume;
                $values[] = $transfer_log_out->date_time;
                $values[] = $transfer_log_out->received?"Yes":"No";
                $values[] = $transfer_log_out->received_datetime;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }


}
