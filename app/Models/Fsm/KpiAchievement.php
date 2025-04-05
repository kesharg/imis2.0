<?php

namespace App\Models\Fsm;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class KpiAchievement extends Model
{
    use HasFactory;
    use RevisionableTrait;
    use SoftDeletes;
    protected $revisionCreationsEnabled = true;
    protected $table = 'fsm.kpi_achievements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_provider_id',
        'year',
        'indicator_id',
        'target',
        'achievement'
    ];
}
