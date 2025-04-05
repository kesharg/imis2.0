<?php

namespace App\Models\Swm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

    /**
     * The table name along with the schema.
     *
     * @var String
     */
    protected $table= 'swm.service_providers';

    /**
     * The fillable fields for mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name','start_date','geom'];
}
