<?php

namespace App\Http\Controllers\Swm\SwmRegistrations;

use App\Classes\FormField;
use App\Http\Controllers\Controller;
use App\Http\Requests\swm\registrations\LandfillSiteRequest;
use App\Models\Swm\LandfillSite;
use App\Services\Swm\SwmRegistrations\LandfillSiteService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LandfillSiteController extends Controller
{
    protected LandfillSiteService $landfillSiteService;

    public function __construct(LandfillSiteService $landfillSiteService)
    {
        $this->landfillSiteService = $landfillSiteService;
    }

    /**
     * Display a list of landfill sites.
     *
     * @return View
     */
    public function index()
    {
        $createBtnLink = Auth::user()->can('Add Landfill Site')?route('landfill-site.create'):null;
        $createBtnTitle = 'Add Landfill Site';
        $filterFormFields = $this->landfillSiteService->getFilterFormFields();
        $exportBtnLink = Auth::user()->can('Export Landfill Sites')?$this->landfillSiteService->getExportRoute():null;
        return view('swm.swm-registrations.landfill-site.index',compact('createBtnLink','createBtnTitle','filterFormFields','exportBtnLink'));
    }

    /**
     * Prepare data for the DataTable.
     *
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function getData(Request $request)
    {
        $landfillSiteData = $this->landfillSiteService->getAllLandfillSites();
        return DataTables::of($landfillSiteData)
            ->filter(function ($query) use ($request) {
                if ($request->name){
                    $query->where('name','ILIKE','%'.$request->name.'%');
                }
                if ($request->ward){
                    $query->where('ward',$request->ward);
                }
                if ($request->status){
                    $query->where('status',$request->status);
                }
                if ($request->operated_by){
                    $query->where('operated_by',$request->operated_by);
                }
            })
            ->addColumn('action', function ($model) {
                $content = \Form::open(['method' => 'DELETE', 'route' => ['landfill-site.destroy', $model->id]]);
                $content .= '<div class="btn-group">';
                if (Auth::user()->can('Edit Landfill Site')){
                    $content .= '<a title="Edit" href="' . route('landfill-site.edit', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Landfill Site')){
                    $content .= '<a title="Detail" href="' . route('landfill-site.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a> ';
                }
                $content .= '<a title="History" href="' . route('landfill-site.show', [$model->id]) . '" class="btn btn-info btn-xs"><i class="fa fa-history"></i></a> ';
                if (Auth::user()->can('Delete Landfill Site')){
                    $content .= '<a title="Delete"  class="delete btn btn-danger btn-xs">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= '</div>';
                $content .= \Form::close();
                return $content;
            })
            ->make(true);
    }

    /**
     * Display the create form for landfill site.
     *
     * @return View
     */
    public function create()
    {
        return view('swm.swm-registrations.landfill-site.create',[
            'formAction' => $this->landfillSiteService->getCreateFormAction(),
            'indexAction' => $this->landfillSiteService->getIndexAction(),
            'formFields' => $this->landfillSiteService->getCreateFormFields(),
        ]);
    }

    /**
     * Store a newly created landfill site in storage.
     *
     * @param LandfillSiteRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(LandfillSiteRequest $request)
    {
        if ($request->validated()){
            try {
                LandfillSite::create($request->all());
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('error',"Error! Landfill Site couldn't be created!");
            }
        }
        return redirect(route('landfill-site.index'))->with('success','Landfill Site created successfully');
    }

    /**
     * Display the specified landfill site.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $landfillSite = LandfillSite::find($id);
        if ($landfillSite) {
            $page_title = "Landfill Site Details";
            $indexAction = $this->landfillSiteService->getIndexAction();
            $formFields = $this->landfillSiteService->getShowFormFields($landfillSite);
            return view('layouts.show', compact('page_title', 'formFields','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified landfill site.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $landfillSite = LandfillSite::find($id);
        if ($landfillSite) {
            $page_title = "Edit Landfill Site";
            $formFields = $this->landfillSiteService->getEditFormFields($landfillSite);
            $indexAction = $this->landfillSiteService->getIndexAction();
            $formAction = $this->landfillSiteService->getEditFormAction($landfillSite);
            return view('layouts.edit',compact('page_title','formFields','formAction','indexAction'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified landfill site in storage.
     *
     * @param LandfillSiteRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(LandfillSiteRequest $request, $id)
    {
        try {
            $landfillSite = LandfillSite::findOrFail($id)->update($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error','Failed to update Landfill Site');
        }
        return redirect(route('landfill-site.index'))->with('success','Landfill Site updated successfully');
    }

    /**
     * Remove the specified landfill site from storage.
     *
     * @param  int  $id
     * @return Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $landfillSite = LandfillSite::findOrFail($id)->delete();
        } catch (\Throwable $e) {
            return redirect(route('landfill-site.index'))->with('error','Failed to delete Landfill Site');
        }
        return redirect(route('landfill-site.index'))->with('success','Landfill Site deleted successfully');

    }

    /**
     * Export landfill sites to csv.
     *
     * @return Redirector|RedirectResponse
     */

    public function export(Request $request)
    {
        try {
            $this->landfillSiteService->export($request);
        } catch (\Throwable $e) {
            return redirect(route('landfill-site.index'))->with('error','Failed to export landfill sites.');
        }
    }
}
