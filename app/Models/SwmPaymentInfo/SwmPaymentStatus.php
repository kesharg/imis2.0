<?php

namespace App\Models\SwmPaymentInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwmPaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'swm_info.swm_payment_status';
    protected $primaryKey = 'tax_id';

    public static function selectAll(){
        return TaxPaymentStatus::select('tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date');
    }
}
