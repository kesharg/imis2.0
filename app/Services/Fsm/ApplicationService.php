<?php
// Last Modified Date: 11-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\Fsm;

use App\Classes\FormField;
use App\Http\Requests\Fsm\ApplicationRequest;
use App\Models\Fsm\HelpDesk;
use App\Models\Fsm\ServiceProvider;
use App\Models\LayerInfo\Ward;
use App\Models\User;

use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
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
use Venturecraft\Revisionable\Revision;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Datetime;
use PDF;


class ApplicationService
{

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $createRoute,$exportRoute;
    protected $createPartialForm, $createFormFields, $createFormAction;
    protected $showFormFields, $editFormFields, $filterFormFields;
    protected $reportRoute;
    /**
     * Constructs a new ApplicationService object.
     *
     *
     */
    public function __construct()
    {
        /*Session code
        ....
         here*/

        $this->createPartialForm = 'fsm.application.partial-form';
        $this->createFormFields = [
            ["title" => "Address",
                "fields" => [
                    new FormField(
                        label: 'Street Name',
                        labelFor: 'road_code',
                        inputType: 'multiple-select',
                        inputId: 'road_code',
                        selectValues: [],
                        required: true
                    ),
                    new FormField(
                        label: 'House Number',
                        labelFor: 'house_number',
                        inputType: 'multiple-select',
                        inputId: 'house_number',
                        selectValues: [],
                        required: true
                    ),
                    new FormField(
                        label: 'Ward Number',
                        labelFor: 'ward',
                        inputType: 'select',
                        inputId: 'ward',
                        placeholder: 'Ward Number',
                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
                    ),
                    /*new FormField(
                        label: 'Address',
                        labelFor: 'address',
                        inputType: 'multiple-select',
                        inputId: 'address',
                        selectValues: Building::orderByRaw('LENGTH(address) ASC')->orderBy('address','ASC')->pluck('address','address')->toArray(),
                        required: true
                    ),*/
                ]],
            ["title" => "Owner Details",
                "fields" => [
                    new FormField(
                        label: 'Owner Name',
                        labelFor: 'customer_name',
                        inputType: 'text',
                        inputId: 'customer_name',
                        placeholder: 'Owner Name',
                    ),
                    new FormField(
                        label: 'Owner Gender',
                        labelFor: 'customer_gender',
                        inputType: 'select',
                        inputId: 'customer_gender',
                        selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                        placeholder: 'Owner Gender',
                    ),
                    new FormField(
                        label: 'Owner Contact (Phone)',
                        labelFor: 'customer_contact',
                        inputType: 'number',
                        inputId: 'customer_contact',
                        selectValues: [],
                        placeholder: 'Owner Contact (Phone)',
                    ),
                ]],
            ["title" => "Applicant Details",
                "copyDetails"=>true,
                "fields" => [
                    new FormField(
                        label: "Applicant Name",
                        labelFor: 'applicant_name',
                        inputType: 'text',
                        inputId: 'applicant_name',
                        selectValues: [],
                        required: true,
                        placeholder: 'Applicant Name',
                    ),
                    new FormField(
                        label: "Applicant Gender",
                        labelFor: 'applicant_gender',
                        inputType: 'select',
                        inputId: 'applicant_gender',
                        selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                        required: true,
                        placeholder: 'Applicant Gender',
                    ),
                    new FormField(
                        label: "Applicant Contact Number",
                        labelFor: 'applicant_contact',
                        inputType: 'number',
                        inputId: 'applicant_contact',
                        selectValues: [],
                        required: true,
                        placeholder: 'Applicant Contact Number',
                    ),
                ]],

//            ["title" => "Building Details",
//                "id" => "building-if-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Road',
//                        labelFor: 'road_code',
//                        inputType: 'select',
//                        inputId: 'road_code',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    /*new FormField(
//                        label: 'Containment',
//                        labelFor: 'containment_code',
//                        inputType: 'text',
//                        inputId: 'containment_code',
//                        selectValues: Containment::all()->pluck('code','code')->toArray(),
//                    ),*/
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward',
//                        inputType: 'select',
//                        inputId: 'ward',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                ]],
//            ["title" => "Building Details",
//                "id" => "building-if-not-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Nearest Road',
//                        labelFor: 'road_code_no_addr',
//                        inputType: 'select',
//                        inputId: 'road_code_no_addr',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward_no_addr',
//                        inputType: 'select',
//                        inputId: 'ward_no_addr',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Nearest Landmark',
//                        labelFor: 'landmark',
//                        inputType: 'text',
//                        inputId: 'landmark',
//                        selectValues: [],
//                    ),
//                ]],
            ["title" => "Application Details",
                "fields" => [
                    new FormField(
                        label: 'Proposed Emptying Date',
                        labelFor: 'proposed_emptying_date',
                        inputType: 'text',
                        inputId: 'proposed_emptying_date',
                        required: true,
                        autoComplete: "off",
                        placeholder: 'Proposed Emptying Date',
                    ),
                    new FormField(
                        label: 'Service Provider Name',
                        labelFor: 'service_provider_id',
                        inputType: 'select',
                        inputId: 'service_provider_id',
                        selectValues: ServiceProvider::Operational()->pluck("company_name","id")->toArray(),
                        required: true,
                        placeholder: 'Service Provider Name',
                    ),
                    new FormField(
                        label: 'Emergency Desludging',
                        labelFor: 'emergency_desludging_status',
                        inputType: 'select',
                        inputId: 'emergency_desludging_status',
                        selectValues: array("1" => "Yes" , "0" => "No"),
                        required: true,
                        placeholder: 'Emergency Desludging',
                    ),
                ]],
                ["title" => "Household Details",
                "fields" => [
                    new FormField(
                        label: 'Number of Households',
                        labelFor: 'household_served',
                        inputType: 'number',
                        inputId: 'household_served',
                        required: false,
                        placeholder: 'Number of Households',
                    ),
                    new FormField(
                        label: 'Population of Building',
                        labelFor: 'population_served',
                        inputType: 'number',
                        inputId: 'population_served',
                        required: false,
                        placeholder: 'Population of Building',
                    ),
                    new FormField(
                        label: 'Number of Toilets',
                        labelFor: 'toilet_count',
                        inputType: 'number',
                        inputId: 'toilet_count',
                        required: false,
                        placeholder: 'Number of Toilets',
                    ),
                ]],
        ];
        $this->createFormAction = route('application.store');
        $this->indexAction = route('application.index');
        $this->createRoute = route('application.create');
        $this->exportRoute = route('application.export');
        $this->reportRoute = 'true';
        $this->filterFormFields = [
            [
                new FormField(
                    label: 'House Number',
                    labelFor: 'house_number',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'house_number',
                    selectValues: [],
                    required: true,
                    placeholder: 'House Number',
                ),
                /*new FormField(
                    label: 'Address',
                    labelFor: 'address',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'address',
                    selectValues: Building::all()->pluck('address','address')->sort()->toArray(),
            ),*/
                new FormField(
                    label: 'Owner Name',
                    labelFor: 'customer_name',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'customer_name',
                    placeholder: 'Owner Name',
                ),
                new FormField(
                    label: 'Application ID',
                    labelFor: 'application_id',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'application_id',
                    placeholder: 'Application ID',
                ),
            ],
            [
                new FormField(
                    label: 'Emptying Status',
                    labelFor: 'emptying_status',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'select',
                    inputId: 'emptying_status',
                    selectValues: [true=>"Yes",false=>"No"],
                    placeholder: 'Status',
                    autoComplete: "off",
                ),
                new FormField(
                    label: 'Sludge Collection Status',
                    labelFor: 'sludge_collection_status',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'select',
                    inputId: 'sludge_collection_status',
                    selectValues: ["true"=>"Yes","false"=>"No"],
                    placeholder: 'Status',
                    autoComplete: "off",
                ),
                new FormField(
                    label: 'Feedback Status',
                    labelFor: 'feedback_status',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'select',
                    inputId: 'feedback_status',
                    selectValues: [true=>"Yes",false=>"No"],
                    placeholder: 'Status',
                    autoComplete: "off",
                ),

            ],
            [
                new FormField(
                    label: 'Road',
                    labelFor: 'road_code',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'multiple-select',
                    inputId: 'road_code',
                    selectValues: [],
                    placeholder: 'Road',
                ),
                new FormField(
                    label: 'Proposed Emptying Date',
                    labelFor: 'proposed_emptying_date',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'proposed_emptying_date',
                    required: true,
                    autoComplete: "off",
                    placeholder: 'Proposed Emptying Date',
                ),
                new FormField(
                    label: 'Ward Number',
                    labelFor: 'ward',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'select',
                    inputId: 'ward',
                    placeholder: 'Ward',
                    selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
                ),
            ],
            [
                new FormField(
                    label: 'Service Provider Name',
                    labelFor: 'service_provider_id',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'select',
                    inputId: 'service_provider_id',
                    placeholder: 'Service Provider',
                    selectValues: ServiceProvider::Operational()->pluck("company_name","id")->toArray(),
                ),
                new FormField(
                    label: 'Date From',
                    labelFor: 'date_from',
                    labelClass: 'col-md-2 col-form-label ',
                    inputType: 'text',
                    inputId: 'date_from',
                    selectValues: [],
                    placeholder: 'Date From',
                ),
                new FormField(
                    label: 'Date To',
                    labelFor: 'date_to',
                    labelClass: 'col-md-2 col-form-label ',
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
     * Get form fields for creating application.
     *
     * @return array
     */
    public function getCreateFormFields()
    {
        return $this->createFormFields;
    }

    /**
     * Get form fields for showing application.
     *
     * @return array
     */
    public function getShowFormFields($application)
    {

        $this->showFormFields = [
            ["title" => "Address",
                "fields" => [
                    new FormField(
                        label: 'Street Name',
                        labelFor: 'road_code',
                        inputType: 'label',
                        inputId: 'road_code',
                        labelValue: $application->road_code,
                    ),
                    new FormField(
                        label: 'House Number',
                        labelFor: 'house_number',
                        inputType: 'label',
                        inputId: 'house_number',
                        labelValue: $application->house_number,
                    ),
                    new FormField(
                        label: 'Ward Number',
                        labelFor: 'ward',
                        inputType: 'label',
                        inputId: 'ward',
                        labelValue: $application->ward,
                    ),
                    /*new FormField(
                        label: 'Address',
                        labelFor: 'address',
                        inputType: 'multiple-select',
                        inputId: 'address',
                        selectValues: Building::orderByRaw('LENGTH(address) ASC')->orderBy('address','ASC')->pluck('address','address')->toArray(),
                        required: true
                    ),*/
                ]],
            ["title" => "Owner Details",
                "fields" => [
                    new FormField(
                        label: 'Owner Name',
                        labelFor: 'customer_name',
                        inputType: 'label',
                        inputId: 'customer_name',
                        labelValue: $application->customer_name,
                    ),
                    new FormField(
                        label: 'Owner Gender',
                        labelFor: 'customer_gender',
                        inputType: 'label',
                        inputId: 'customer_gender',
                        labelValue: $application->customer_gender,
                    ),
                    new FormField(
                        label: 'Owner Contact (Phone)',
                        labelFor: 'customer_contact',
                        inputType: 'label',
                        inputId: 'customer_contact',
                        labelValue: $application->customer_contact,
                    ),
                ]],
            ["title" => "Applicant Details",
                "fields" => [
                    new FormField(
                        label: "Applicant Name",
                        labelFor: 'applicant_name',
                        inputType: 'label',
                        inputId: 'applicant_name',
                        labelValue: $application->applicant_name,
                    ),
                    new FormField(
                        label: "Applicant Gender",
                        labelFor: 'applicant_gender',
                        inputType: 'label',
                        inputId: 'applicant_gender',
                        labelValue: $application->applicant_gender,
                    ),
                    new FormField(
                        label: "Applicant Contact Number",
                        labelFor: 'applicant_contact',
                        inputType: 'label',
                        inputId: 'applicant_contact',
                        labelValue: $application->applicant_contact,
                    ),
                ]],

//            ["title" => "Building Details",
//                "id" => "building-if-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Road',
//                        labelFor: 'road_code',
//                        inputType: 'select',
//                        inputId: 'road_code',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    /*new FormField(
//                        label: 'Containment',
//                        labelFor: 'containment_code',
//                        inputType: 'text',
//                        inputId: 'containment_code',
//                        selectValues: Containment::all()->pluck('code','code')->toArray(),
//                    ),*/
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward',
//                        inputType: 'select',
//                        inputId: 'ward',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                ]],
//            ["title" => "Building Details",
//                "id" => "building-if-not-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Nearest Road',
//                        labelFor: 'road_code_no_addr',
//                        inputType: 'select',
//                        inputId: 'road_code_no_addr',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward_no_addr',
//                        inputType: 'select',
//                        inputId: 'ward_no_addr',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Nearest Landmark',
//                        labelFor: 'landmark',
//                        inputType: 'text',
//                        inputId: 'landmark',
//                        selectValues: [],
//                    ),
//                ]],

            ["title" => "Application Details",
                "fields" => [
                    new FormField(
                        label: 'Proposed Emptying Date',
                        labelFor: 'proposed_emptying_date',
                        inputType: 'label',
                        inputId: 'proposed_emptying_date',
                        labelValue: date('Y-m-d', strtotime($application->proposed_emptying_date)),
                    ),
                    new FormField(
                        label: 'Service Provider Name',
                        labelFor: 'service_provider_id',
                        inputType: 'label',
                        inputId: 'service_provider_id',
                        labelValue: $application->service_provider ? $application->service_provider()->withTrashed()->first()->company_name : 'Not Assigned',
                    ),
                     new FormField(
                        label: 'Emergency Desludging',
                        labelFor: 'emergency_desludging_status',
                        inputType: 'label',
                        inputId: 'emergency_desludging_status',
                        labelValue: $application->emergency_desludging_status ? 'Yes' : 'No',

                    ),
                    /*new FormField(
                        label: 'Help desk',
                        labelFor: 'help_desk_id',
                        inputType: 'label',
                        inputId: 'help_desk_id',
                        labelValue: HelpDesk::find(User::where('id',$application->user_id)->pluck('help_desk_id'))->first()->name??'-',
                    ),*/
                ]],
        ];

        return $this->showFormFields;
    }

    /**
     * Get form fields for editing application.
     *
     * @return array
     */
    public function getEditFormFields($application)
    {
        if($application->emptying_status) {
                     $selectValueServiceProvider = ServiceProvider::withTrashed()->pluck("company_name","id")->toArray();
                 }
                 else {
                     $selectValueServiceProvider = ServiceProvider::Operational()->pluck("company_name","id")->toArray();
                 }
        $this->editFormFields = [
            ["title" => "Address",
                "fields" => [
                    new FormField(
                        label: 'Street Name',
                        labelFor: 'road_code',
                        inputType: 'label',
                        inputId: 'road_code',
                        labelValue: $application->road_code,
                        placeholder: 'Street Name',
                    ),
                    new FormField(
                        label: 'House Number',
                        labelFor: 'house_number',
                        inputType: 'label',
                        inputId: 'house_number',
                        labelValue: $application->house_number,
                        placeholder: 'House Number',
                    ),
                    new FormField(
                        label: 'Ward Number',
                        labelFor: 'ward',
                        inputType: 'label',
                        inputId: 'ward',
                        labelValue: $application->ward,
                        placeholder: 'Ward Number',
                    ),
                    new FormField(
                        label: 'Street Name',
                        labelFor: 'road_code',
                        inputType: 'text',
                        inputId: 'road_code',
                        inputValue: $application->road_code,
                        hidden: true,
                        placeholder: 'Street Name',
                    ),
                    new FormField(
                        label: 'House Number',
                        labelFor: 'house_number',
                        inputType: 'text',
                        inputId: 'house_number',
                        inputValue: $application->house_number,
                        hidden: true,
                        placeholder: 'House Number',
                    ),
                    new FormField(
                        label: 'Ward Number',
                        labelFor: 'ward',
                        inputType: 'label',
                        inputId: 'text',
                        inputValue: $application->ward,
                        hidden: true,
                        placeholder: 'Ward Number',
                    ),
                    /*new FormField(
                        label: 'Address',
                        labelFor: 'address',
                        inputType: 'multiple-select',
                        inputId: 'address',
                        selectValues: Building::orderByRaw('LENGTH(address) ASC')->orderBy('address','ASC')->pluck('address','address')->toArray(),
                        required: true
                    ),*/
                ]],
            ["title" => "Owner Details",
                "fields" => [
                    new FormField(
                        label: 'Owner Name',
                        labelFor: 'customer_name',
                        inputType: 'text',
                        inputId: 'customer_name',
                        inputValue: $application->customer_name,
                        placeholder: 'Owner Name',
                    ),
                    new FormField(
                        label: 'Owner Gender',
                        labelFor: 'customer_gender',
                        inputType: 'select',
                        inputId: 'customer_gender',
                        selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                        selectedValue: $application->customer_gender,
                        placeholder: 'Owner Gender',
                    ),
                    new FormField(
                        label: 'Owner Contact (Phone)',
                        labelFor: 'customer_contact',
                        inputType: 'number',
                        inputId: 'customer_contact',
                        inputValue: $application->customer_contact,
                        placeholder: 'Owner Contact (Phone)',
                    ),
                ]],
            ["title" => "Applicant Details",
                "copyDetails"=>true,
                "fields" => [
                    new FormField(
                        label: "Applicant Name",
                        labelFor: 'applicant_name',
                        inputType: 'text',
                        inputId: 'applicant_name',
                        inputValue: $application->applicant_name,
                        selectValues: [],
                        required: true,
                        placeholder: 'Applicant Name',
                    ),
                    new FormField(
                        label: "Applicant Gender",
                        labelFor: 'applicant_gender',
                        inputType: 'select',
                        inputId: 'applicant_gender',
                        selectValues: ["M"=>"Male","F"=>"Female","O"=>"Others"],
                        selectedValue: $application->applicant_gender,
                        required: true,
                        placeholder: 'Applicant Gender',
                    ),
                    new FormField(
                        label: "Applicant Contact Number",
                        labelFor: 'applicant_contact',
                        inputType: 'number',
                        inputId: 'applicant_contact',
                        inputValue: $application->applicant_contact,
                        selectValues: [],
                        required: true,
                        placeholder: 'Applicant Contact Number',
                    ),
                ]],

//            ["title" => "Building Details",
//                "id" => "building-if-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Road',
//                        labelFor: 'road_code',
//                        inputType: 'select',
//                        inputId: 'road_code',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    /*new FormField(
//                        label: 'Containment',
//                        labelFor: 'containment_code',
//                        inputType: 'text',
//                        inputId: 'containment_code',
//                        selectValues: Containment::all()->pluck('code','code')->toArray(),
//                    ),*/
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward',
//                        inputType: 'select',
//                        inputId: 'ward',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                ]],
//            ["title" => "Building Details",
//                "id" => "building-if-not-address",
//                "hidden" => true,
//                "fields" => [
//                    new FormField(
//                        label: 'Nearest Road',
//                        labelFor: 'road_code_no_addr',
//                        inputType: 'select',
//                        inputId: 'road_code_no_addr',
//                        selectValues: Roadline::all()->pluck('name','code')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Ward',
//                        labelFor: 'ward_no_addr',
//                        inputType: 'select',
//                        inputId: 'ward_no_addr',
//                        selectValues: Ward::orderBy('ward')->pluck('ward','ward')->toArray(),
//                    ),
//                    new FormField(
//                        label: 'Nearest Landmark',
//                        labelFor: 'landmark',
//                        inputType: 'text',
//                        inputId: 'landmark',
//                        selectValues: [],
//                    ),
//                ]],
            ["title" => "Application Details",
                "fields" => [
                    new FormField(
                        label: 'Proposed Emptying Date',
                        labelFor: 'proposed_emptying_date',
                        inputType: 'text',
                        inputId: 'proposed_emptying_date',
                        inputValue: Carbon::parse($application->proposed_emptying_date)->format('m/d/Y'),
                        required: true,
                        autoComplete: "off",
                        disabled:$application->emptying_status?true:'',
                        placeholder: 'Proposed Emptying Date',
                    ),
                    new FormField(
                        label: 'Service Provider Name',
                        labelFor: 'service_provider_id',
                        inputType: 'select',
                        inputId: 'service_provider_id',
                        selectValues: $selectValueServiceProvider,
                        selectedValue: $application->service_provider_id,
                        required: true,
                        disabled:$application->emptying_status?true:'',
                        placeholder: 'Service Provider Name',
                    ),
                     new FormField(
                        label: 'Emergency Desludging',
                        labelFor: 'emergency_desludging_status',
                        inputType: 'select',
                        inputId: 'emergency_desludging_status',
                        selectValues: array("1" => "Yes" , "0" => "No"),
                        selectedValue: $application->emergency_desludging_status ? "1" : "0",
                        required: true,
                        placeholder: 'Emergency Desludging',
                    ),
                ]],
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
     * Get action/route for index page of Applications.
     *
     * @return String
     */
    public function getIndexAction()
    {
        return $this->indexAction;
    }

    /**
     * Get action/route for create page of Applications.
     *
     * @return String
     */
    public function getCreateRoute()
    {
        return $this->createRoute;
    }

    /**
     * Get action/route for exporting Applications.
     *
     * @return String
     */
    public function getExportRoute()
    {
        return $this->exportRoute;
    }

    public function getReportRoute()
    {
        return $this->reportRoute;
    }

    /**
     * Get action/route for edit form.
     *
     * @return String
     */
    public function getEditFormAction($application)
    {
        $this->editFormAction = route('application.update', $application);
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
     * Get all the applications.
     *
     *
     * @return Application[]|Collection
     */
    public function getAllApplications(Request $request)
    {

        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk'))
        {
          return  Application::whereNull('deleted_at')->where('applications.service_provider_id',"=",Auth::user()->service_provider_id);
        }
        else if(Auth::user()->hasRole('Treatment Plant'))
        {
           return Application::whereHas("emptying",function($q) use($request){
                $q->where("treatment_plant_id","=",Auth::user()->treatment_plant_id)
                ->where("emptying_status", true)
                ->whereNull('deleted_at');
            });

        }
        else
        {
            return Application::whereNull('deleted_at');
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
        return DataTables::of($this->getAllApplications($request))

            ->filter(function ($query) use ($request) {

                if ($request->house_number){
                    $query->whereHas('buildings', function ($query) use ($request) {
                        $query->where('house_number', 'ILIKE', '%' . $request->house_number . '%');
                        $query->orWhere('bin', 'ILIKE', '%' . $request->house_number . '%');

                    });
                }
                if ($request->customer_name){
                    $query->where('customer_name','ILIKE','%'.$request->customer_name.'%');
                }
                if ($request->ward){
                    $query->where('ward',$request->ward);
                }
                if ($request->application_id){
                    $query->where('id',$request->application_id);
                }
                if (!is_null($request->emptying_status)){
                    $query->where('emptying_status', $request->emptying_status);
                }
                if (!is_null($request->feedback_status)){
                    $query->where('feedback_status', $request->feedback_status);
                }
                if (!is_null($request->sludge_collection_status)){
                    $query->where('sludge_collection_status', $request->sludge_collection_status);
                }
                if ($request->road_code){
                    $query->where('road_code',$request->road_code);
                }
                if ($request->proposed_emptying_date){
                    $query->where('proposed_emptying_date',$request->proposed_emptying_date);
                }
                if ($request->service_provider_id){
                    $query->where('service_provider_id',$request->service_provider_id);
                }
                if ($request->date_from && $request->date_to) {
                    $query->whereDate('application_date', '>=', $request->date_from);
                    $query->whereDate('application_date', '<=', $request->date_to);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['application.destroy', $model->id]]);
                $content .= '<div class="">';
                if (Auth::user()->can('Edit Application')){
                    $content .= '<a title="Edit" href="' . route('application.edit', [$model->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1 '. ($model->emptying_status? ' anchor-disabled' : '') . '"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Application')){
                    $content .= '<a title="Detail" href="' . route('application.show', [$model->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('Edit Emptying') && $model->emptying_status){
                    $content .= '<a title="Edit Emptying Service Details" href="' . route("emptying.edit", [$model->with('emptying')->where('id',$model->id)->get()->first()->emptying->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1'. ( $model->sludge_collection_status  ? ' anchor-disabled' : '') . '"><i class="fa fa-recycle"></i></a> ';
                }
                // if (Auth::user()->can('Edit Feedback') && $model->feedback_status){
                //     $content .= '<a title="Edit Feedback Details" href="' . route("feedback.edit", [$model->feedback->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1'. ( $model->assessment_status && $model->emptying_status  && $model->feedback_status ? ' anchor-disabled' : '' ) . '"><i class="fa fa-pencil"></i></a> ';
                // }
                if (Auth::user()->can('Edit Sludge Collection') && $model->sludge_collection_status){

                    $content .= '<a title="Edit Sludge Collection" href="' . route("sludge-collection.edit", [$model->sludge_collection->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1'. (  $model->feedback_status ? ' anchor-disabled' : '' ) . '"><i class="fa fa-truck-moving"></i></a> ';

                }
                $content .= '<a title="History" href="' . route('application.history', $model->id) . '" class="btn btn btn-info btn-sm mb-1 mb-1"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Application')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger  btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }

                if (Auth::user()->can('Generate Application Report')){
                    if ($model->emptying_status == TRUE) {
                    $content .= '<a title="Generate Report" href="' . route('application.report', [$model->id]) . '" class="btn btn btn-info btn-sm mb-1 mb-1"><i class="fa-regular fa-file-pdf"></i></a> ';
                    }
                }

                $content .= '</div>';
                $content .= \Form::close();

                return $content;
            })
//            ->editColumn('assessment_status',function($model){
//                $content = '<div class="application-quick__actions">';
//                $content .= $model->assessment_status?'<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
//                $content .= '<a title="Assessment Details" href="' . route("application.index", [$model->id]) . '" class="btn btn-info btn-sm mb-1'. ($model->assessment_status ? '' : ' anchor-disabled') . '" ><i class="fa fa-sticky-note"></i></a> ';
//                $content .= '</div>';
//                return $content;
//            })
            ->editColumn('emptying_status',function($model){
                $content = '<div class="application-quick__actions">';
                $content .= $model->emptying_status?'<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
                if ($model->emptying_status == TRUE) {
                    if (Auth::user()->can('View Emptying')){
                        $content .= '<a title="Emptying Service Details" href="' . route("emptying.show", [$model->with('emptying')->where('id',$model->id)->get()->first()->emptying->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-recycle"></i></a> ';
                    }
                } else {
                    if (Auth::user()->can('Add Emptying')){
                        $content .= '<a title="Add Emptying Service Details" href="' . route("emptying.create-id", [$model->id]) . '" class="btn btn-info btn-sm mb-1'. ($model->assessment_status ? '' : ' anchor-disabled') . '"><i class="fa fa-recycle"></i></a> ';
                    }
                }
                $content .= '</div>';
                return $content;
            })
            ->editColumn('feedback_status',function ($model){
                $content = '<div class="application-quick__actions">';
                $content .= $model->feedback_status?'<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';

                if($model->feedback_status == FALSE)
                {
                    if (Auth::user()->can('Add Feedback')){
                        $content .= '<a title="Feedback Details" href="' . route("feedback.create-Feedback", [$model->id]) . '" class="btn btn-info btn-sm mb-1'. ( $model->emptying_status ? '' : ' anchor-disabled') . '"><i class="fa fa-pencil"></i></a> ';
                    }
                }
                else
                {
                    if (Auth::user()->can('View Feedback')){
                        $content .= '<a title="Feedback Details" href="' . route("feedback.show", [$model->feedback->id]) . '" class="btn btn-info btn-sm mb-1'. ( $model->emptying_status ? '' : ' anchor-disabled') . '"><i class="fa fa-pencil"></i></a> ';
                    }
                }
                $content .= '</div>';
                return $content;
            })
            ->editColumn('sludge_collection_status',function ($model){
                $content = '<div class="application-quick__actions">';
                $content .= $model->sludge_collection_status?'<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';

                if($model->sludge_collection_status == FALSE)
                {
                    if (Auth::user()->can('Add Sludge Collection')){
                        $content .= '<a title="Add Sludge Collection" href="' . route("sludge-collection.create-id", [$model->id]) . '" class="btn btn-info btn-sm mb-1'. ( $model->emptying_status ? '' : ' anchor-disabled') . '"><i class="fa fa-truck-moving"></i></a> ';
                    }
                }
                else
                {
                    if (Auth::user()->can('View Sludge Collection')){
                        $content .= '<a title="Sludge Collection Details" href="' . route("sludge-collection.show", [$model->sludge_collection->id]) . '" class="btn btn-info btn-sm mb-1'. ( $model->emptying_status ? '' : ' anchor-disabled') . '"><i class="fa fa-truck-moving"></i></a> ';
                    }
                }
                $content .= '</div>';
                return $content;
            })
            ->editColumn('service_provider_id',function ($model){
                 return $model->service_provider()->withTrashed()->first()->company_name??'Not Assigned';
            })
            ->rawColumns(['assessment_status','emptying_status','feedback_status','sludge_collection_status','action'])

            ->make(true);
    }

    /**
     * Get building details of specified Application.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getBuildingDetails(Request $request){
        try {
            $building = Building::where('bin','=',$request->house_number)->firstOrFail();
            $containments = $building->containments;
            $owner = $building->owners;
            $road = $building->roadlines;
            $application = Application::orderBy('id','DESC')->where('house_number',$request->house_number)->first();
            if ($containments->isEmpty()){
                return JsonResponse::fromJsonString(json_encode([
                    "error" => "There is no containment for this building!"
                ]),404);
            }
        } catch (\Throwable $e) {
            return JsonResponse::fromJsonString(json_encode([
                "error" => "Error getting building details!"
            ]),500);
        }
        return JsonResponse::fromJsonString(json_encode([
            'test'=>$road,
            "customer_name" => $owner->owner_name??null,
            "customer_gender" => $owner->owner_gender??null,
            "customer_contact" => $owner->owner_contact??null,
            "road" => $road->code??null,
            "ward" => $building->ward??null,
            "containments" => $containments??null,
            "household_served" => $building->household_served??null,
            "population_served" => $building->population_served??null,
            "toilet_count" => $building->toilet_count??null,

            "status" => !$application || $application->emptying_status === null || $application->emptying_status
        ]),200);
    }

    /**
     * Store new application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     */
    public function createApplication(ApplicationRequest $request)
    {
        $application = '';
        if ($request->validated()){
            try {
                DB::transaction(function () use ($request) {
                    $application = Application::create($request->all());
                    $building = Building::where('bin','=',$application->house_number)->firstOrFail();
                    $owner = $building->owners;
                    $application->containment_id = $building->containments->first()->id;
                    $application->customer_name = $request->customer_name??$owner->owner_name;
                    $application->customer_contact = $request->customer_contact??$owner->owner_contact;
                    $application->customer_gender = $request->customer_gender??$owner->owner_gender;

                    $owner->fill([
                            "owner_name" => $request->customer_name??$owner->owner_name,
                            "owner_gender" => $request->customer_gender??$owner->owner_gender,
                            "owner_contact" => $request->customer_contact??$owner->owner_contact
                        ]
                    )->save();
                    $building->fill([
                        "ward" => $request->ward??$building->ward,
                        "road_code" => $request->road_code,

                    ])->save();
                    $building->household_served = $request->household_served ;
                    $building->population_served = $request->population_served;
                    $building->toilet_count = $request->toilet_count;
                    $building->save();
                    $application->application_date = now()->format('Y-m-d H:i:s');
                    $application->user_id = Auth::user()->id;
                    if($request->autofill === 'on'){
                        $application->applicant_name = $request->customer_name??$owner->owner_name??null;
                        $application->applicant_contact = $request->customer_contact??$owner->owner_contact??null;
                        $application->applicant_gender = $request->customer_gender??$owner->owner_gender??null;
                    };
                    $application->emergency_desludging_status = $request->emergency_desludging_status ?? $request->emergency_desludging_status ?? null;
                    $application->save();
                });
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Application couldn't be created. ".$e);
            }
        }

        return redirect(route('application.index'))->with('success','Application created successfully!');
    }

    /**
     * Update application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     */
    public function updateApplication(ApplicationRequest $request, $id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->update($request->all());
            if ($application->address != '-'){
                $building = Building::where('bin','=',$application->house_number)->firstOrFail();
                $owner = $building->owners;
                $application->customer_name = $request->customer_name??$owner->owner_name;
                $application->customer_contact = $request->customer_contact??$owner->owner_contact;
                $application->customer_gender = $request->customer_gender??$owner->owner_gender;
                $owner->fill([
                        "owner_name" => $request->customer_name??$owner->owner_name,
                        "owner_gender" => $request->customer_gender??$owner->owner_gender,
                        "owner_contact" => $request->customer_contact??$owner->owner_contact
                    ]
                )->save();
                $building->fill([
                    "ward" => $request->ward??$building->ward,
                    "road_code" => $request->road_code??$building->road_code
                ])->save();
            }
            if ($application->address === '-'){
                $application->ward = $request->ward_no_addr??$application->ward;
                $application->road_code = $request->road_code_no_addr??$application->road_code;
                $application->proposed_emptying_date = $request->proposed_emptying_date_no_addr??$application->proposed_emptying_date;
            }
            $application->save();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Application' . $e);
        }
        return redirect(route('application.index'))->with('success','Application updated successfully');
    }

    /**
     * Retrieve application history.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|Redirector
     */
    public function getApplicationHistory($id)
    {
        try {
            $application = Application::findOrFail($id);
            $revisions = Revision::all()
                ->where('revisionable_type',get_class($application))
                ->where('revisionable_id',$id)
                ->groupBy(function($item)
                {
                    return $item->created_at->format("D M j Y");
                })
                ->sortByDesc('created_at')
                ->reverse();
        } catch (\Throwable $e) {
            return redirect(route('application.index'))->with('error','Failed to generate history.');
        }
        return view('fsm.applications.history',compact('application','revisions'));
    }

    /**
     * Export applications.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $house_number = $request->house_number;
        $customer_name = $request->customer_name;
        $ward = $request->ward;
        $application_id = $request->application_id ;
        $emptying_status = $request->emptying_status;
        $feedback_status = $request->feedback_status;
        $sludge_collection_status = $request->sludge_collection_status;
        $road = $request->road_code;
        $proposed_emptying_date = $request->proposed_emptying_date;
        $service_provider_id = $request->service_provider_id;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $columns = [
            'ID',
            'House Number',
            'Ward',
            'Application Date',
            'Service Provider',
            'Proposed Emptying Date',
            'Road Code',
            'Emptying Status',
            'Sludge Collection Status',
            'Feedback Status',
            'Customer Name',
            'Contact Number',
            'Applicant Name',
            'Applicants Contact',
        ];
        $query = Application::select('id',
            'house_number',
            'ward',
            'application_date',
            'service_provider_id',
            'proposed_emptying_date',
            'road_code',
            'emptying_status',
            'sludge_collection_status',
            'feedback_status',
            'customer_name',
            'customer_contact',
            'applicant_name',
            'applicant_contact')->whereNull('deleted_at');
        if (Auth::user()->hasRole('Service Provider - Admin') || Auth::user()->hasRole('Service Provider - Help Desk'))
        {
            $query->where('applications.service_provider_id',"=",Auth::user()->service_provider_id);
        }
        else if(Auth::user()->hasRole('Treatment Plant'))
        {
            return Application::whereHas("emptying",function($q) use($request){
            $query->where("treatment_plant_id","=",Auth::user()->treatment_plant_id);
            });
        }
        else
        {
            $query->whereNull('deleted_at');
        }
        // if (!empty($house_number)) {
        //     $query->where('house_number','ILIKE',"%" . $house_number .  "%");
        // }

        if (!empty($house_number)){
            $query->whereHas('buildings', function ($query) use ($request) {
                $query->where('house_number', 'ILIKE', '%' . $request->house_number . '%');
                $query->orWhere('bin', 'ILIKE', '%' . $request->house_number . '%');

            });
        }
        if (!empty($customer_name)) {
            $query->where('customer_name', 'ILIKE',"%" . $customer_name .  "%");
        }
        if (!empty($ward)) {
            $query->where('ward', $ward);
        }
        if (!empty($application_id)) {
            $query->where('id',$application_id);
        }
        if (!is_null($emptying_status)) {
            $query->where('emptying_status', $emptying_status);
        }
        if (!empty($feedback_status)) {
            $query->where('feedback_status', $feedback_status);
        }
        if (!empty($sludge_collection_status)) {
            $query->where('sludge_collection_status', $sludge_collection_status);
        }
        if (!empty($road)) {
            $query->where('road_code', $road);
        }
        if (!empty($proposed_emptying_date)) {
            $query->where('proposed_emptying_date', $proposed_emptying_date);
        }
        if (!empty($service_provider_id)) {
            $query->where('service_provider_id', $service_provider_id);
        }
         if ($request->date_from && $request->date_to) {
            $query->whereDate('application_date', '>=', $request->date_from);
            $query->whereDate('application_date', '<=', $request->date_to);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Applications.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($applications) use ($writer) {
            foreach($applications as $application) {
               if($application->service_provider_id)
               {
                    $serviceProviderName = $application->service_provider()->withTrashed()->first()->company_name;
               }
                else {
                    $serviceProviderName = null;
                }
                $values = [];
                $values[] = $application->id;
                $values[] = $application->house_number;
                $values[] = $application->ward;
                $values[] = $application->application_date;
                $values[] = $serviceProviderName;
                $values[] = $application->proposed_emptying_date;
                $values[] = $application->road_code;
                $values[] = $application->emptying_status?'True':'False';
                $values[] = $application->sludge_collection_status?'True':'False';
                $values[] = $application->feedback_status?'True':'False';
                $values[] = $application->customer_name;
                $values[] = $application->customer_contact;
                $values[] = $application->applicant_name;
                $values[] = $application->applicant_contact;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }
    /**
    * Fetches and generates a monthly report.
    *
    * @param int $year The year for the report.
    * @param int $month The month for the report.
    * @return \Illuminate\Http\Response The generated PDF report.
    */
    public function fethMonthlyReport($year, $month)
    {
        if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Municipality - IT Admin') && !Auth::user()->hasRole('Municipality - Executive') || Auth::user()->hasRole('Municipality - Help Desk')) {
        $monthWisequery = 'WITH application AS(

           SELECT service_providers.company_name AS serv_name, count(applications.id) AS applicationCount
            from fsm.service_providers
            LEFT JOIN fsm.applications ON service_providers.id= applications.service_provider_id where EXTRACT(YEAR FROM application_date) = '. $year . '
            and EXTRACT(Month from application_date)  = '. $month .'
            AND fsm.service_providers.deleted_at IS NULL

            GROUP BY serv_name
        ),
        emptying as(
            select service_providers.company_name as serv_name, count(emptyings.id)  as emptyCount, sum(total_cost) as totalCost  , sum(volume_of_sludge) as sludgeCount, count(volume_of_sludge) as sCount
            from fsm.service_providers
            LEFT JOIN fsm.applications ON service_providers.id= applications.service_provider_id
            Left JOIN fsm.emptyings ON emptyings.application_id = applications.id  where EXTRACT(YEAR FROM emptyings.emptied_date) ='. $year .'
            and EXTRACT(Month from emptyings.emptied_date)  = '. $month .' and EXTRACT(YEAR FROM applications.application_date) = '. $year . '
            and EXTRACT(Month from applications.application_date)  = '. $month .'
            AND fsm.service_providers.deleted_at IS NULL

            GROUP BY service_providers.company_name
        )
        select application.serv_name, applicationCount, emptyCount, sludgeCount, totalCost,sCount  from application full join emptying ON application.serv_name = emptying.serv_name; ';

        $monthWisecount= DB::Select($monthWisequery);

        $yearCountquery = 'with application as(
            select  count(applications.id) as applicationCount
            from fsm.applications
            where EXTRACT(YEAR FROM application_date) = '. $year .' and EXTRACT(Month from application_date)  <= '. $month .'

        ),
        emptying as(
            SELECT COUNT(emptyings.id) AS emptyCount,
            SUM(total_cost) AS totalCost, SUM(volume_of_sludge) AS sludgeCount, count(volume_of_sludge) AS sCount  FROM  fsm.emptyings
            LEFT JOIN fsm.applications ON emptyings.application_id = applications.id WHERE
            EXTRACT(YEAR FROM emptyings.emptied_date) = ' . $year . '
            AND EXTRACT(MONTH FROM emptyings.emptied_date) <= ' . $month . '
            AND EXTRACT(YEAR FROM applications.application_date) = ' . $year . '
            AND EXTRACT(MONTH FROM applications.application_date) <= ' . $month . '

        )
        select applicationCount, emptyCount, sludgeCount, totalCost , sCount from application, emptying; ';

        $yearCount= DB::Select($yearCountquery);

        $wardMonthlyquery = ' with application as(
            select count(applications.id) as applicationCount ,APPLICATIONS.ward as award
                   from fsm.applications
                   where EXTRACT(YEAR FROM application_date) = '. $year .' and EXTRACT(MONTH FROM application_date) <= '. $month .'
                   GROUP BY APPLICATIONS.ward
         ),
          emptying as(
            select count(emptyings.id)  as emptyCount, sum(total_cost) as totalCost, sum(volume_of_sludge) as sludgeCount, count(volume_of_sludge) as sCount,ward as eward
                   from fsm.emptyings
                   Left JOIN fsm.applications  ON applications.id= emptyings.application_id  WHERE EXTRACT(YEAR FROM emptyings.emptied_date) = '. $year .'
                   and EXTRACT(MONTH FROM emptyings.emptied_date) <= '. $month .'
                   AND EXTRACT(YEAR FROM applications.application_date) = ' . $year . '
                   AND EXTRACT(MONTH FROM applications.application_date) <= ' . $month . '
                   GROUP BY APPLICATIONS.ward
               )
               select  applicationCount, emptyCount, sludgeCount, totalCost, sCount, award  from application a
               left join emptying e ON a.award = e.eward ORDER BY award ;' ;

        // converts month number to mont name
        $wardData= DB::Select($wardMonthlyquery);
        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');

        return PDF::loadView('fsm.applications.monthly_report', compact('year', 'monthName','monthWisecount','yearCount','wardData'))->inline('Monthly Report.pdf');
          }
          else{

            $service_provider_id = User::where('id', '=',Auth::id())->pluck('service_provider_id')->first();

            $monthWisequery = 'with application as(
                select service_providers.company_name as serv_name, count(applications.id) as applicationCount
                from fsm.service_providers
                LEFT JOIN fsm.applications ON service_providers.id= applications.service_provider_id
                where EXTRACT(YEAR FROM application_date) = '. $year .'
                and EXTRACT(Month from application_date)  = '. $month .'
                and APPLICATIONS.service_provider_id='. $service_provider_id. '
                AND fsm.service_providers.deleted_at IS NULL

                GROUP BY service_providers.company_name
            ),
            emptying as(
                select service_providers.company_name as serv_name, count(emptyings.id)  as emptyCount, sum(total_cost) as totalCost  , sum(volume_of_sludge) as sludgecount, count(volume_of_sludge) as sCount
                from fsm.service_providers
                LEFT JOIN fsm.applications ON service_providers.id= applications.service_provider_id
                Left JOIN fsm.emptyings ON applications.id= emptyings.application_id  where EXTRACT(YEAR FROM emptied_date) ='. $year .'
                and EXTRACT(Month from emptied_date)  = '. $month .'
                AND EXTRACT(YEAR FROM applications.application_date) = ' . $year . '
                AND EXTRACT(MONTH FROM applications.application_date) = ' . $month . '
                and emptyings.service_provider_id='. $service_provider_id. '
                AND fsm.service_providers.deleted_at IS NULL

                GROUP BY service_providers.company_name
            )
            select application.serv_name, applicationCount, emptyCount,sludgecount, totalCost, sCount  from application full join emptying ON application.serv_name = emptying.serv_name; ';

            $monthWisecount= DB::Select($monthWisequery);
            $yearCountquery = 'with application as(
                select  count(applications.id) as applicationCount
                from fsm.applications
                where EXTRACT(YEAR FROM application_date) = '. $year . '
                and EXTRACT(Month from application_date)  <= '. $month .'
                and applications.service_provider_id='. $service_provider_id. '
            ),
            emptying as(
                select  count(emptyings.id)  as emptyCount, sum(total_cost) as totalCost, sum(volume_of_sludge) as sludgeCount,  count(volume_of_sludge) as sCount
                from fsm.emptyings
                LEFT JOIN fsm.applications ON emptyings.application_id = applications.id
                 where EXTRACT(YEAR FROM emptied_date) = '. $year . '
                 and  EXTRACT(Month from emptied_date)  <= '. $month .'
                 AND EXTRACT(YEAR FROM applications.application_date) = ' . $year . '
                   AND EXTRACT(MONTH FROM applications.application_date) <= ' . $month . '
                and emptyings.service_provider_id='. $service_provider_id. '

            )
            select applicationCount, emptyCount, sludgeCount, totalCost, sCount  from application, emptying; ';

            $yearCount= DB::Select($yearCountquery);

            $wardMonthlyquery = ' with application as(
                select count(applications.id) as applicationCount ,APPLICATIONS.ward as award
                    from fsm.APPLICATIONS
                    where EXTRACT(YEAR FROM application_date) = '. $year . '
                    and EXTRACT(MONTH FROM application_date) <= '. $month . '
                    and applications.service_provider_id='. $service_provider_id. '

                       GROUP BY APPLICATIONS.ward
             ),
              emptying as(
                select count(emptyings.id)  as emptyCount, sum(total_cost) as totalCost,count(volume_of_sludge) as sludgeCount,  sum(volume_of_sludge) as sCount, ward as eward
                    from fsm.emptyings
                    Left JOIN fsm.applications ON applications.id= emptyings.application_id  WHERE EXTRACT(YEAR FROM emptied_date) = '. $year . '
                    and EXTRACT(MONTH FROM emptied_date) <= '. $month . '
                    AND EXTRACT(YEAR FROM applications.application_date) = ' . $year . '
                    AND EXTRACT(MONTH FROM applications.application_date) <= ' . $month . '
                    and emptyings.service_provider_id='. $service_provider_id. '
                    GROUP BY APPLICATIONS.ward
                   )

                   select  applicationCount, emptyCount, sludgeCount, totalCost, award, sCount from application a
	                left join emptying e ON a.award = e.eward ORDER BY award; ' ;

            $wardData= DB::Select($wardMonthlyquery);
            // converts month number to mont name
            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');

            return PDF::loadView('fsm.applications.monthly_report', compact('year', 'monthName','monthWisecount','yearCount','wardData'))->inline('Monthly Report.pdf');
          }

        }
        /**
        * Generate a PDF report for a specific application.
        *
        * @param int $id The ID of the application.
        * @return \Illuminate\Http\Response The generated PDF report.
        */
        public function getApplicationReport($id){

            $application = Application::find($id);
           return PDF::View('fsm.applications.application_report',compact('application'))->inline('Application Report.pdf');
        }


}
