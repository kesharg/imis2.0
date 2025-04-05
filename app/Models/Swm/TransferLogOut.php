<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferLogOut extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.transfer_log_outs';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['transfer_station_id','landfill_site_id','type_of_waste','volume','date_time','received','received_datetime'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['landfill_site','transfer_station'];

    /**
     * Get the landfill site associated with the collection point.
     *
     *
     * @return BelongsTo
     */
    public function landfill_site(){
        return $this->belongsTo(LandfillSite::class);
    }

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
