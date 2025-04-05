<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class BuildingsOwnerExport implements FromView
{
    private $geom;
    
     /**
     * Constructor method for the BuildingsOwnerExport class.
     *
     * @param  $geom The geometry of the building.
     * @return void
     */
    public function __construct($geom)
    {
        $this->geom = $geom;
    }

    /**
     * Returns an array of sheets for the export.
     *
     * @return array
     */
    public function view(): View
    {
        ini_set('memory_limit', '8192M');
         // Construct the SQL query to fetch building information along with owner details
        $buildingQuery = "SELECT b.*, bo.owner_name, bo.owner_gender, bo.owner_contact FROM building_info.buildings b"
                . " LEFT JOIN building_info.owners bo"
                . " ON b.bin = bo.bin"
                . " WHERE b.deleted_at is null"
                . " AND (ST_Intersects(b.geom, ST_GeomFromText('" . $this->geom . "', 4326)))";
        $buildingResults = DB::select($buildingQuery);
        
        return view('exports.buildings-owners', compact('buildingResults'));
    }
}