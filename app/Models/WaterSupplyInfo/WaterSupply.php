<?php

namespace App\Models\WaterSupplyInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSupply extends Model
{
    use HasFactory;

    protected $table = 'watersupply_info.watersupply_payments';
     protected $fillable = [
        'tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date'
        
    ];
    public static function selectAll(){
        return WaterSupply::select('tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date');
    }
}
