<?php

namespace App\Services\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Helpers\Common;
use App\Models\Fsm\Application;
use App\Models\LayerInfo\Ward;
use App\Models\Swm\LandfillSite;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LandfillSiteService {

    protected $session;
    protected $instance;
    protected string $indexAction;
    protected $exportRoute;
    protected $createPartialForm,$createFormFields,$createFormAction;
    protected $showFormFields,$editFormFields,$filterFormFields;


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

        $this->createPartialForm = 'swm.landfill-site.partial-form';
        $this->indexAction = route('landfill-site.index');
        $this->exportRoute = route('landfill-site.export');
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
                label: 'Lifespan (in years)',
                labelFor: 'life_span',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'life_span',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'status',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getLandfillSiteStatus(),
                selectedValue: null
            ),
            new FormField(
                label: 'Operated By',
                labelFor: 'operated_by',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'operated_by',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getLandfillSiteOperators(),
                selectedValue: null
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'poly_geom_drawer',
                inputId: 'geom',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
        ];
        $this->createFormAction = route('landfill-site.store');

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
                    label: 'Status',
                    labelFor: 'status',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'status',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: Common::getLandfillSiteStatus(),
                    selectedValue: null
                ),            ],
            [
                new FormField(
                    label: 'Operated By',
                    labelFor: 'operated_by',
                    labelClass: 'col-sm-1 control-label',
                    inputType: 'select',
                    inputId: 'operated_by',
                    inputValue: null,
                    inputClass: 'form-control',
                    selectValues: Common::getLandfillSiteOperators(),
                    selectedValue: null
                ),
            ],
        ];
    }

    /**
     * Get form fields for creating landfill site.
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
     * Get action/route for exporting landfill sites.
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
    public function getShowFormFields($landfillSite){
        $this->showFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'name',
                inputValue: $landfillSite->name,
                inputClass: 'form-control',
                labelValue: $landfillSite->name,
            ),
            new FormField(
                label: 'Ward',
                labelFor: 'ward',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'ward',
                inputValue: $landfillSite->ward,
                inputClass: 'form-control',
                labelValue: $landfillSite->ward,
            ),
            new FormField(
                label: 'Area',
                labelFor: 'area',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'area',
                inputValue: $landfillSite->area,
                inputClass: 'form-control',
                labelValue: $landfillSite->area,
            ),
            new FormField(
                label: 'Capacity',
                labelFor: 'capacity',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'capacity',
                inputValue: $landfillSite->capacity,
                inputClass: 'form-control',
                labelValue: $landfillSite->capacity,
            ),
            new FormField(
                label: 'Life Span (in years)',
                labelFor: 'life_span',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'life_span',
                inputValue: $landfillSite->life_span,
                inputClass: 'form-control',
                labelValue: $landfillSite->life_span,
            ),
            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'status',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $landfillSite->status,
            ),
            new FormField(
                label: 'Operated By',
                labelFor: 'operated_by',
                labelClass: 'col-sm-4 control-label',
                inputType: 'label',
                inputId: 'operated_by',
                inputValue: null,
                inputClass: 'form-control',
                labelValue: $landfillSite->operated_by,
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'geom_viewer',
                inputId: 'geom',
                inputClass: 'form-control',
                labelValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.landfill_sites WHERE id = $landfillSite->id")[0]->geom
            ),
        ];
        return $this->showFormFields;
    }

    /**
     * Get form fields for editing collection point.
     *
     * @return array
     */
    public function getEditFormFields($landfillSite){
        $this->editFormFields = [
            new FormField(
                label: 'Name',
                labelFor: 'name',
                labelClass: 'col-sm-4 control-label',
                inputType: 'text',
                inputId: 'name',
                inputValue: $landfillSite->name,
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
                inputValue: $landfillSite->ward,
                inputClass: 'form-control',
                selectValues: Ward::all()->sortBy('ward')->pluck('ward','ward')->toArray(),
                selectedValue: $landfillSite->ward,
            ),
            new FormField(
                label: 'Area',
                labelFor: 'area',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'area',
                inputValue: $landfillSite->area,
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
                inputValue: $landfillSite->capacity,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Life Span (in years)',
                labelFor: 'life_span',
                labelClass: 'col-sm-4 control-label',
                inputType: 'number',
                inputId: 'life_span',
                inputValue: $landfillSite->life_span,
                inputClass: 'form-control',
                selectValues: [],
                selectedValue: null
            ),
            new FormField(
                label: 'Status',
                labelFor: 'status',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'status',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getLandfillSiteStatus(),
                selectedValue: $landfillSite->status,
            ),
            new FormField(
                label: 'Operated By',
                labelFor: 'operated_by',
                labelClass: 'col-sm-4 control-label',
                inputType: 'select',
                inputId: 'operated_by',
                inputValue: null,
                inputClass: 'form-control',
                selectValues: Common::getLandfillSiteOperators(),
                selectedValue: $landfillSite->operated_by,
            ),
            new FormField(
                label: 'Set the location',
                labelFor: 'geom',
                labelClass: 'col-sm-4 control-label',
                inputType: 'poly_geom_drawer',
                inputId: 'geom',
                inputValue: DB::select("SELECT ST_AsTEXT(geom) AS geom FROM swm.landfill_sites WHERE id = $landfillSite->id")[0]->geom,
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
    public function getEditFormAction($landfillSite){
        $this->editFormAction = route('landfill-site.update',$landfillSite);
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
     * @return LandfillSite[]|Collection
     */
    public function getAllLandfillSites()
    {
        return LandfillSite::latest()->whereNull('deleted_at');
    }

    /**
     * Export landfill sites.
     *
     * @throws Exception
     */
    public function export(Request $request)
    {
        $name = $request->name;
        $ward = $request->ward;
        $status = $request->status;
        $operated_by = $request->operated_by;
        $columns = [
            'ID',
            'Name',
            'Ward',
            'Area',
            'Capacity',
            'Lifespan',
            'Status',
            'Operated By'
        ];
        $query = LandfillSite::all()->whereNull('deleted_at')->toQuery();
        if (!empty($name)) {
            $query->where('name',$name);
        }
        if (!empty($ward)) {
            $query->where('ward',$ward);
        }
        if (!empty($status)) {
            $query->where('status',$status);
        }
        if (!empty($operated_by)) {
            $query->where('operated_by',$operated_by);
        }

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(13)
            ->setBackgroundColor(Color::rgb(228, 228, 228))
            ->build();

        $writer = WriterFactory::create(Type::CSV);
        $writer->openToBrowser('Landfill-Sites.csv')
            ->addRowWithStyle($columns, $style); //Top row of excel
        $query->chunk(5000, function ($landfill_sites) use ($writer) {
            foreach($landfill_sites as $landfill_site) {
                $values = [];
                $values[] = $landfill_site->id;
                $values[] = $landfill_site->name;
                $values[] = $landfill_site->ward;
                $values[] = $landfill_site->area;
                $values[] = $landfill_site->capacity;
                $values[] = $landfill_site->life_span;
                $values[] = $landfill_site->status;
                $values[] = $landfill_site->operated_by;
                $writer->addRow($values);
            }
        });

        $writer->close();
    }


}
