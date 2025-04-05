<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\Fsm;
use DB;
use App\Classes\FormField;
use App\Models\Fsm\EmployeeInfo;
use App\Models\Fsm\Emptying;
use App\Models\Fsm\TreatmentPlant;
use App\Models\Fsm\VacutugType;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use DateTimeZone;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\BuildingInfo\Building;
use App\Models\Fsm\Application;
use App\Models\Fsm\Containment;
use App\Models\Swm\Route;
use App\Models\UtilityInfo\Roadline;
use Carbon\Carbon;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Venturecraft\Revisionable\Revision;
use DataTables;

class EmptyingService
{

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $createRoute, $exportRoute;
    protected $createPartialForm, $createFormFields, $createFormAction;
    protected $showFormFields, $editFormFields, $filterFormFields;

    /**
     * Constructs a new Emptying object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/

        $this->createPartialForm = 'fsm.emptying.partial-form';
        $this->createFormAction = route('emptying.store');
        $this->indexAction = route('emptying.index');
        $this->createRoute = route('emptying.create');
        $this->exportRoute = route('emptying.export');

        $this->filterFormFields = [
            [
                new FormField(
                    label: 'Application ID',
                    labelFor: 'application_id',
                    labelClass: 'control-label col-md-2',
                    inputType: 'number',
                    inputId: 'application_id',
                    placeholder: 'Application ID',
                ),
                // new FormField(
                //     label: 'House Number',
                //     labelFor: 'house_number',
                //     labelClass: 'control-label col-md-2',
                //     inputType: 'text',
                //     inputId: 'house_number',
                //     selectValues: [],
                //     required: true
                // ),
                new FormField(
                    label: 'Emptied Date',
                    labelFor: 'emptied_date',
                    labelClass: 'control-label col-md-2',
                    inputType: 'text',
                    inputId: 'emptied_date',
                    placeholder: 'Emptied Date',
                ),

            ],
            [
                new FormField(
                    label: 'Containment ID',
                    labelFor: 'containment_id',
                    labelClass: 'control-label col-md-2',
                    inputType: 'text',
                    inputId: 'containment_id',
                    placeholder: 'Containment ID',
                ),
                new FormField(
                    label: 'Date From',
                    labelFor: 'date_from',
                    labelClass: 'control-label col-md-2',
                    inputType: 'text',
                    inputId: 'date_from',
                    selectValues: [],
                    placeholder: 'Date From',
                ),
                new FormField(
                    label: 'Date To',
                    labelFor: 'date_to',
                    labelClass: 'control-label col-md-2',
                    inputType: 'text',
                    inputId: 'date_to',
                    required: true,
                    autoComplete: "off",
                    placeholder: 'Date To',
                ),
            ],
        ];
    }

    /**
     * Get form fields for creating emptying.
     *
     * @return array
     */
    public function getCreateFormFields($id)
    {
        $application = Application::findOrFail($id);
        $containment = Containment::findOrFail($application->containment_id);
        $this->createFormFields = [
            new FormField(
                label: 'Application ID',
                labelFor: 'application_id',
                inputType: 'text',
                inputId: 'application_id',
                inputValue: $id,
                hidden: true
            ),
            new FormField(
                label: 'Containment ID',
                labelFor: 'containment_id',
                inputType: 'text',
                inputId: 'containment_id',
                inputValue: is_null($application->buildings) ? "-" : $application->buildings->containments->pluck('id'),
                hidden: true
            ),
            new FormField(
                label: 'Date',
                labelFor: 'emptied_date',
                inputType: 'label',
                inputId: 'emptied_date',
                labelValue: !is_null($containment->last_emptied_date) ? $containment->last_emptied_date : 'No date available',
                placeholder: 'Date',
            ),
            new FormField(
                label: 'Service Receiver Name',
                labelFor: 'service_receiver_name',
                inputType: 'text',
                inputId: 'service_receiver_name',
                required: true,
                placeholder: 'Service Receiver Name',
            ),
            new FormField(
                label: 'Service Receiver Gender',
                labelFor: 'service_receiver_gender',
                inputType: 'select',
                inputId: 'service_receiver_gender',
                selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                required: true,
                placeholder: 'Service Receiver Gender',
            ),
            new FormField(
                label: 'Service Receiver Contact Number',
                labelFor: 'service_receiver_contact',
                inputType: 'number',
                inputId: 'service_receiver_contact',
                required: true,
                placeholder: 'Service Receiver Contact Number',
            ),
            new FormField(
                label: 'Reason for Emptying',
                labelFor: 'emptying_reason',
                inputType: 'textarea',
                inputId: 'emptying_reason',
                required: true,
                placeholder: 'Reason for Emptying',
            ),
            new FormField(
                label: 'Containment Construction Year',
                labelFor: 'construction_year',
                inputType: 'label',
                inputId: 'construction_year',
                labelValue: date('Y', strtotime($containment->construction_date)),
                placeholder: 'Containment Construction Year',
            ),
            new FormField(
                label: 'Sludge Volume (m³)',
                labelFor: 'volume_of_sludge',
                inputType: 'number',
                inputId: 'volume_of_sludge',
                required: true,
                placeholder: 'Sludge Volume (m³)',
            ),
            new FormField(
                label: 'Distance to Closest Well (m)',
                labelFor: 'distance_closest_well',
                inputType: 'text',
                inputId: 'distance_closest_well',
                inputValue: $containment->distance_closest_well ? $containment->distance_closest_well : '',
                placeholder: 'Distance to Closest Well (m)',

            ),
            new FormField(
                label: 'Desludging Vehicle Number Plate',
                labelFor: 'desludging_vehicle_id',
                inputType: 'select',
                inputId: 'desludging_vehicle_id',
                selectValues: !Auth::user()->hasRole('Super Admin') ?
                VacutugType::Operational()->where('service_provider_id', $application->service_provider_id)->pluck('license_plate_number', 'id')->toArray()
                : VacutugType::Operational()->pluck('license_plate_number', 'id')->toArray(),
                required: true,
                placeholder: 'Desludging Vehicle Number Plate',
            ),
            new FormField(
                label: 'Disposal Place',
                labelFor: 'treatment_plant_id',
                inputType: 'select',
                inputId: 'treatment_plant_id',
                selectValues: TreatmentPlant::Operational()->pluck('name', 'id')->toArray(),
                required: true,
                placeholder: 'Disposal Place',
            ),
            new FormField(
                label: 'Driver Name',
                labelFor: 'driver',
                inputType: 'select',
                inputId: 'driver',
                selectValues: !Auth::user()->hasRole('Super Admin') ?
                EmployeeInfo::Active()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray()
                : EmployeeInfo::Active()->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray(),
                required: true,
                placeholder: 'Driver Name',
            ),
            new FormField(
                label: 'Emptier 1 Name',
                labelFor: 'emptier1',
                inputType: 'select',
                inputId: 'emptier1',
                selectValues: !Auth::user()->hasRole('Super Admin') ?
                EmployeeInfo::Active()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray()
                : EmployeeInfo::Active()->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray(),
                required: true,
                placeholder: 'Emptier 1 Name',
            ),
            new FormField(
                label: 'Emptier 2 Name',
                labelFor: 'emptier2',
                inputType: 'select',
                inputId: 'emptier2',
                selectValues: !Auth::user()->hasRole('Super Admin') ?
                EmployeeInfo::Active()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray()
                : EmployeeInfo::Active()->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray(),
                placeholder: 'Emptier 2 Name',
            ),
            new FormField(
                label: 'Start time',
                labelFor: 'start_time',
                inputType: 'time',
                inputId: 'start_time',
                required: true,
                placeholder: 'Start time',
            ),
            new FormField(
                label: 'End time',
                labelFor: 'end_time',
                inputType: 'time',
                inputId: 'end_time',
                required: true,
                placeholder: 'End time',
            ),
            new FormField(
                label: 'No. of trips',
                labelFor: 'no_of_trips',
                inputType: 'number',
                inputId: 'no_of_trips',
                required: true,
                placeholder: 'No. of trips',
            ),
            new FormField(
                label: 'Receipt number',
                labelFor: 'receipt_number',
                inputType: 'number',
                inputId: 'receipt_number',
                required: true,
                placeholder: 'Receipt number',
            ),
            new FormField(
                label: 'Total cost',
                labelFor: 'total_cost',
                inputType: 'number',
                inputId: 'total_cost',
                required: true,
                placeholder: 'Total cost',
            ),
            new FormField(
                label: 'House Image',
                labelFor: 'house_image',
                inputType: 'file_upload',
                inputId: 'house_image',
                required: true,
                placeholder: 'House Image',
            ),
            new FormField(
                label: 'Receipt Image',
                labelFor: 'receipt_image',
                inputType: 'file_upload',
                inputId: 'receipt_image',
                required: true,
                placeholder: 'Receipt Image',
            ),
            new FormField(
                label: 'Comments (if any)',
                labelFor: 'comments',
                inputType: 'textarea',
                inputId: 'comments',
                placeholder: 'Comments (if any)',
            ),
        ];

        return $this->createFormFields;
    }

