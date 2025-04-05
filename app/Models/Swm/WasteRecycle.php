<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteRecycle extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.waste_recycles';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['transfer_station_id','volume','waste_type','date_time','rate','total_price'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['transfer_station'];

    /**
     * Get the transfer station associated with the collection point.
     *
     *
     * @return BelongsTo
     */
    public function transfer_station(){
        return $this->belongsTo(TransferStation::class);
    }
}
