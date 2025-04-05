<?php

namespace App\Models\Fsm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyPerformanceIndicator extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'fsm.key_performance_indicators';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'indicator',
        'target'
    ];
}
