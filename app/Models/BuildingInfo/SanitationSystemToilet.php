<?php

namespace App\Models\BuildingInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class SanitationSystemToilet extends Model
{



    protected $table = 'building_info.sanitation_systems';
    protected $primaryKey = 'id';
}
