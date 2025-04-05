<?php

namespace App\Models\UtilityInfo;

use App\Models\BuildingInfo\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class SewerLine extends Model
{
    use HasFactory;
    use RevisionableTrait;
    protected $revisionCreationsEnabled = true;
    protected $table = 'utility_info.sewers';
    protected $primaryKey = 'code';
    public $incrementing = false;

       /**
     * Get the buildings associated with the sewer.
     *
     *
     * @return HasMany
     */
    public function buildings(){
        return $this->hasMany(Building::class,'sewer_code','code');
    }
}
