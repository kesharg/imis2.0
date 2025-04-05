<?php
// Last Modified Date: 18-04-2024
//Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)
namespace App\Http\Controllers\TaxPaymentInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxPaymentInfo\TaxPaymentStatus;
use App\Models\LayerInfo\Ward;
use App\Models\TaxPaymentInfo\DueYear;
use DataTables;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\AbstractWriter;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TaxImport;
use Maatwebsite\Excel\HeadingRowImport;
use App\Models\TaxPaymentInfo\TaxPayment;

class TaxPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:List Property Tax Collection', ['only' => ['index']]);
        $this->middleware('permission:Import Property Tax Collection From CSV', ['only' => ['create', 'store']]);
        $this->middleware('permission:Export Property Tax Collection Info', ['only' => ['export']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Property Tax Collection";
        $wards = Ward::getInAscOrder();
        $dueYears = DueYear::getInAscOrder();

        return view('taxpayment-info.index', compact('page_title','wards', 'dueYears'));
    }
    /**
     * Prepare data for the DataTable.
     *
     * @param Request $request
     * @return DataTables
     * @throws Exception
     */
    public function getData(Request $request)
    {
        $buildingData = DB::table(DB::raw('(SELECT DISTINCT ON (tax_code) tax_code, bin,owner_name, owner_gender, owner_contact, ward, match, due_year FROM taxpayment_info.tax_payment_status
          ORDER BY tax_code,tax_payment_id DESC) tax'))
            ->leftjoin('building_info.buildings AS b', 'tax.tax_code', '=', 'b.tax_code')
            ->leftjoin('taxpayment_info.due_years AS due', 'due.value', '=', 'tax.due_year')
            ->select('tax.tax_code', 'tax.bin','tax.owner_name', 'tax.owner_gender', 'tax.owner_contact', 'tax.ward',  'tax.match', 'due.name')
            ->where('b.building_associated_to', null)
            ->where('b.deleted_at', null);


        /*$buildingData = DB::select("SELECT tax.*, due.name  FROM (SELECT DISTINCT ON (tax_code) tax_code, owner_name, owner_gender, owner_contact, ward, match, due_year FROM taxpayment_info.tax_payment_status
            ORDER BY tax_code,tax_payment_id DESC) tax
            LEFT JOIN building_info.buildings b ON tax.tax_code=b.tax_code
            LEFT JOIN taxpayment_info.due_years due ON due.value=tax.due_year
            WHERE b.building_associated_to IS NULL
            AND b.deleted_at IS NULL");*/

        return DataTables::of($buildingData)
            ->filter(function ($query) use ($request) {
                if ($request->dueyear_select) {
                    $query->where('due.name', $request->dueyear_select);
                }
                if ($request->ward_select) {
                    $query->where('b.ward', $request->ward_select);
                }
                if ($request->match) {
                    $query->where('tax.match', $request->match);
                }
            })
            ->make(true);


    }
    /**
     * Display the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = "Import Property Tax Collection ISS";
        return view('taxpayment-info.create', compact('page_title'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '512M');

        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
                if( !in_array( $value->getClientOriginalExtension(), $parameters ) ){
                return false;
            }
            else {
                return true;
            }
        }, 'File must be csv format');
        $this->validate($request,
                ['excelfile' => 'required|file_extension:csv'],
                ['required' => 'The csv file field is required.'],
        );

        if (!$request->hasfile('excelfile')) {

            return redirect('tax-payment/data')->with('error','The csv file is required.');
        }
        if ($request->hasFile('excelfile')) {

                $filename = 'building-tax-payments.' . $request->file('excelfile')->getClientOriginalExtension();
                if (Storage::disk('importtax')->exists('/' . $filename)){
                    Storage::disk('importtax')->delete('/' . $filename);
                    //deletes if already exists
                }
                $stored = $request->file('excelfile')->storeAs('/', $filename, 'importtax');

                if ($stored)
                {
                    $storage = Storage::disk('importtax')->path('/');
                    $location = preg_replace('/\\\\/', '', $storage);

                    $file_selection = Storage::disk('importtax')->listContents();
                    $filename = $file_selection[0]['basename'];

                    //checking csv file has all heading row keys
                    $headings = (new HeadingRowImport)->toArray($location.$filename);
                    $heading_row_errors = array();
                    if (!in_array("tax_code", $headings[0][0])) {
                        $heading_row_errors['tax_code'] = "Heading row : tax_code is required";
                    }
                    if (!in_array("owner_name", $headings[0][0])) {
                        $heading_row_errors['owner_name'] = "Heading row : owner_name is required";
                    }
                    if (!in_array("owner_gender", $headings[0][0])) {
                        $heading_row_errors['owner_gender'] = "Heading row : owner_gender is required";
                    }
                    if (!in_array("owner_contact", $headings[0][0])) {
                        $heading_row_errors['owner_contact'] = "Heading row : owner_contact is required";
                    }
                    if (!in_array("last_payment_date", $headings[0][0])) {
                        $heading_row_errors['last_payment_date'] = "Heading row : last_payment_date is required";
                    }
                    if (count($heading_row_errors) > 0) {
                    return back()->withErrors($heading_row_errors);
                    }
                    \DB::statement('TRUNCATE TABLE taxpayment_info.tax_payments RESTART IDENTITY');
                    #\DB::statement('ALTER SEQUENCE IF exists taxpayment_info.tax_payments_id_seq RESTART WITH 1');
                    $import = new TaxImport();
                    $import->import($location.$filename);

                    $message = 'Successfully Imported Building Tax Payments From Excel.';
                    $filter = \DB::statement("select taxpayment_info.fnc_taxpaymentstatus()");

                    if($filter){
                        $matchCount = DB::table('taxpayment_info.tax_payment_status')->where('match', true)->count();
                        $unMatchCount = DB::table('taxpayment_info.tax_payment_status')->where('match', false)->count();
                        $message = 'Successfully Imported Building Tax Payments From Excel.';
                        $message .= ' No. of matched row is '.$matchCount;
                        $message .= ' and no. of unmatched row is '.$unMatchCount.'.';
                    }
                    #\DB::statement('select taxpayment_info.fnc_insrtupd_taxbuildowner()');
                    \DB::statement('select taxpayment_info.fnc_create_wardproportion()');
                    \DB::statement('select taxpayment_info.fnc_create_gridproportion()');

                    \DB::statement('select taxpayment_info.fnc_updonimprt_gridnward_tax()');

                    return redirect('tax-payment')->with('success',$message);

                }
                else{
                    $message = 'Building Tax Payments Not Imported From Excel.';
                }

        }
        flash('Could not import from excel. Try Again');
        return redirect('tax-payment');
    }
    /**
     * Export building tax payment data to a CSV file.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {

        $ward = $_GET['ward'] ?? null;
        $due_year = $_GET['due_year'] ?? null;
        $match = $_GET['match'] ?? null;

        $columns = ['Tax Code', 'Owner Name', 'Owner Gender', 'Owner Contact', 'Last Payment Date'];

        $query = DB::table('taxpayment_info.tax_payment_status AS tax')
                                ->leftjoin('taxpayment_info.due_years AS due', 'due.value', '=', 'tax.due_year')
                                ->leftjoin('building_info.buildings AS b', 'tax.tax_code', '=', 'b.tax_code')
                                ->select('tax.*', 'due.name')
                                ->where('b.building_associated_to', null)
                                ->where('b.deleted_at', null)
                                ->distinct('tax.tax_code')
                                ->orderBy('tax.tax_code', 'ASC');

        if (!empty($ward)) {
            $query->where('tax.ward', $ward);
        }
        if (!empty($due_year)) {
            $query->where('due.name', $due_year);
        }
        if (($match)) {
            $query->where('tax.match', $match);
        }
        //dd($query);
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('property-tax-collection-iss.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel

        $query->chunk(5000, function ($taxpayments) use ($writer) {
            foreach($taxpayments as $taxpayment) {

                $values = [];
                $values[] = $taxpayment->tax_code;
                $values[] = $taxpayment->owner_name;
                $values[] = $taxpayment->owner_gender;
                $values[] = $taxpayment->owner_contact;
                $values[] = $taxpayment->last_payment_date;

                $writer->addRow($values);
            }
        });

        $writer->close();
    }
}
