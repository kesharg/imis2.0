<?php

namespace App\Models\BuildingInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class SanitationSystemTechnology extends Model
{
    use SoftDeletes;

    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
    protected $table = 'building_info.sanitation_system_technologies';
    protected $primaryKey = 'id';


    /**
     * Get the sanitation system associated with the sanitation system technology.
     *
     *
     * @return BelongsTo
     */
    public function sanitationSystem(){
        return $this->belongsTo(SanitationSystem::class, 'sanitation_type_id', 'id');
    }

}
