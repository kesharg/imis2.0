<?php

namespace App\Services\Swm\SwmServices;

use App\Classes\FormField;
use App\Helpers\Common;
use App\Models\Fsm\Application;
use App\Models\Swm\TransferStation;
use App\Models\Swm\WasteRecycle;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;

class WasteRecycleService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new WasteRecycle object.
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
                label: 'Waste Type',
                labelFor: 'waste_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'waste_type',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getWasteTypes(),
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
            new FormField(
                label: 'Rate',
                labelFor: 'rate',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'rate',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Total Price',
                labelFor: 'total_price',
                labelClass: 'col-sm-4 control-label',
                inputType: 'hidden',
                inputId: 'total_price',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null,
                placeholder: '-',
                disabled: true
            ),
        ];
        $this->indexAction = route('waste-recycle.index');
        $this->createFormAction = route('waste-recycle.store');
        $this->exportRoute = route('waste-recycle.export');

        $this->filterFormFields = [
            [
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
                    label: 'Waste Type',
                    labelFor: 'waste_type',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'waste_type',
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
     * Get action/route for exporting waste recycles.
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
    public function getShowFormFields($wasteRecycle)
    {
        $this->showFormFields = [
            new FormField(
                label: 'Transfer Station',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'transfer_station_id',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->transfer_station->name??'-'
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'volume',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->volume
            ),
            new FormField(
                label: 'Waste Type',
                labelFor: 'waste_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'waste_type',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->waste_type
            ),
            new FormField(
                label: 'Date & Time',
                labelFor: 'date_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'date_time',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->date_time
            ),
            new FormField(
                label: 'Rate',
                labelFor: 'rate',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'rate',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->rate
            ),
            new FormField(
                label: 'Total Price',
                labelFor: 'total_price',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'total_price',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $wasteRecycle->total_price
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($wasteRecycle){
        $this->editFormFields = [
            new FormField(
                label: 'Transfer Station',
                labelFor: 'transfer_station_id',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'transfer_station_id',
                inputValue: $wasteRecycle->transfer_station_id,
                inputClass: 'form-control',
                selectValues: TransferStation::getTransferStations()->pluck('name','id')->toArray(),
                selectedValue: $wasteRecycle->transfer_station_id,
            ),
            new FormField(
                label: 'Volume',
                labelFor: 'volume',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'volume',
                inputValue: $wasteRecycle->volume,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Waste Type',
                labelFor: 'waste_type',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'waste_type',
                inputValue: $wasteRecycle->waste_type,
                inputClass: 'form-control',
                selectValues: Common::getWasteTypes(),
                selectedValue: $wasteRecycle->waste_type
            ),
            new FormField(
                label: 'Date & Time',
                labelFor: 'date_time',
                labelClass: 'col-sm-4 control-label',
                inputType: 'date_time',
                inputId: 'date_time',
                inputValue: $wasteRecycle->date_time,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Rate',
                labelFor: 'rate',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'rate',
                inputValue: $wasteRecycle->rate,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Total Price',
                labelFor: 'total_price',
                labelClass: 'col-sm-4 control-label',
                inputType: 'hidden',
                inputId: 'total_price',
                inputValue: $wasteRecycle->total_price,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null,
                placeholder: '-',
                disabled: true
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
    public function getEditFormAction($wasteRecycle){
        $this->editFormAction = route('waste-recycle.update',$wasteRecycle);
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
     * @return WasteRecycle[]|Collection
     */
    public function getAllTransferLogOuts()
    {
        return WasteRecycle::latest()->whereNull('deleted_at');
    }

    /**
     * Export waste recycles.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $transfer_station_id = $request->transfer_station_id;
        $rate = $request->rate;
        $total_price = $request->total_price;
        $columns = [
            'ID',
            'Transfer Station',
            'Volume',
            'Waste Type',
            'Date & Time',
            'Rate',
            'Total Price'
        ];
        $query = WasteRecycle::all()->whereNull('deleted_at')->toQuery();
        if (!empty($transfer_station_id)) {
            $query->where('transfer_station_id',$transfer_station_id);
        }
        if (!empty($rate)) {
            $query->where('rate',$rate);
        }
        if (!empty($total_price)) {
            $query->where('total_price',$total_price);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Waste-Recycles.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($waste_recycles) use ($writer) {
            foreach($waste_recycles as $waste_recycle) {
                $values = [];
                $values[] = $waste_recycle->id;
                $values[] = $waste_recycle->transfer_station->name;
                $values[] = $waste_recycle->volume;
                $values[] = $waste_recycle->waste_type;
                $values[] = $waste_recycle->date_time;
                $values[] = $waste_recycle->rate;
                $values[] = $waste_recycle->total_price;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }

}
