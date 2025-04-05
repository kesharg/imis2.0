<?php

namespace App\Services\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Models\Fsm\Application;
use App\Models\LayerInfo\Ward;
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

class TransferStationService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createPartialForm,$createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


    /**
     * Constructs a new TransferStation object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/

        $this->createPartialForm = 'swm.transfer-station.partial-form';
        $this->indexAction = route('transfer-station.index');
        $this->exportRoute = route('transfer-station.export');
        $this->createFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'ward',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                selectedValue: null
            ),
            new FormField(
                label: 'Separation Facility',
                labelFor: 'separation_facilty',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'separation_facility',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [true=>"Yes",false=>"No"],
                selectedValue: null
            ),
            new FormField(
                label: 'Area',
                labelFor: 'area',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'area',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'capacity',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'point_geom_drawer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
        ];
        $this->createFormAction = route('transfer-station.store');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Name',
                    labelFor: 'name',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'text',
                    inputId: 'name',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: [],
                    selectedValue: null
                ),
                new FormField(
                    label: 'Ward',
                    labelFor: 'ward',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'ward',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                    selectedValue: null
                ),
                new FormField(
                    label: 'Separation Facility',
                    labelFor: 'separation_facility',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'separation_facility',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: [true=>"Yes",false=>"No"],
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
     * Get action/route for exporting transfer stations.
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
    public function getShowFormFields($transferStation){
        $this->showFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'name',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferStation->name
            ),
            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'ward',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferStation->ward
            ),
            new FormField(
                label: 'Separation Facility',
                labelFor: 'separation_facilty',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'separation_facility',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferStation->separation_facility?'Yes':'No'
            ),
            new FormField(
                label: 'Area',
                labelFor: 'area',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'area',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferStation->area
            ),
            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'capacity',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $transferStation->capacity
            ),
            new FormField(
                label: 'Location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'geom_viewer',
                inputId: 'geom',
                inputClass: 'form-control',
                labelValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.transfer_stations WHERE id = $transferStation->id")[0]->geom
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($transferStation){
        $this->editFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: $transferStation->name,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'ward',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                selectedValue: $transferStation->ward
            ),
            new FormField(
                label: 'Separation Facility',
                labelFor: 'separation_facilty',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'separation_facility',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [true=>"Yes",false=>"No"],
                selectedValue: $transferStation->separation_facility
            ),
            new FormField(
                label: 'Area',
                labelFor: 'area',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'area',
                inputValue: $transferStation->area,
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'capacity',
                inputValue: $transferStation->capacity,
                inputClass: 'form-control',
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'point_geom_drawer',
                inputId: 'geom',
                inputValue:DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.transfer_stations WHERE id = $transferStation->id")[0]->geom,
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
    public function getEditFormAction($transferStation){
        $this->editFormAction = route('transfer-station.update',$transferStation);
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
     * @return TransferStation[]|Collection
     */
    public function getAllTransferStations()
    {
        return TransferStation::latest()->whereNull('deleted_at');
    }

    /**
     * Export transfer stations.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $name = $request->name;
        $ward = $request->ward;
        $separation_facility = $request->separation_facility;
        $columns = [
            'ID',
            'Name',
            'Ward',
            'Separation Facility',
            'Area',
            'Capacity',
        ];
        $query = TransferStation::all()->whereNull('deleted_at')->toQuery();
        if (!empty($name)) {
            $query->where('name',$name);
        }
        if (!empty($ward)) {
            $query->where('ward',$ward);
        }
        if (!empty($separation_facility)) {
            $query->where('separation_facility',$separation_facility);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Transfer-Stations.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($transfer_stations) use ($writer) {
            foreach($transfer_stations as $transfer_station) {
                $values = [];
                $values[] = $transfer_station->id;
                $values[] = $transfer_station->name;
                $values[] = $transfer_station->ward;
                $values[] = $transfer_station->separation_facility?"Yes":"No";
                $values[] = $transfer_station->area;
                $values[] = $transfer_station->capacity;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }


}
