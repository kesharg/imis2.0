<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceArea extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.service_areas';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name','service_provider_id','geom'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['service_provider'];

    /**
     * Get the service provider associated with the route.
     *
     *
     * @return BelongsTo
     */

    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class);
    }
}
