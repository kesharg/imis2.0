<?php

namespace App\Models\SwmPaymentInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwmPayment extends Model
{
    use HasFactory;

    protected $table = 'swm_info.swm_payments';
    protected $fillable = [
        'tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date'

    ];
    public static function selectAll(){
        return SwmPayment::select('tax_code', 'owner_name', 'owner_gender', 'owner_contact', 'last_payment_date');
    }
}

