<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandfillSite extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.landfill_sites';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name','ward','area','capacity','life_span','status','geom','operated_by'];

    /**
     * Get all the landfill sites.
     *
     *
     * @return LandfillSite[]|Collection
     */
    public static function getLandfillSites(){
        return LandfillSite::all()->whereNull('deleted_at');
    }
}