    /**
     * Get form fields for showing emptying.
     *
     * @return array
     */
    public function getShowFormFields($emptying)
    {
        $application = Application::findOrFail($emptying->application_id);
        $containment = Containment::findOrFail($application->containment_id);
        $this->showFormFields = [
            new FormField(
                label: 'Application ID',
                labelFor: 'application_id',
                inputType: 'label',
                inputId: 'application_id',
                labelValue: $emptying->application_id,
            ),
            new FormField(
                label: 'Date',
                labelFor: 'emptied_date',
                inputType: 'label',
                inputId: 'emptied_date',
                labelValue: date('Y-m-d', strtotime($emptying->emptied_date)),
            ),
            new FormField(
                label: 'Service Receiver Name',
                labelFor: 'service_receiver_name',
                inputType: 'label',
                inputId: 'service_receiver_name',
                labelValue: $emptying->service_receiver_name
            ),
            new FormField(
                label: 'Service Receiver Gender',
                labelFor: 'service_receiver_gender',
                inputType: 'label',
                inputId: 'service_receiver_gender',
                labelValue: $emptying->service_receiver_gender
            ),
            new FormField(
                label: 'Service Receiver Contact Number',
                labelFor: 'service_receiver_contact',
                inputType: 'label',
                inputId: 'service_receiver_contact',
                labelValue: $emptying->service_receiver_contact
            ),
            new FormField(
                label: 'Reason for Emptying',
                labelFor: 'emptying_reason',
                inputType: 'label',
                inputId: 'emptying_reason',
                labelValue: $emptying->emptying_reason

            ),
            new FormField(
                label: 'Containment Construction Year',
                labelFor: 'construction_year',
                inputType: 'label',
                inputId: 'construction_year',
                labelValue: date('Y', strtotime($containment->construction_date)),
            ),
            new FormField(
                label: 'Sludge Volume (m³)',
                labelFor: 'volume_of_sludge',
                inputType: 'label',
                inputId: 'volume_of_sludge',
                labelValue: $emptying->volume_of_sludge
            ),
            new FormField(
                label: 'Distance to Closest Well (m)',
                labelFor: 'distance_to_closest_well',
                inputType: 'label',
                inputId: 'distance_to_closest_well',
                labelValue: $containment->distance_closest_well,

            ),
            new FormField(
                label: 'Desludging Vehicle Number Plate',
                labelFor: 'desludging_vehicle_id',
                inputType: 'label',
                inputId: 'desludging_vehicle_id',
                labelValue: $emptying->vacutug()->withTrashed()->first()->license_plate_number
            ),
            new FormField(
                label: 'Disposal Place',
                labelFor: 'treatment_plant_id',
                inputType: 'label',
                inputId: 'treatment_plant_id',
                labelValue: $emptying->treatmentPlant()->withTrashed()->first()->name
            ),
            new FormField(
                label: 'Driver Name',
                labelFor: 'driver',
                inputType: 'label',
                inputId: 'driver',
                labelValue: EmployeeInfo::withTrashed()->where('id',$emptying->driver)->first()->name
            ),
            new FormField(
                label: 'Emptier 1 Name',
                labelFor: 'emptier1',
                inputType: 'label',
                inputId: 'emptier1',

                labelValue: EmployeeInfo::withTrashed()->where('id',$emptying->emptier1)->first()->name
            ),
            new FormField(
                label: 'Emptier 2 Name',
                labelFor: 'emptier2',
                inputType: 'label',
                inputId: 'emptier2',
                labelValue: $emptying->emptier2 ? EmployeeInfo::withTrashed()->where('id',$emptying->emptier2)->first()->name : ''
            ),
            new FormField(
                label: 'Start time',
                labelFor: 'start_time',
                inputType: 'label',
                inputId: 'start_time',
                labelValue: $emptying->start_time
            ),
            new FormField(
                label: 'End time',
                labelFor: 'end_time',
                inputType: 'label',
                inputId: 'end_time',
                labelValue: $emptying->end_time
            ),
            new FormField(
                label: 'No. of trips',
                labelFor: 'no_of_trips',
                inputType: 'label',
                inputId: 'no_of_trips',
                labelValue: $emptying->no_of_trips
            ),
            new FormField(
                label: 'Receipt number',
                labelFor: 'receipt_number',
                inputType: 'label',
                inputId: 'receipt_number',
                labelValue: $emptying->receipt_number
            ),
            new FormField(
                label: 'Total cost',
                labelFor: 'total_cost',
                inputType: 'label',
                inputId: 'total_cost',
                labelValue: $emptying->total_cost
            ),
            new FormField(
                label: 'House Image',
                labelFor: 'house_image',
                inputType: 'file_viewer',
                inputId: 'house_image',
                labelValue: $emptying->house_image,
                fileUrl: asset('storage/emptyings/houses/' . $emptying->house_image)
            ),
            new FormField(
                label: 'Receipt Image',
                labelFor: 'receipt_image',
                inputType: 'file_viewer',
                inputId: 'receipt_image',
                labelValue: $emptying->receipt_image,
                fileUrl: asset('storage/emptyings/receipts/' . $emptying->receipt_image)
            ),
            new FormField(
                label: 'Comments (if any)',
                labelFor: 'comments',
                inputType: 'label',
                inputId: 'comments',
                labelValue: $emptying->comments,

            ),
        ];

        return $this->showFormFields;
    }

