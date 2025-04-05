<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferLogIn extends Model
{
    use HasFactory;
    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.transfer_log_ins';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['route_id','transfer_station_id','type_of_waste','volume','date','time'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['route','transfer_station'];

    /**
     * Get the route associated with the transfer log in.
     *
     *
     * @return BelongsTo
     */
    public function route(){
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the transfer station associated with the transfer log in.
     *
     *
     * @return BelongsTo
     */
    public function transfer_station(){
        return $this->belongsTo(TransferStation::class);
    }
}
