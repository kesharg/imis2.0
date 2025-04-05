<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.routes';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['service_provider_id','name','type','geom','time'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['service_provider'];

    /**
     * Get all the routes.
     *
     *
     * @return Route[]|Collection
     */

    public static function getRoutes(){
        return Route::all()->whereNull('deleted_at');
    }

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
