<?php
// Last Modified Date: 18-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022)
namespace App\Services\Fsm;

use App\Models\Fsm\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use DB;
use Carbon\Carbon;
use Auth;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Yajra\DataTables\DataTables;
use App\Enums\ServiceProviderStatus;
use App\Models\Fsm\Feedback;

class ServiceProviderService
{

    protected $session;
    protected $instance;

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
    }

    /**
     * Get all the All Employee Info.
     *
     *
     * @return AllData[]|Collection
     */
    public function getAllServiceProviders($data)
    {
        $serviceProviderData = ServiceProvider::select('*')->whereNull('deleted_at');

        return Datatables::of($serviceProviderData)
            ->filter(function ($query) use ($data) {
                if ($data['company_name']) {
                    $query->where('company_name', 'ILIKE', '%' .  trim($data['company_name']) . '%');
                }
                if ($data['ward']) {
                    $query->where('ward', 'ILIKE', '%' .  trim($data['ward']) . '%');
                }
                if ($data['email']) {
                    $query->where('email', 'ILIKE', '%' .  trim($data['email']) . '%');
                }
                if ($data['contact_person']) {
                    $query->where('contact_person', 'ILIKE', '%' .  trim($data['contact_person']) . '%');
                }
                if ($data['company_location']) {
                    $query->where('company_location', 'ILIKE', '%' .  trim($data['company_location']) . '%');
                }
                if ($data['status']) {
                    $query->where('status', $data['status']);
                }
            })

            ->editColumn('rating', function ($model) {
                $content = ServiceProvider::select('service_providers.id', 'feedbacks.fsm_service_quality')
                    ->join('fsm.feedbacks', 'service_providers.id', '=', 'feedbacks.service_provider_id')
                    ->where('service_providers.id',$model->id)
                    ->orderBy('feedbacks.created_at', 'desc')
                    ->limit(5)
                    ->pluck('feedbacks.fsm_service_quality');

                $htmlContent = '';

                foreach ($content as $quality) {
                    $starSymbol = $quality ? '★' : '☆';
                    $htmlContent .= $starSymbol;
                }

                return $htmlContent;
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['service-providers.destroy', $model->id]]);

                if (Auth::user()->can('Edit Service Provider')) {
                    $content .= '<a title="Edit" href="' . action("Fsm\ServiceProviderController@edit", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }

                if (Auth::user()->can('View Service Provider')) {
                    $content .= '<a title="Detail" href="' . action("Fsm\ServiceProviderController@show", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }

                if (Auth::user()->can('View Service Provider History')) {
                    $content .= '<a title="History" href="' . action("Fsm\ServiceProviderController@history", [$model->id]) . '" class="btn btn-info btn-sm mb-1"><i class="fa fa-history"></i></a> ';
                }

                if (Auth::user()->can('Delete Service Provider')) {
                    $content .= '<a href="#" title="Delete" class="delete btn btn-danger btn-sm mb-1"><i class="fa fa-trash"></i></a> ';
                }

                $content .= \Form::close();
                return $content;
            })
            ->editColumn('status', function ($model) {
                return ServiceProviderStatus::getDescription($model->status);
            })
            ->make(true);
    }
    /**
     * Store or update a newly created resource in storage.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function storeOrUpdate($id, $data)
    {
        if (is_null($id)) {
            $serviceProvider = new ServiceProvider();
            $serviceProvider->company_name = $data['company_name'] ? $data['company_name'] : null;
            $serviceProvider->email = $data['email'] ? $data['email'] : null;
            $serviceProvider->ward = $data['ward'] ? $data['ward'] : null;
            $serviceProvider->company_location = $data['company_location'] ? $data['company_location'] : null;
            $serviceProvider->contact_person = $data['contact_person'] ? $data['contact_person'] : null;
            $serviceProvider->contact_gender = $data['contact_gender'] ? $data['contact_gender'] : null;
            $serviceProvider->contact_number = $data['contact_number'] ? $data['contact_number'] : null;
            $serviceProvider->status = $data['status'] ? $data['status'] : 0;

            $serviceProvider->save();
            return $serviceProvider->id;
        } else {
            $serviceProvider = ServiceProvider::find($id);
            $serviceProvider->company_name = $data['company_name'] ? $data['company_name'] : null;
            $serviceProvider->email = $data['email'] ? $data['email'] : null;
            $serviceProvider->ward = $data['ward'] ? $data['ward'] : null;
            $serviceProvider->company_location = $data['company_location'] ? $data['company_location'] : null;
            $serviceProvider->contact_person = $data['contact_person'] ? $data['contact_person'] : null;
            $serviceProvider->contact_gender = $data['contact_gender'] ? $data['contact_gender'] : null;
            $serviceProvider->contact_number = $data['contact_number'] ? $data['contact_number'] : null;
            $serviceProvider->status = $data['status'] ? $data['status'] : 0;


            $serviceProvider->save();
            if ($data['status'] == 0) {
                if ($serviceProvider->applications()->exists()) {
                    $applicationsCount =  $serviceProvider->applications()->where('emptying_status', 'false')->count();

                    if ($applicationsCount > 0) {
                        /*While updating service provider status to not operational, update service_provider_id to null having emptying_status false i.e. if emptying service is not done yet for an application and corresponding service provider is deleted or not in operation, help desk should have privilege to assign another service provider */
                        $results = \App\Models\Fsm\Application::where(['service_provider_id' => $id, 'emptying_status' => 'false'])->get();
                        foreach ($results as $result) {
                            $application = \App\Models\Fsm\Application::find($result->id);
                            $application->service_provider_id = null;
                            $application->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Download a listing of the specified resource from storage.
     *
     * @param array $data
     * @return null
     */
    public function download($data)
    {
        $searchData = $data['searchData'] ? $data['searchData'] : null;

        $company_name = $data['company_name'] ? $data['company_name'] : null;
        $ward = $data['ward'] ? $data['ward'] : null;
        $company_location = $data['company_location'] ? $data['company_location'] : null;
        $email = $data['email'] ? $data['email'] : null;
        $contact_person = $data['contact_person'] ? $data['contact_person'] : null;

        $status = $data['status'] ? $data['status'] : null;
        $searchData = $data['searchData'] ? $data['searchData'] : 0;

        $columns = ['Company Name', 'Ward Number', 'Company Location', 'Email', 'Contact Person Name', 'Contact Person Number', 'Status'];
        $query = ServiceProvider::select('company_name', 'ward', 'company_location', 'email', 'contact_person', 'contact_number', 'status')->whereNull('deleted_at');


        if (!empty($company_name)) {
            $query->where('company_name', 'ILIKE', '%' .  trim($company_name) . '%');
        }

        if (!empty($email)) {
            $query->where('email', 'ILIKE', '%' .  trim($email) . '%');
        }

        if (!empty($ward)) {
            $query->where("ward", $ward);
        }

        if (!empty($company_location)) {
            $query->where('company_location', 'ILIKE', '%' .  trim($company_location) . '%');
        }

        if (!empty($contact_person)) {
            $query->where('contact_person', 'ILIKE', '%' .  trim($contact_person) . '%');
        }

        if (!empty($status)) {
            $query->where('status',  $status);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Service Providers.CSV')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($serviceProviders) use ($writer) {
            foreach ($serviceProviders as $serviceProvider) {
                $values = [];
                $values[] = $serviceProvider->company_name;
                $values[] = $serviceProvider->ward;
                $values[] = $serviceProvider->company_location;
                $values[] = $serviceProvider->email;
                $values[] = $serviceProvider->contact_person;
                $values[] = $serviceProvider->contact_number;
                $values[] = ServiceProviderStatus::getDescription($serviceProvider->status);
                $writer->addRow($values);
            }
        });

        $writer->close();
    }
}
