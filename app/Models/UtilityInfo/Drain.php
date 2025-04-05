<?php
// Last Modified Date: 09-04-2024
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024)
namespace App\Models\UtilityInfo;
use App\Models\Fsm\TreatmentPlant;
use App\Models\BuildingInfo\Building;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drain extends Model
{
    use HasFactory;
    use RevisionableTrait;
    protected $revisionCreationsEnabled = true;
    protected $table = 'utility_info.drains';
    protected $primaryKey = 'code';
    public $incrementing = false;

    public function buildings(){
        return $this->hasMany(Building::class,'drain_code','code');
    }

    public function treatmentPlant(){
        return $this->hasMany(TreatmentPlant::class,'tp_id','id');
    }
}