    /**
     * Get form fields for editing emptying.
     *
     * @return array
     */
    public function getEditFormFields($emptying)
    {
        $application = Application::findOrFail($emptying->application_id);
        $containment = Containment::findOrFail($application->containment_id);
         if(!Auth::user()->hasRole('Super Admin')) {
             if($application->emptying_status && $application->sludge_collection_status) {
                $selectValueVacutugType = VacutugType::withTrashed()->where('service_provider_id', $application->service_provider_id)->pluck('license_plate_number', 'id')->toArray();
             }
             else {
                $selectValueVacutugType = VacutugType::Operational()->where('service_provider_id', $application->service_provider_id)->pluck('license_plate_number', 'id')->toArray();

             }

             } else {
                 if($application->emptying_status && $application->sludge_collection_status) {
                     $selectValueVacutugType = VacutugType::withTrashed()->pluck('license_plate_number', 'id')->toArray();
                 }
                 else {
                     $selectValueVacutugType = VacutugType::Operational()->pluck('license_plate_number', 'id')->toArray();
                 }

         }
        if(!Auth::user()->hasRole('Super Admin')){
                if($application->emptying_status && $application->sludge_collection_status) {
                $selectValuesDriver = EmployeeInfo::withTrashed()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray();

                }
                else {
                   $selectValuesDriver =  EmployeeInfo::Active()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray();
                }
                }
        else {
            if($application->emptying_status && $application->sludge_collection_status) {
                $selectValuesDriver = EmployeeInfo::withTrashed()->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray();
            }
            else{
               $selectValuesDriver =  EmployeeInfo::Active()->where('employee_type', '=', 'Driver')->pluck('name', 'id')->toArray();
            }
        }
        if(!Auth::user()->hasRole('Super Admin')){
            if($application->emptying_status && $application->sludge_collection_status) {
                $selectValuesCleanerEmptier = EmployeeInfo::withTrashed()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray();
            } else {
                $selectValuesCleanerEmptier = EmployeeInfo::Active()->where('service_provider_id', $application->service_provider_id)->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray();
            }

            } else {
                if($application->emptying_status && $application->sludge_collection_status) {
                    $selectValuesCleanerEmptier = EmployeeInfo::withTrashed()->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray();
                } else {
                    $selectValuesCleanerEmptier = EmployeeInfo::Active()->where('employee_type', '=', 'Cleaner/Emptier')->pluck('name', 'id')->toArray();
                }
                }
        $this->editFormFields = [
            new FormField(
                label: 'Application ID',
                labelFor: 'application_id',
                inputType: 'textarea',
                inputId: 'application_id',
                inputValue: $emptying->application_id,
                hidden: true
            ),
            new FormField(
                label: 'Containment ID',
                labelFor: 'containment_id',
                inputType: 'text',
                inputId: 'containment_id',
                inputValue: is_null($application->buildings) ? "-" : $application->buildings->containments->pluck('id'),
                hidden: true
            ),
            new FormField(
                label: 'Date',
                labelFor: 'emptied_date',
                inputType: 'label',
                inputId: 'emptied_date',
                inputValue: $emptying->emptied_date,
                placeholder: 'Date',
            ),
            new FormField(
                label: 'Service Receiver Name',
                labelFor: 'service_receiver_name',
                inputType: 'text',
                inputId: 'service_receiver_name',
                inputValue: $emptying->service_receiver_name,
                required: true,
                placeholder: 'Service Receiver Name',
            ),
            new FormField(
                label: 'Service Receiver Gender',
                labelFor: 'service_receiver_gender',
                inputType: 'select',
                inputId: 'service_receiver_gender',
                selectedValue: $emptying->service_receiver_gender,
                selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                required: true,
                placeholder: 'Service Receiver Gender',
            ),
            new FormField(
                label: 'Service Receiver Contact Number',
                labelFor: 'service_receiver_contact',
                inputType: 'text',
                inputId: 'service_receiver_contact',
                inputValue: $emptying->service_receiver_contact,
                required: true,
                placeholder: 'Service Receiver Contact Number',
            ),
              new FormField(
                label: 'Reason for Emptying',
                labelFor: 'emptying_reason',
                inputType: 'textarea',
                inputId: 'emptying_reason',
                inputValue: $emptying->emptying_reason,
                required: true,
                placeholder: 'Reason for Emptying',
            ),
            new FormField(
                label: 'Containment Construction Year',
                labelFor: 'construction_year',
                inputType: 'label',
                inputId: 'construction_year',
                labelValue: date('Y', strtotime($containment->construction_date)),
                placeholder: 'Containment Construction Year',
            ),
            new FormField(
                label: 'Sludge Volume (m³)',
                labelFor: 'volume_of_sludge',
                inputType: 'number',
                inputId: 'volume_of_sludge',
                inputValue: $emptying->volume_of_sludge,
                required: true,
                placeholder: 'Sludge Volume (m³)',
            ),

           new FormField(
                label: 'Distance to Closest Well (m)',
                labelFor: 'distance_closest_well',
                inputType: 'text',
                inputId: 'distance_closest_well',
                inputValue: $containment->distance_closest_well,
                placeholder: 'Distance to Closest Well (m)',

            ),
            new FormField(
                label: 'Desludging Vehicle Number Plate',
                labelFor: 'desludging_vehicle_id',
                inputType: 'select',
                inputId: 'desludging_vehicle_id',
                selectValues: $selectValueVacutugType,
                selectedValue: $emptying->desludging_vehicle_id,
                required: true,
                disabled:$application->emptying_status && $application->sludge_collection_status ? true:'',
                placeholder: 'Desludging Vehicle Number Plate',
            ),
            new FormField(
                label: 'Disposal Place',
                labelFor: 'treatment_plant_id',
                inputType: 'select',
                inputId: 'treatment_plant_id',
                selectValues: $application->emptying_status && $application->sludge_collection_status ? TreatmentPlant::withTrashed()->pluck('name', 'id')->toArray() : TreatmentPlant::Operational()->pluck('name', 'id')->toArray(),
                selectedValue: $emptying->treatment_plant_id,
                required: true,
                disabled:$application->emptying_status && $application->sludge_collection_status ? true:'',
                placeholder: 'Disposal Place',

            ),
            new FormField(
                label: 'Driver Name',
                labelFor: 'driver',
                inputType: 'select',
                inputId: 'driver',
                selectValues: $selectValuesDriver,
                selectedValue: $emptying->driver,
                required: true,
                disabled:$application->emptying_status && $application->sludge_collection_status ? true:'',
                placeholder: 'Driver Name',
            ),
            new FormField(
                label: 'Emptier 1 Name',
                labelFor: 'emptier1',
                inputType: 'select',
                inputId: 'emptier1',
                selectValues: $selectValuesCleanerEmptier,
                selectedValue: $emptying->emptier1,
                required: true,
                disabled:$application->emptying_status && $application->sludge_collection_status ? true:'',
                placeholder: 'Emptier 1 Name',
            ),
            new FormField(
                label: 'Emptier 2 Name',
                labelFor: 'emptier2',
                inputType: 'select',
                inputId: 'emptier2',
                selectValues: $selectValuesCleanerEmptier,
                selectedValue: $emptying->emptier2,
                disabled:$application->emptying_status && $application->sludge_collection_status ? true:'',
                placeholder: 'Emptier 2 Name',

            ),
            new FormField(
                label: 'Start time',
                labelFor: 'start_time',
                inputType: 'time',
                inputId: 'start_time',
                inputValue: Carbon::parse($emptying->start_time)->format('H:i'),
                required: true,
                placeholder: 'Start time',
            ),
            new FormField(
                label: 'End time',
                labelFor: 'end_time',
                inputType: 'time',
                inputId: 'end_time',
                inputValue: Carbon::parse($emptying->end_time)->format('H:i'),
                required: true,
                placeholder: 'End time',
            ),
            new FormField(
                label: 'No. of trips',
                labelFor: 'no_of_trips',
                inputType: 'number',
                inputId: 'no_of_trips',
                inputValue: $emptying->no_of_trips,
                required: true,
                placeholder: 'No. of trips',
            ),
            new FormField(
                label: 'Receipt number',
                labelFor: 'receipt_number',
                inputType: 'number',
                inputId: 'receipt_number',
                inputValue: $emptying->receipt_number,
                required: true,
                placeholder: 'Receipt number',
            ),
            new FormField(
                label: 'Total cost',
                labelFor: 'total_cost',
                inputType: 'number',
                inputId: 'total_cost',
                inputValue: $emptying->total_cost,
                required: true,
                placeholder: 'Total cost',
            ),
            new FormField(
                label: 'House Image',
                labelFor: 'house_image',
                inputType: 'file_upload',
                inputId: 'house_image',
                inputValue: $emptying->house_image,
                required: true,
                placeholder: 'House Image',
            ),
            new FormField(
                label: 'Receipt Image',
                labelFor: 'receipt_image',
                inputType: 'file_upload',
                inputId: 'receipt_image',
                inputValue: $emptying->receipt_image,
                required: true,
                placeholder: 'Receipt Image',
            ),
            new FormField(
                label: 'Comments (if any)',
                labelFor: 'comments',
                inputType: 'textarea',
                inputId: 'comments',
                inputValue: $emptying->comments,
                placeholder: 'Date',
            ),
        ];
        return $this->editFormFields;
    }

