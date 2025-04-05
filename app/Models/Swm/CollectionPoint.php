<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionPoint extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.collection_points';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'route_id',
        'type',
        'capacity',
        'ward',
        'service_type',
        'service_provider_id',
        'household_served',
        'status',
        'collection_time',
        'service_area_id',
        'geom'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['route','service_area'];

    /**
     * Get the route associated with the collection point.
     *
     *
     * @return BelongsTo
     */
    public function route(){
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the service area associated with the collection point.
     *
     *
     * @return BelongsTo
     */
    public function service_area(){
        return $this->belongsTo(ServiceArea::class);
    }

    /**
     * Get the service provider associated with the collection point.
     *
     *
     * @return BelongsTo
     */
    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class);
    }

}
