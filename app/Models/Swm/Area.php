<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['service_provider_id','type','geom'];
}