    /**
     * Get action/route for create form.
     *
     * @return String
     */
    public function getCreateFormAction()
    {
        return $this->createFormAction;
    }

    /**
     * Get action/route for index page of Emptyings.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for index page of Application.
     *
     * @return String
     */
    public function getApplicationIndexAction()
    {
        return route('application.index');
    }

    /**
     * Get action/route for create page of Emptyings.
     *
     * @return String
     */
    public function getCreateRoute()
    {
        return $this->createRoute;
    }

    /**
     * Get action/route for exporting Emptyings.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    /**
     * Get action/route for edit form.
     *
     * @return String
     */
    public function getEditFormAction($emptying)
    {
        $this->editFormAction = route('emptying.update', $emptying);
        return $this->editFormAction;
    }

    /**
     * Get form fields for filter.
     *
     * @return array
     */
    public function getFilterFormFields()
    {
        return $this->filterFormFields;
    }

    /**
     * Get all the emptyings.
     *
     *
     * @return Emptying[]|Collection
     */
    public function getAllEmptyings()
    {
        if(Auth::user()->hasRole('Service Provider - Admin'))
        {
        return Emptying::join('fsm.applications', function($join) {
             $join->on('fsm.emptyings.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.emptyings.deleted_at')
            ->where('fsm.emptyings.service_provider_id',Auth::user()->service_provider_id)
            ->select('fsm.emptyings.*');
        }
        else if (Auth::user()->hasRole('Treatment Plant'))
        {
            return Emptying::join('fsm.applications', function($join) {
             $join->on('fsm.emptyings.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.emptyings.deleted_at')
            ->where('fsm.emptyings.treatment_plant_id',Auth::user()->treatment_plant_id)
            ->select('fsm.emptyings.*');
        }
        else
        {
            return Emptying::join('fsm.applications', function($join) {
             $join->on('fsm.emptyings.application_id', '=', 'fsm.applications.id')
            ->whereNull('fsm.applications.deleted_at');
            })
            ->whereNull('fsm.emptyings.deleted_at')
            ->select('fsm.emptyings.*');
        }
    }

    /**
     * Get Datatables of Applications.
     *
     * @return DataTables
     * @throws Exception
     */
    public function getDatatable(Request $request)
    {

        return DataTables::of($this->getAllEmptyings())
            ->filter(function ($query) use ($request) {

                if ($request->application_id) {
                    $query->where('application_id',"=", $request->application_id );
                }
                if ($request->house_number) {
                    $query->whereHas("application.buildings", function ($q) use ($request) {
                        $q->where("house_number", "=", $request->house_number);
                    });
                }
                if ($request->emptied_date) {
                    $query->whereDate('emptied_date', $request->emptied_date);
                }
                if ($request->containment_code) {
                    $query->whereHas("application", function ($q) use ($request) {
                        $q->where('containment_id', "=", $request->containment_code );
                    });
                }
                if ($request->date_from && $request->date_to) {
                    $query->whereBetween('emptied_date', [$request->date_from, $request->date_to]);;
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['emptying.destroy', $model->id]]);
                $content .= '<div class="">';
                // if (Auth::user()->can('Edit Emptying')) {
                //     $content .= '<a title="Edit" href="' . route('emptying.edit', [$model->id]) . '" class="btn btn-info btn-sm mb-1 '. ($model->application?->assessment_status && $model->application?->emptying_status ? ' anchor-disabled' : '') . '"><i class="fa fa-edit"></i></a> ';
                // }
                if (Auth::user()->can('View Emptying')) {
                    $content .= '<a title="Detail" href="' . route('emptying.show', [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route('emptying.history', $model->id) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                /*if (Auth::user()->can('Delete Emptying')) {
                    $content .= '<a title="Delete"  class="delete  btn-danger btn  btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }*/
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->editColumn('service_provider_id', function ($model) {
                return $model->service_provider->company_name ?? '-';
            })
            ->rawColumns(['assessment_status', 'emptying_status', 'feedback_status', 'action'])
            ->make(true);
    }

    /**
     * Create emptying.
     *
     * @throws Exception
     */
    public function createEmptying(Request $request)
    {
        $emptying = '';
        if ($request->validated()) {
            try {
                $emptying = Emptying::create($request->all());
                $application = Application::findOrFail($request->application_id);
                $containment = Containment::findOrFail($application->containment_id);
                if ($application->emptying_status) {
                    if ($emptying) {
                        $emptying->forceDelete();
                        $application->emptying_status = false;
                        $application->save();
                    }
                    return redirect()->back()->withInput()->with('error', "Emptying service is already done for application $application->id");
                }
                $containment->last_emptied_date = $emptying->emptied_date = now();
                $containment->next_emptying_date = now()->addYears(3);
                $containment->emptied_status = true;
                $containment->last_emptied_date = $emptying->emptied_date;
                $containment->no_of_times_emptied = $containment->no_of_times_emptied + 1;
                $emptying->service_provider_id = $application->service_provider_id;
                $emptying->user_id = \Auth::user()->id;

                // update containment last emptied date
                $containment->last_emptied_date = $emptying->emptied_date;
                //check image upload
                $allowedFileExt = ['pdf', 'jpg', 'jpeg', 'png', 'PNG'];
                $extension_receipt = $request->receipt_image->getClientOriginalExtension();
                $extension_house = $request->house_image->getClientOriginalExtension();
                $dateTime = now();
                $dateTime->setTimezone(new DateTimeZone('Asia/Kathmandu'));
                $dateTime = $dateTime->format('Y_m_d');
                $check = in_array($extension_receipt, $allowedFileExt) && in_array($extension_house, $allowedFileExt);
                if ($check) {
                    try {
                        $filename_receipt = $emptying->id . '_' . $emptying->application_id . '_' . $emptying->receipt_number . '_' . $dateTime . '.' . $extension_receipt;
                        $filename_house = $emptying->id . '_' . $emptying->application_id . '_' . $emptying->receipt_number . '_' . $dateTime . '.' . $extension_house;
                        $storeReceiptImg = Image::make($request->receipt_image)->save(Storage::disk('local')->path('/public/emptyings/receipts/' . $filename_receipt), 50);
                        $storeHouseImg = Image::make($request->house_image)->save(Storage::disk('local')->path('/public/emptyings/houses/' . $filename_house), 50);
                        if (!Storage::disk('local')->exists('/public/emptyings/receipts/' . $filename_receipt) || !Storage::disk('local')->exists('/public/emptyings/houses/' . $filename_house)) {
                            if ($emptying) {
                                $emptying->delete();
                                $application->emptying_status = false;
                                $application->save();
                            }
                            return redirect()->back()->withInput()->with('error', "Images already exists!");
                        }
                        $emptying->receipt_image = $filename_receipt;
                        $emptying->house_image = $filename_house;
                    } catch (\Throwable $th) {
                        if ($emptying) {
                            $emptying->delete();
                            $application->emptying_status = false;
                            $application->save();
                        }
                        return redirect()->back()->withInput()->with('error', "Error! Unable to save images.");
                    }

                } else {
                    if ($emptying) {
                        $emptying->delete();
                        $application->emptying_status = false;
                        $application->save();
                    }
                    return redirect()->back()->withInput()->with('error', "Error! Invalid image format.");
                }
                //end check image
                $emptying->save();
                $application->emptying_status = true;
                $application->save();
                $containment->save();
            } catch (\Throwable $e) {
                if ($emptying) {
                    $emptying->delete();
                    $application->emptying_status = false;
                    $application->save();
                }
                return redirect()->back()->withInput()->with('error', "Error! Emptying couldn't be created." . $e);
            }
        }

        return redirect(route('application.index'))->with('success', 'Emptying Service Details created successfully');
    }

    /**
     * Update emptying.
     *
     * @throws Exception
     */
    public function updateEmptying(Request $request, $id)
    {
        $emptying = Emptying::findOrFail($id);
        try {
            if ($request->validated()) {
                $emptying->updateOrFail($request->all());
                $dateTime = now();
                $dateTime->setTimezone(new DateTimeZone('Asia/Kathmandu'));
                $dateTime = $dateTime->format('Y_m_d');
                //check image upload
                $allowedFileExt = ['pdf', 'jpg', 'jpeg', 'png', 'PNG'];
                if (!is_null($request->receipt_image)) {
                    $extension_receipt = $request->receipt_image->getClientOriginalExtension();
                    $check = in_array($extension_receipt, $allowedFileExt);
                    if ($check) {
                        try {
                            $filename_receipt = $emptying->id . '_' . $emptying->application_id . '_' . $emptying->receipt_number . '_' . $dateTime . '.' . $extension_receipt;
                            $storeReceiptImg = Image::make($request->receipt_image)->save(Storage::disk('local')->path('/public/emptyings/receipts/' . $filename_receipt), 50);
                            if (!Storage::disk('local')->exists('/public/emptyings/receipts/' . $filename_receipt)) {
                                return redirect()->back()->withInput()->with('error', "Error! Unable to save receipt image.");
                            }
                            $emptying->receipt_image = $filename_receipt;
                        } catch (\Throwable $th) {
                            return redirect()->back()->withInput()->with('error', "Error! Unable to save images.");
                        }

                    } else {
                        return redirect()->back()->withInput()->with('error', "Error! Invalid image format.");
                    }


                } elseif (!is_null($request->house_image)) {
                    $extension_house = $request->house_image->getClientOriginalExtension();
                    $check = in_array($extension_house, $allowedFileExt);
                    if ($check) {
                        try {
                            $filename_house = $emptying->id . '_' . $emptying->application_id . '_' . $emptying->receipt_number . '_' . $dateTime . '.' . $extension_house;
                            $storeHouseImg = Image::make($request->house_image)->save(Storage::disk('local')->path('/public/emptyings/houses/' . $filename_house), 50);
                            if (!Storage::disk('local')->exists('/public/emptyings/houses/' . $filename_house)) {
                                return redirect()->back()->withInput()->with('error', "Error! Unable to save house image.");
                            }
                            $emptying->house_image = $filename_house;
                        } catch (\Throwable $th) {
                            return redirect()->back()->withInput()->with('error', "Error! Unable to save images.");
                        }

                    } else {
                        return redirect()->back()->withInput()->with('error', "Error! Invalid image format.");
                    }


                }
                $emptying->save();
                if($request->distance_closest_well){
                    $application = Application::findOrFail($emptying->application_id);
                    $containment = Containment::findOrFail($application->containment_id);
                    $containment->distance_closest_well = $request->distance_closest_well ? $request->distance_closest_well : null;
                    $containment->save();
                }
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update Emptying');
        }
        return redirect(route('application.index'))->with('success', 'Emptying updated successfully');
    }

    /**
     * Retrieve emptying history.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     */
    public function getEmptyingHistory($id)
    {
        try {
            $emptying = Emptying::findOrFail($id);
            $revisions = Revision::all()
                ->where('revisionable_type', get_class($emptying))
                ->where('revisionable_id', $id)
                ->groupBy(function ($item) {
                    return $item->created_at->format("D M j Y");
                })
                ->sortByDesc('created_at')
                ->reverse();
        } catch (\Throwable $e) {
            return redirect(route('emptying.index'))->with('error', 'Failed to generate history.');
        }
        return view('fsm.emptying.history', compact('emptying', 'revisions'));
    }

    /**
     * Export emptyings.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {

        $application_id = $request->application_id;
        $house_number = $request->house_number;
        $emptied_date = $request->emptied_date;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $containment_id = $request->containment_id;

        $headers = [
            'Application ID',
            'House Number',
            'Volume of Sludge',
            'Emptied Date',
            'Desludging Vehicle License Plate',
            'Driver Name',
            'Emptier 1 Name',
            'Emptier 2 Name',
            'Start Time',
            'End Time',
            'No. of Trips',
            'Receipt Number',
            'Treatment Plant Name',
            'Total Cost',
            'Service Provider',
            'Uploaded By'
        ];

        $query =  DB::table('fsm.emptyings AS e')
            ->leftJoin('fsm.applications AS a', 'a.id', '=', 'e.application_id')
            ->leftJoin('building_info.buildings AS b', 'b.bin', '=', 'a.house_number')
            ->leftJoin('fsm.desludging_vehicles AS dv', 'dv.id', '=', 'e.desludging_vehicle_id')
            ->leftJoin('fsm.service_providers AS s', 's.id', '=', 'e.service_provider_id')
            ->leftJoin('fsm.employees AS d', 'd.id', '=', 'e.driver')
            ->leftJoin('fsm.employees AS e1', 'e1.id', '=', 'e.emptier1')
            ->leftJoin('fsm.employees AS e2', 'e2.id', '=', 'e.emptier2')
            ->leftJoin('fsm.treatment_plants AS t', 't.id', '=', 'e.treatment_plant_id')
            ->leftJoin('auth.users AS u', 'u.id', '=', 'e.user_id')
            ->select(
                'e.application_id',
                'b.bin AS house_number',
                'e.volume_of_sludge',
                'e.emptied_date',
                'dv.license_plate_number AS desludging_vehicle_license_plate',
                'd.name AS driver_name',
                'e1.name AS emptier1_name',
                'e2.name AS emptier2_name',
                'e.start_time',
                'e.end_time',
                'e.no_of_trips',
                'e.receipt_number',
                't.name AS treatment_plant_name',
                'e.total_cost',
                's.company_name',
                'u.name AS user'
            )->orderBy('e.id')
            ->whereNull('e.deleted_at');

        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->hasRole('Municipality - IT Admin') && !Auth::user()->hasRole('Municipality - Sanitation Department')) {
            $query->where('user', '=', Auth::name());
        }
        if (!empty($application_id)) {
            $query->where('e.application_id', $application_id);
        }
        if (!empty($house_number)) {
            $query->whereHas("application.buildings", function ($q) use ($house_number) {
                $q->where("house_number", "=", $house_number);
            });
        }
        if (!empty($emptied_date)) {
            $query->whereDate('e.emptied_date', $emptied_date);
        }
        if ($date_from && $date_to) {
            $query->whereBetween('e.emptied_date', [$date_from, $date_to]);
        }
        if (!empty($containment_id)) {
            $query->whereExists(function ($q) use ($containment_id) {
                $q->select(DB::raw(1))
                    ->from('fsm.applications AS a')
                    ->whereRaw('a.id = e.application_id')
                    ->where('a.containment_id', '=', $containment_id);
            });
        }
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Emptyings.csv')
            ->addRowWithStyle($headers, $style); //Top row of excel

        $query->chunk(5000, function ($emptyings) use ($writer) {
            foreach($emptyings as $emptying) {

                $values = [];
                $values[] = $emptying->application_id??"-";
                $values[] = $emptying->house_number??"-";
                $values[] = $emptying->volume_of_sludge??"-";
                $values[] = $emptying->emptied_date??"-";
                $values[] = $emptying->desludging_vehicle_license_plate??"-";
                $values[] = $emptying->driver_name??"-";
                $values[] = $emptying->emptier1_name??"-";
                $values[] = $emptying->emptier2_name??"-";
                $values[] = $emptying->start_time??"-";
                $values[] = $emptying->end_time??"-";
                $values[] = $emptying->no_of_trips??"-";
                $values[] = $emptying->receipt_number??"-";
                $values[] = $emptying->treatment_plant_name??"-";
                $values[] = $emptying->total_cost??"-";
                $values[] = $emptying->company_name??"-";
                $values[] = $emptying->user??"-";
                $writer->addRow($values);

            }
        });

        $writer->close();
    }

}
