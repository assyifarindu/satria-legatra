<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestSubstage extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_substages';

    protected $fillable = [
        'stage_id',
        'sequence',
        'substage',
        'sla_hours',
        'created_by',
        'updated_by',
    ];

    public function stage()
    {
        return $this->belongsTo(TspRequestStage::class, 'stage_id');
    }


}