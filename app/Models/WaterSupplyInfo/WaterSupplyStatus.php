<?php

namespace App\Models\WaterSupplyInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSupplyStatus extends Model
{
    use HasFactory;

    protected $table = 'watersupply_info.watersupply_payment_status';
    protected $primaryKey = 'tax_id';

    public static function selectAll(){
        return WaterSupplyStatus::select('tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date');
    }
}
