<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferStation extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.transfer_stations';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name','ward','separation_facility','area','capacity','geom'];

    /**
     * Get all the transfer stations.
     *
     *
     * @return TransferStation[]|Collection
     */

    public static function getTransferStations(){
        return TransferStation::all()->whereNull('deleted_at');
    }

}
